/*
 * ============================================================
 * GUYUB RFID Registration Terminal — ESP32 + MFRC522
 * ============================================================
 * Fitur:
 *   - Membaca UID e-KTP / kartu RFID (13.56 MHz)
 *   - Mengirim status Heartbeat ONLINE ke server setiap 5 detik
 *   - Mengirim UID hasil scan ke web app saat user klik "Daftarkan KTP"
 *   - Diagnosis otomatis koneksi kabel modul RC522 di Serial Monitor
 *
 * Wiring RC522 ke Arduino Nano ESP32:
 *   RC522   | Pin Arduino Nano ESP32
 *   --------|-----------------------
 *   SDA(SS) | Pin D10 (atau GPIO 21)
 *   SCK     | Pin D13 (atau GPIO 48)
 *   MOSI    | Pin D11 (atau GPIO 38)
 *   MISO    | Pin D12 (atau GPIO 47)
 *   RST     | Pin D9  (atau GPIO 18)
 *   GND     | GND
 *   3.3V    | 3V3    ⚠️ (PERINGATAN: Jangan ke 5V / VBUS!)
 *
 * Wiring RC522 ke ESP32 Standar (DevKit 30/38 pin):
 *   RC522   | Pin ESP32 DevKit
 *   --------|-----------------
 *   SDA(SS) | GPIO 5
 *   SCK     | GPIO 18
 *   MOSI    | GPIO 23
 *   MISO    | GPIO 19
 *   RST     | GPIO 22
 *   GND     | GND
 *   3.3V    | 3.3V
 * ============================================================
 */

#include <WiFi.h>
#include <HTTPClient.h>
#include <SPI.h>
#include <MFRC522.h>

// ============================================================
// KONFIGURASI WIFI & SERVER
// ============================================================
const char* WIFI_SSID     = "POCOX3NFC";
const char* WIFI_PASSWORD = "12345678";

// Endpoint Server GUYUB
const char* SCAN_URL      = "https://guyub.unique-monsters.my.id/api/rfid/scan";
const char* HEARTBEAT_URL = "https://guyub.unique-monsters.my.id/api/rfid/heartbeat";

// API key — Harus cocok dengan RFID_DEVICE_KEY di server
const char* DEVICE_KEY    = "GuyubRfid2026_SecretKey_ChangeMe";
const char* DEVICE_ID     = "esp32-scanner-01";
const char* DEVICE_TYPE   = "registration";

// ============================================================
// KONFIGURASI PIN SPI RC522 (Otomatis deteksi Nano ESP32 vs Standar)
// ============================================================
#if defined(ARDUINO_NANO_ESP32)
  #define SS_PIN   D10
  #define RST_PIN  D9
#else
  #define SS_PIN   5
  #define RST_PIN  22
#endif

// Pin indikator LED opsional
#define LED_GREEN   32
#define LED_RED     33

// ============================================================
// Variabel Global
// ============================================================
MFRC522 rfid(SS_PIN, RST_PIN);
String lastUID = "";
unsigned long lastScanTime = 0;
unsigned long lastHeartbeatTime = 0;
const unsigned long SCAN_COOLDOWN_MS = 2500; // 2.5 detik jeda scan kartu yang sama
const unsigned long HEARTBEAT_INTERVAL_MS = 5000; // Kirim status tiap 5 detik

// Prototype
String readUID();
void sendScanToServer(String uid);
void sendHeartbeat();
void testRc522Connection();

// ============================================================
// SETUP
// ============================================================
void setup() {
    Serial.begin(115200);
    delay(1000);

    Serial.println();
    Serial.println("==================================================");
    Serial.println("  GUYUB - ESP32 RFID/NFC Registration Scanner");
    Serial.println("==================================================");

    // Inisialisasi SPI & Modul RFID
    SPI.begin();
    rfid.PCD_Init();
    delay(50);
    rfid.PCD_SetAntennaGain(rfid.RxGain_max); // Sensitivitas maksimal untuk e-KTP

    // Cek apakah RC522 terhubung dengan benar
    testRc522Connection();

    // Koneksi ke WiFi Hotspot
    Serial.printf("\n[WIFI] Menghubungkan ke: %s ...\n", WIFI_SSID);
    WiFi.begin(WIFI_SSID, WIFI_PASSWORD);

    int retries = 0;
    while (WiFi.status() != WL_CONNECTED && retries < 25) {
        delay(500);
        Serial.print(".");
        retries++;
    }

    if (WiFi.status() == WL_CONNECTED) {
        Serial.println("\n[WIFI] Berhasil terhubung!");
        Serial.printf("[WIFI] IP Address ESP32: %s\n", WiFi.localIP().toString().c_str());

        // Kirim heartbeat pertama kali saat baru menyala
        sendHeartbeat();
    } else {
        Serial.println("\n[WIFI] GAGAL! Pastikan Hotspot HP aktif dan password benar.");
    }

    Serial.println("\n[STATUS] Scanner siap membaca kartu.");
    Serial.println(">>> Buka web: guyub.unique-monsters.my.id/rfid-ktp");
    Serial.println(">>> Klik tombol 'Daftarkan KTP' di web, lalu tempelkan kartu e-KTP ke sensor.\n");
}

