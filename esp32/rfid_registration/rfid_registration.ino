/*
 * ============================================================
 * GUYUB RFID Registration Terminal — ESP32 + MFRC522
 * ============================================================
 * Fungsi : Membaca UID kartu KTP dan mengirimkan ke server GUYUB
 *          saat ada sesi registrasi aktif dari web app.
 *
 * Hardware yang dibutuhkan:
 *   - ESP32 (DevKit atau sejenisnya)
 *   - MFRC522 RFID Reader (komunikasi SPI)
 *   - LED hijau (opsional)
 *   - LED merah (opsional)
 *   - Buzzer aktif 5V (opsional)
 *   - OLED 0.96" SSD1306 128x64 (opsional, untuk info UID)
 *
 * Library Arduino yang perlu diinstall:
 *   - MFRC522 by GithubCommunity (via Library Manager)
 *   - ArduinoJson by Benoit Blanchon (v6.x)
 *   - WiFi (built-in ESP32)
 *   - HTTPClient (built-in ESP32)
 *
 * Wiring MFRC522 ke ESP32:
 *   MFRC522 | ESP32
 *   --------|-------
 *   SDA(SS) | GPIO 5
 *   SCK     | GPIO 18
 *   MOSI    | GPIO 23
 *   MISO    | GPIO 19
 *   IRQ     | -
 *   GND     | GND
 *   RST     | GPIO 22
 *   3.3V    | 3.3V
 * ============================================================
 */

#include <WiFi.h>
#include <HTTPClient.h>
#include <SPI.h>
#include <MFRC522.h>
#include <ArduinoJson.h>

// ============================================================
// KONFIGURASI — SESUAIKAN DENGAN LINGKUNGAN ANDA
// ============================================================
const char* WIFI_SSID     = "NAMA_WIFI_ANDA";
const char* WIFI_PASSWORD = "PASSWORD_WIFI_ANDA";

// URL server GUYUB (produksi atau lokal)
// Produksi : "https://guyub.unique-monsters.my.id/api/rfid/scan"
// Lokal    : "http://192.168.x.x:8000/api/rfid/scan"  (ganti IP laptop)
const char* SERVER_URL    = "https://guyub.unique-monsters.my.id/api/rfid/scan";

// API key — HARUS sama dengan RFID_DEVICE_KEY di file .env server
const char* DEVICE_KEY    = "GANTI_DENGAN_RFID_DEVICE_KEY_DI_ENV";

// ID perangkat ini (bebas, untuk log di server)
const char* DEVICE_ID     = "registration-01";
const char* DEVICE_TYPE   = "registration";

// Pin MFRC522
#define SS_PIN    5
#define RST_PIN   22

// Pin indikator (opsional, bisa diabaikan jika tidak dipasang)
#define LED_GREEN   32
#define LED_RED     33
#define BUZZER_PIN  25

// ============================================================
// Variabel global
// ============================================================
MFRC522 rfid(SS_PIN, RST_PIN);
String lastUID    = "";
unsigned long lastScanTime = 0;
const unsigned long SCAN_COOLDOWN_MS = 3000; // 3 detik debounce

// ============================================================
void setup() {
    Serial.begin(115200);
    delay(500);

    // Init pin indikator
    pinMode(LED_GREEN, OUTPUT);
    pinMode(LED_RED,   OUTPUT);
    pinMode(BUZZER_PIN, OUTPUT);
    digitalWrite(LED_GREEN, LOW);
    digitalWrite(LED_RED,   LOW);

    // Init SPI & RFID reader
    SPI.begin();
    rfid.PCD_Init();
    rfid.PCD_SetAntennaGain(rfid.RxGain_max);

    Serial.println("=================================");
    Serial.println("  GUYUB - RFID Registration ESP32");
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
        indicatorSuccess();
    } else {
        Serial.println("\nWiFi GAGAL! Pastikan SSID & password benar.");
        indicatorError();
    }

    Serial.println("\nSiap membaca kartu KTP...");
    Serial.println("Tempelkan kartu RFID/NFC ke reader.");
}

