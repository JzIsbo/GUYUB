/*
 * ============================================================
 * GUYUB RFID Gate Controller — ESP32 + MFRC522 + Relay
 * ============================================================
 * Fungsi : Membaca UID kartu KTP di portal perumahan.
 *          Jika UID terdaftar & akun aktif → relay buka portal.
 *          Jika tidak terdaftar → akses ditolak (buzzer + LED merah).
 *
 * Hardware yang dibutuhkan:
 *   - ESP32 (DevKit atau sejenisnya)
 *   - MFRC522 RFID Reader (komunikasi SPI)
 *   - Relay modul 5V (1 channel) untuk kontrol solenoid/motor portal
 *   - LED hijau (akses diberikan)
 *   - LED merah (akses ditolak)
 *   - Buzzer aktif 5V
 *   - LCD 16x2 I2C atau OLED (opsional, tampil nama user)
 *
 * Wiring MFRC522 ke ESP32:
 *   MFRC522 | ESP32
 *   --------|-------
 *   SDA(SS) | GPIO 5
 *   SCK     | GPIO 18
 *   MOSI    | GPIO 23
 *   MISO    | GPIO 19
 *   GND     | GND
 *   RST     | GPIO 22
 *   3.3V    | 3.3V
 *
 * Wiring Relay & Indikator:
 *   Relay IN  → GPIO 26 (HIGH = aktif = portal terbuka)
 *   LED Hijau → GPIO 32
 *   LED Merah → GPIO 33
 *   Buzzer    → GPIO 25
 *
 * CATATAN KEAMANAN RELAY:
 *   Relay modul "aktif LOW": IN=LOW → relay ON, IN=HIGH → relay OFF
 *   Relay modul "aktif HIGH": IN=HIGH → relay ON, IN=LOW → relay OFF
 *   Sesuaikan RELAY_ACTIVE_LEVEL di bawah!
 * ============================================================
 */

#include <WiFi.h>
#include <HTTPClient.h>
#include <SPI.h>
#include <MFRC522.h>

// ============================================================
// KONFIGURASI — SESUAIKAN DENGAN LINGKUNGAN ANDA
// ============================================================
const char* WIFI_SSID     = "NAMA_WIFI_ANDA";
const char* WIFI_PASSWORD = "PASSWORD_WIFI_ANDA";

// URL endpoint gate-check di server GUYUB
const char* SERVER_URL    = "https://guyub.unique-monsters.my.id/api/rfid/gate-check";

// API key — HARUS sama dengan RFID_DEVICE_KEY di file .env server
const char* DEVICE_KEY    = "GuyubRfid2026_SecretKey_ChangeMe";

// ID unik perangkat ini (untuk log di server)
const char* DEVICE_ID     = "gate-01";

// ============================================================
// PIN MFRC522 & RELAY (OTOMATIS NANO ESP32 vs ESP32 STANDARD)
// ============================================================
#if defined(ARDUINO_NANO_ESP32) || defined(ARDUINO_ARCH_ESP32) && defined(D10)
  #define SS_PIN     D10
  #define RST_PIN    D9
  #define RELAY_PIN  D4
  #define LED_GREEN  D5
  #define LED_RED    D6
  #define BUZZER_PIN D7
#else
  #define SS_PIN     5
  #define RST_PIN    22
  #define RELAY_PIN  26
  #define LED_GREEN  32
  #define LED_RED    33
  #define BUZZER_PIN 25
#endif

// Relay aktif HIGH (1) atau aktif LOW (0)?
// Kebanyakan modul relay murah: aktif LOW
#define RELAY_ACTIVE_LEVEL  LOW
#define RELAY_IDLE_LEVEL    HIGH

// Fallback: buka portal offline jika server tidak respond (false = aman default)
#define ALLOW_OFFLINE_FALLBACK false

// ============================================================
// Variabel global
// ============================================================
MFRC522 rfid(SS_PIN, RST_PIN);
String lastUID    = "";
unsigned long lastScanTime = 0;
const unsigned long SCAN_COOLDOWN_MS = 4000; // 4 detik debounce

// ============================================================
void setup() {
    Serial.begin(115200);
    delay(500);

    // Init pin
    pinMode(LED_GREEN,  OUTPUT);
    pinMode(LED_RED,    OUTPUT);
    pinMode(RELAY_PIN,  OUTPUT);
    pinMode(BUZZER_PIN, OUTPUT);

    // Portal tertutup saat boot
    digitalWrite(RELAY_PIN, RELAY_IDLE_LEVEL);
    digitalWrite(LED_GREEN, LOW);
    digitalWrite(LED_RED,   LOW);

    // Init SPI & RFID reader
    SPI.begin();
    rfid.PCD_Init();
    rfid.PCD_SetAntennaGain(rfid.RxGain_max);

    Serial.println("=================================");
    Serial.println("  GUYUB - RFID Gate Controller");
    Serial.println("=================================");

    // Koneksi WiFi
    Serial.printf("Connecting to WiFi: %s\n", WIFI_SSID);
    WiFi.begin(WIFI_SSID, WIFI_PASSWORD);

    int retries = 0;
    while (WiFi.status() != WL_CONNECTED && retries < 30) {
        delay(500);
        Serial.print(".");
        retries++;
    }

    if (WiFi.status() == WL_CONNECTED) {
        Serial.printf("\nWiFi OK! IP: %s\n", WiFi.localIP().toString().c_str());
        // Blink hijau: siap
        for (int i = 0; i < 3; i++) {
            digitalWrite(LED_GREEN, HIGH); delay(200);
            digitalWrite(LED_GREEN, LOW);  delay(200);
        }
    } else {
        Serial.println("\nWiFi GAGAL!");
        indicatorDeny();
    }

    Serial.println("\nSistem portal siap. Tempelkan KTP untuk akses.");
}