// ============================================================
// LOOP
// ============================================================
void loop() {
    // 1. Cek Koneksi WiFi
    if (WiFi.status() != WL_CONNECTED) {
        Serial.println("[WIFI] Terputus! Mencoba menghubungkan kembali...");
        WiFi.reconnect();
        delay(4000);
        return;
    }

    // 2. Heartbeat berkala setiap 5 detik ke server (agar web tahu ESP32 ONLINE)
    if (millis() - lastHeartbeatTime >= HEARTBEAT_INTERVAL_MS) {
        lastHeartbeatTime = millis();
        sendHeartbeat();
    }

    // 3. Cek apakah ada kartu e-KTP / RFID di reader
    if (!rfid.PICC_IsNewCardPresent()) {
        return;
    }
    if (!rfid.PICC_ReadCardSerial()) {
        return;
    }

    // 4. Baca UID Kartu
    String uid = readUID();

    // Jeda anti-spam jika kartu yang sama ditempel terus
    if (uid == lastUID && (millis() - lastScanTime) < SCAN_COOLDOWN_MS) {
        rfid.PICC_HaltA();
        rfid.PCD_StopCrypto1();
        return;
    }

    lastUID = uid;
    lastScanTime = millis();

    Serial.printf("\n=====================================\n");
    Serial.printf("[SCAN] KARTU TERBACA! UID: %s\n", uid.c_str());
    Serial.printf("=====================================\n");

    // 5. Kirim UID ke server GUYUB
    sendScanToServer(uid);

    rfid.PICC_HaltA();
    rfid.PCD_StopCrypto1();
}

// ============================================================
// DIAGNOSIS KONEKSI HARDWARE RC522
// ============================================================
void testRc522Connection() {
    byte v = rfid.PCD_ReadRegister(rfid.VersionReg);
    Serial.print("[RC522] Versi Chip Firmware: 0x");
    Serial.println(v, HEX);

    if (v == 0x00 || v == 0xFF) {
        Serial.println();
        Serial.println("!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!");
        Serial.println("❌ PERINGATAN KERAS: Modul RC522 TIDAK TERBACA OLEH ESP32!");
        Serial.println("   Penyebab kartu tidak terbaca adalah sambungan kabel:");
        Serial.println("   1. Pastikan VCC modul ke pin 3.3V (JANGAN KE 5V!)");
        Serial.println("   2. Pastikan SDA terpasang ke pin D10 (Nano ESP32)");
        Serial.println("   3. Pastikan SCK ke D13, MOSI ke D11, MISO ke D12");
        Serial.println("   4. Pastikan RST ke D9 dan GND ke GND");
        Serial.println("   5. Periksa jika ada kabel jumper yang kendor!");
        Serial.println("!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!\n");
    } else {
        Serial.println("✅ Modul RC522 BERHASIL TERHUBUNG DENGAN BAIK!\n");
    }
}

// ============================================================
// FORMAT UID DARI BYTE KE STRING HEX
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
// KIRIM HEARTBEAT KE SERVER
// ============================================================
void sendHeartbeat() {
    if (WiFi.status() != WL_CONNECTED) return;

    HTTPClient http;
    http.begin(HEARTBEAT_URL);
    http.addHeader("Content-Type", "application/json");
    http.addHeader("X-Device-Key", DEVICE_KEY);
    http.setTimeout(4000);

    String payload = "{\"device_id\":\"" + String(DEVICE_ID) + "\",\"ip\":\"" + WiFi.localIP().toString() + "\"}";
    int code = http.POST(payload);

    if (code == 200) {
        Serial.print("."); // Titik heartbeat tanda normal
    } else {
        Serial.printf("\n[HEARTBEAT] Gagal (HTTP %d)\n", code);
    }
    http.end();
}

// ============================================================
// KIRIM HASIL SCAN UID KE SERVER
// ============================================================
void sendScanToServer(String uid) {
    if (WiFi.status() != WL_CONNECTED) {
        Serial.println("[ERROR] Tidak bisa kirim ke server: WiFi offline!");
        return;
    }

    HTTPClient http;
    http.begin(SCAN_URL);
    http.addHeader("Content-Type", "application/json");
    http.addHeader("X-Device-Key", DEVICE_KEY);
    http.setTimeout(8000);

    String payload = "{\"uid\":\"" + uid + "\",\"device_id\":\"" + String(DEVICE_ID) + "\",\"device_type\":\"" + String(DEVICE_TYPE) + "\"}";

    Serial.printf("[HTTP] Mengirim UID %s ke server...\n", uid.c_str());
    int httpCode = http.POST(payload);

    Serial.printf("[HTTP] Status Response: %d\n", httpCode);

    if (httpCode == 200) {
        String response = http.getString();
        Serial.printf("[HTTP] Respons Server: %s\n", response.c_str());

        if (response.indexOf("success") >= 0) {
            Serial.println("\n🎉 [SUKSES BESAR] UID berhasil terkirim ke sesi registrasi web!");
            Serial.println("🎉 Silakan klik tombol 'Ya, Daftarkan Sekarang' di browser Anda!\n");
        } else if (response.indexOf("no_session") >= 0) {
            Serial.println("\n⚠️ [BELUM KLIK DAFTAR DI WEB]");
            Serial.println("⚠️ Kartu terbaca, TETAPI Anda belum menekan tombol 'Daftarkan KTP' di web.");
            Serial.println("⚠️ Silakan buka web, klik 'Daftarkan KTP' dulu, lalu tempelkan kartu lagi.\n");
        }
    } else {
        Serial.printf("[HTTP ERROR] Kode: %d\n", httpCode);
    }

    http.end();
}