// ============================================================
void loop() {
    // Reconnect WiFi jika terputus
    if (WiFi.status() != WL_CONNECTED) {
        Serial.println("WiFi terputus, reconnecting...");
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

    // Debounce — hindari scan ganda
    if (uid == lastUID && (millis() - lastScanTime) < SCAN_COOLDOWN_MS) {
        rfid.PICC_HaltA();
        rfid.PCD_StopCrypto1();
        return;
    }

    lastUID      = uid;
    lastScanTime = millis();

    Serial.printf("\n[SCAN] UID terbaca: %s\n", uid.c_str());

    // Kirim ke server
    sendToServer(uid);

    rfid.PICC_HaltA();
    rfid.PCD_StopCrypto1();
}

// ============================================================
// Baca UID dari kartu dan format sebagai string HEX uppercase
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
// Kirim UID ke server GUYUB
// ============================================================
void sendToServer(String uid) {
    if (WiFi.status() != WL_CONNECTED) {
        Serial.println("[ERROR] WiFi tidak terhubung!");
        indicatorError();
        return;
    }

    HTTPClient http;
    http.begin(SERVER_URL);
    http.addHeader("Content-Type", "application/json");
    http.addHeader("X-Device-Key", DEVICE_KEY);
    http.setTimeout(8000); // 8 detik timeout

    // Build JSON payload
    StaticJsonDocument<256> doc;
    doc["uid"]         = uid;
    doc["device_id"]   = DEVICE_ID;
    doc["device_type"] = DEVICE_TYPE;

    String payload;
    serializeJson(doc, payload);

    Serial.printf("[HTTP] POST ke: %s\n", SERVER_URL);
    Serial.printf("[HTTP] Payload: %s\n", payload.c_str());

    int httpCode = http.POST(payload);

    Serial.printf("[HTTP] Response code: %d\n", httpCode);

    if (httpCode == 200) {
        String response = http.getString();
        Serial.printf("[HTTP] Response: %s\n", response.c_str());

        StaticJsonDocument<512> respDoc;
        DeserializationError err = deserializeJson(respDoc, response);

        if (!err) {
            const char* status = respDoc["status"] | "unknown";

            if (strcmp(status, "success") == 0) {
                Serial.println("[OK] UID berhasil dikirim ke sesi registrasi!");
                indicatorSuccess();
            } else if (strcmp(status, "no_session") == 0) {
                Serial.println("[INFO] Tidak ada sesi aktif. User belum klik 'Daftarkan KTP' di web.");
                indicatorWarning();
            } else {
                Serial.printf("[INFO] Status: %s\n", status);
                indicatorWarning();
            }
        }
    } else if (httpCode == 401) {
        Serial.println("[ERROR] API key salah! Periksa DEVICE_KEY di sketch.");
        indicatorError();
    } else if (httpCode < 0) {
        Serial.printf("[ERROR] HTTP gagal: %s\n", http.errorToString(httpCode).c_str());
        indicatorError();
    } else {
        Serial.printf("[WARN] HTTP code tidak terduga: %d\n", httpCode);
        indicatorWarning();
    }

    http.end();
}

// ============================================================
// Indikator LED & Buzzer
// ============================================================
void indicatorSuccess() {
    digitalWrite(LED_GREEN, HIGH);
    tone(BUZZER_PIN, 1500, 150);
    delay(150);
    noTone(BUZZER_PIN);
    delay(150);
    tone(BUZZER_PIN, 2000, 200);
    delay(200);
    noTone(BUZZER_PIN);
    delay(500);
    digitalWrite(LED_GREEN, LOW);
}

void indicatorError() {
    for (int i = 0; i < 3; i++) {
        digitalWrite(LED_RED, HIGH);
        tone(BUZZER_PIN, 400, 300);
        delay(300);
        noTone(BUZZER_PIN);
        digitalWrite(LED_RED, LOW);
        delay(200);
    }
}

void indicatorWarning() {
    digitalWrite(LED_RED, HIGH);
    tone(BUZZER_PIN, 800, 500);
    delay(500);
    noTone(BUZZER_PIN);
    digitalWrite(LED_RED, LOW);
}