// ============================================================
void loop() {
    // Reconnect WiFi jika terputus
    if (WiFi.status() != WL_CONNECTED) {
        WiFi.reconnect();
        delay(5000);
        return;
    }

    // Cek kartu baru
    if (!rfid.PICC_IsNewCardPresent() || !rfid.PICC_ReadCardSerial()) {
        return;
    }

    // Baca UID
    String uid = readUID();

    // Debounce
    if (uid == lastUID && (millis() - lastScanTime) < SCAN_COOLDOWN_MS) {
        rfid.PICC_HaltA();
        rfid.PCD_StopCrypto1();
        return;
    }

    lastUID      = uid;
    lastScanTime = millis();

    Serial.printf("\n[SCAN] UID: %s\n", uid.c_str());

    // Cek akses ke server
    checkAccess(uid);

    rfid.PICC_HaltA();
    rfid.PCD_StopCrypto1();
}

// ============================================================
// Baca UID dari kartu dan format sebagai HEX uppercase
// ============================================================
String readUID() {
    String uid = "";
    for (byte i = 0; i < rfid.uid.size; i++) {
        if (rfid.uid.uidByte[i] < 0x10) uid += "0";
        uid += String(rfid.uid.uidByte[i], HEX);
    }
    uid.toUpperCase();
    return uid;
}

// ============================================================
// Kirim UID ke server dan proses response
// ============================================================
void checkAccess(String uid) {
    if (WiFi.status() != WL_CONNECTED) {
        Serial.println("[ERROR] WiFi tidak terhubung!");
        if (ALLOW_OFFLINE_FALLBACK) {
            // Fallback offline: tolak akses untuk keamanan
            Serial.println("[OFFLINE] Akses ditolak (server tidak dapat dijangkau).");
        }
        indicatorDeny();
        return;
    }

    HTTPClient http;
    http.begin(SERVER_URL);
    http.addHeader("Content-Type", "application/json");
    http.addHeader("X-Device-Key", DEVICE_KEY);
    http.setTimeout(10000); // 10 detik timeout

    // Build JSON payload natively without ArduinoJson
    String payload = "{\"uid\":\"" + uid + "\",\"device_id\":\"" + String(DEVICE_ID) + "\"}";

    Serial.printf("[HTTP] Checking UID: %s\n", uid.c_str());
    int httpCode = http.POST(payload);
    Serial.printf("[HTTP] Code: %d\n", httpCode);

    if (httpCode == 200) {
        // AKSES DIBERIKAN
        String response = http.getString();
        Serial.printf("[HTTP] Response: %s\n", response.c_str());

        bool access = (response.indexOf("\"access\":true") >= 0 || response.indexOf("\"access\": true") >= 0);

        if (access) {
            String name = "Pengguna";
            int idxName = response.indexOf("\"name\":\"");
            if (idxName >= 0) {
                int start = idxName + 8;
                int end = response.indexOf("\"", start);
                if (end > start) name = response.substring(start, end);
            }

            int gateOpenSeconds = 5;
            int idxSec = response.indexOf("\"gate_open_seconds\":");
            if (idxSec >= 0) {
                int start = idxSec + 20;
                gateOpenSeconds = response.substring(start).toInt();
                if (gateOpenSeconds <= 0) gateOpenSeconds = 5;
            }

            Serial.printf("[ACCESS GRANTED] %s - Portal terbuka %d detik\n",
                          name.c_str(), gateOpenSeconds);

            indicatorGrant(gateOpenSeconds);
        } else {
            Serial.println("[ACCESS DENIED] Akses ditolak oleh server.");
            indicatorDeny();
        }

    } else if (httpCode == 403) {
        // AKSES DITOLAK
        String response = http.getString();
        Serial.printf("[ACCESS DENIED] %s\n", response.c_str());
        indicatorDeny();

    } else if (httpCode == 401) {
        Serial.println("[ERROR] API key salah! Periksa DEVICE_KEY di sketch.");
        indicatorDeny();

    } else if (httpCode < 0) {
        Serial.printf("[ERROR] HTTP gagal: %s\n", http.errorToString(httpCode).c_str());
        indicatorDeny();

    } else {
        Serial.printf("[WARN] HTTP code: %d\n", httpCode);
        indicatorDeny();
    }

    http.end();
}

// ============================================================
// Buka portal: LED hijau + relay ON selama gateSeconds detik
// ============================================================
void indicatorGrant(int gateSeconds) {
    // Buzzer: 2 beep pendek (nada tinggi)
    tone(BUZZER_PIN, 2000, 150); delay(200); noTone(BUZZER_PIN);
    tone(BUZZER_PIN, 2500, 150); delay(200); noTone(BUZZER_PIN);

    // LED hijau ON + relay buka portal
    digitalWrite(LED_GREEN, HIGH);
    digitalWrite(RELAY_PIN, RELAY_ACTIVE_LEVEL); // BUKA PORTAL

    Serial.printf("[RELAY] Portal terbuka selama %d detik...\n", gateSeconds);
    delay(gateSeconds * 1000UL);

    // Tutup portal
    digitalWrite(RELAY_PIN, RELAY_IDLE_LEVEL);
    digitalWrite(LED_GREEN, LOW);
    Serial.println("[RELAY] Portal tertutup kembali.");
}

// ============================================================
// Tolak akses: LED merah + buzzer 3x pendek (nada rendah)
// ============================================================
void indicatorDeny() {
    for (int i = 0; i < 3; i++) {
        digitalWrite(LED_RED, HIGH);
        tone(BUZZER_PIN, 300, 250);
        delay(300);
        noTone(BUZZER_PIN);
        digitalWrite(LED_RED, LOW);
        delay(150);
    }
}
