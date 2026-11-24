/*
 * ============================================================
 * GUYUB RFID Registration Terminal — ESP32 + MFRC522
 * ============================================================
 * Fitur Unggulan:
 *   - Auto AP / Web Portal: Jika hotspot tidak aktif, ESP32 otomatis
 *     memancarkan WiFi "GUYUB-RFID-SETUP" (192.168.4.1) untuk setting WiFi!
 *   - Memori NVS (Non-Volatile Storage): SSID & Password tersimpan di flash ESP32.
 *   - Membaca UID e-KTP / kartu RFID (13.56 MHz Mifare)
 *   - Mengirim status Heartbeat ONLINE ke server setiap 3 detik
 *   - Mengirim UID hasil scan ke web app saat user klik "Daftarkan KTP"
 *   - Kompatibel dengan Arduino Nano ESP32 & ESP32 DevKit Standar
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
#include <WebServer.h>
#include <DNSServer.h>
#include <Preferences.h>
#include <HTTPClient.h>
#include <SPI.h>
#include <MFRC522.h>

// ============================================================
// KONFIGURASI DEFAULT SERVER & API
// ============================================================
const char* DEFAULT_SSID     = "POCOX3NFC";
const char* DEFAULT_PASSWORD = "12345678";

// Endpoint Server GUYUB
const char* TAP_URL          = "https://guyub.unique-monsters.my.id/api/rfid/tap";
const char* HEARTBEAT_URL    = "https://guyub.unique-monsters.my.id/api/rfid/heartbeat";

// API Key Perangkat
const char* DEVICE_KEY       = "smartrw-rfid-device";
const char* DEVICE_ID        = "esp32-scanner-01";

// ============================================================
// KONFIGURASI ACCESS POINT (FALLBACK CAPTIVE PORTAL)
// ============================================================
const char* AP_SSID          = "GUYUB-RFID-SETUP";
const char* AP_PASS          = "12345678"; // Kosongkan "" jika ingin tanpa password

// ============================================================
// PIN DEFINITION (Nano ESP32 vs Standar DevKit)
// ============================================================
#if defined(ARDUINO_NANO_ESP32)
  #define SS_PIN   D10
  #define RST_PIN  D9
#else
  #define SS_PIN   5
  #define RST_PIN  22
#endif

// ============================================================
// OBJEK & VARIABEL GLOBAL
// ============================================================
MFRC522 rfid(SS_PIN, RST_PIN);
Preferences preferences;
WebServer server(80);
DNSServer dnsServer;

bool isAPMode = false;
String wifi_ssid = "";
String wifi_password = "";

String lastUID = "";
unsigned long lastScanTime = 0;
unsigned long lastHeartbeatTime = 0;
const unsigned long SCAN_COOLDOWN_MS = 2500;
const unsigned long HEARTBEAT_INTERVAL_MS = 3000;

// Prototypes
String readUID();
void sendScanToServer(String uid);
void sendHeartbeat();
void testRc522Connection();
void startAccessPoint();
void handleRoot();
void handleSave();

// ============================================================
// SETUP
// ============================================================
void setup() {
    Serial.begin(115200);
    delay(1000);

    Serial.println();
    Serial.println("==================================================");
    Serial.println("  GUYUB - ESP32 RFID Reader + Auto AP Config");
    Serial.println("==================================================");

    // 1. Inisialisasi SPI & Modul RFID RC522
    SPI.begin();
    rfid.PCD_Init();
    delay(50);
    rfid.PCD_SetAntennaGain(rfid.RxGain_max);
    testRc522Connection();

    // 2. Baca Konfigurasi WiFi dari Memori Flash ESP32
    preferences.begin("guyub-rfid", false);
    wifi_ssid     = preferences.getString("ssid", DEFAULT_SSID);
    wifi_password = preferences.getString("pass", DEFAULT_PASSWORD);

    // 3. Coba Sambungkan ke WiFi Hotspot (Station Mode)
    Serial.printf("\n[WIFI] Menghubungkan ke: %s ...\n", wifi_ssid.c_str());
    WiFi.mode(WIFI_STA);
    WiFi.begin(wifi_ssid.c_str(), wifi_password.c_str());

    int retries = 0;
    while (WiFi.status() != WL_CONNECTED && retries < 25) { // Tunggu ~12.5 detik
        delay(500);
        Serial.print(".");
        retries++;
    }

    // 4. Cek Hasil Koneksi
    if (WiFi.status() == WL_CONNECTED) {
        isAPMode = false;
        Serial.println("\n[WIFI] ✅ Berhasil terhubung!");
        Serial.printf("[WIFI] IP Address ESP32: %s\n", WiFi.localIP().toString().c_str());
        sendHeartbeat();
        Serial.println("\n[STATUS] Scanner siap membaca kartu e-KTP / RFID.");
        Serial.println(">>> Buka web: guyub.unique-monsters.my.id/rfid-ktp\n");
    } else {
        Serial.println("\n[WIFI] ❌ Hotspot tidak ditemukan / gagal terhubung!");
        startAccessPoint();
    }
}

// ============================================================
// LOOP
// ============================================================
void loop() {
    // A. JIKA SEDANG DALAM MODE ACCESS POINT (HOTSPOT ESP32)
    if (isAPMode) {
        dnsServer.processNextRequest();
        server.handleClient();
        return; // Tidak scan kartu selama user setting WiFi
    }

    // B. JIKA DALAM MODE NORMAL (STATION TERHUBUNG KE INTERNET)
    if (WiFi.status() != WL_CONNECTED) {
        Serial.println("[WIFI] Terputus! Mencoba menghubungkan kembali...");
        WiFi.reconnect();
        delay(4000);
        return;
    }

    // 1. Kirim Heartbeat ke Server setiap 3 detik
    if (millis() - lastHeartbeatTime >= HEARTBEAT_INTERVAL_MS) {
        lastHeartbeatTime = millis();
        sendHeartbeat();
    }

    // 2. Cek apakah ada kartu di sensor
    if (!rfid.PICC_IsNewCardPresent()) return;
    if (!rfid.PICC_ReadCardSerial())   return;

    // 3. Baca UID Kartu
    String uid = readUID();

    // Debounce / jeda anti-spam scan berulang
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

    // 4. Kirim UID ke Server
    sendScanToServer(uid);

    rfid.PICC_HaltA();
    rfid.PCD_StopCrypto1();
}

// ============================================================
// MODE ACCESS POINT (CAPTIVE PORTAL)
// ============================================================
void startAccessPoint() {
    isAPMode = true;
    WiFi.disconnect();
    WiFi.mode(WIFI_AP);
    WiFi.softAP(AP_SSID, AP_PASS);

    IPAddress apIP(192, 168, 4, 1);
    WiFi.softAPConfig(apIP, apIP, IPAddress(255, 255, 255, 0));

    // DNS Server untuk mengalihkan semua traffic ke 192.168.4.1 (Captive Portal)
    dnsServer.start(53, "*", apIP);

    server.on("/", handleRoot);
    server.on("/save", HTTP_POST, handleSave);
    server.onNotFound(handleRoot); // Otomatis tangkap jika HP buka halaman login
    server.begin();

    Serial.println("==================================================");
    Serial.println("🌐 [AP MODE] HOTSPOT SETUP DIAKTIFKAN!");
    Serial.printf ("   Nama WiFi (SSID): %s\n", AP_SSID);
    Serial.printf ("   Password WiFi   : %s\n", AP_PASS);
    Serial.println("   IP Konfigurasi  : http://192.168.4.1");
    Serial.println("   Sambungkan HP/Laptop ke WiFi ini untuk ubah WiFi.");
    Serial.println("==================================================\n");
}

// Tampilan Halaman Web Portal di HP/Laptop
void handleRoot() {
    // Scan WiFi sekitar
    int n = WiFi.scanNetworks();
    String wifiOptions = "";
    if (n == 0) {
        wifiOptions = "<option value=''>Tidak ada WiFi ditemukan</option>";
    } else {
        for (int i = 0; i < n; ++i) {
            String ssid = WiFi.SSID(i);
            int rssi = WiFi.RSSI(i);
            wifiOptions += "<option value='" + ssid + "'>" + ssid + " (" + String(rssi) + " dBm)</option>";
        }
    }

    String html = "<!DOCTYPE html><html><head><meta charset='UTF-8'>"
                  "<meta name='viewport' content='width=device-width, initial-scale=1'>"
                  "<title>GUYUB RFID - WiFi Setup</title>"
                  "<style>"
                  "body{font-family:system-ui,-apple-system,sans-serif;background:#0f172a;color:#f8fafc;padding:20px;margin:0;display:flex;justify-content:center;align-items:center;min-height:90vh;}"
                  ".card{background:#1e293b;border-radius:20px;padding:28px;max-width:380px;width:100%;box-shadow:0 20px 40px rgba(0,0,0,0.5);border:1px solid #334155;}"
                  "h2{margin:0 0 8px;color:#38bdf8;font-size:22px;text-align:center;font-weight:800;}"
                  "p{font-size:13px;color:#94a3b8;text-align:center;margin:0 0 20px;line-height:1.5;}"
                  "label{font-size:12px;font-weight:700;color:#cbd5e1;display:block;margin-top:14px;}"
                  "select,input{width:100%;padding:12px;margin-top:6px;border-radius:10px;border:1px solid #475569;background:#0f172a;color:#fff;box-sizing:border-box;font-size:14px;outline:none;}"
                  "select:focus,input:focus{border-color:#38bdf8;}"
                  "button{width:100%;background:linear-gradient(135deg,#0284c7,#2563eb);color:#fff;border:none;padding:14px;border-radius:12px;font-weight:800;font-size:15px;margin-top:24px;cursor:pointer;box-shadow:0 4px 15px rgba(37,99,235,0.4);}"
                  "button:hover{opacity:0.95;}"
                  ".footer{font-size:11px;color:#64748b;text-align:center;margin-top:18px;}"
                  "</style></head><body>"
                  "<div class='card'>"
                  "<h2>📡 GUYUB RFID</h2>"
                  "<p>Hotspot utama tidak terdeteksi. Silakan pilih WiFi sekitar untuk menyambungkan alat ke sistem.</p>"
                  "<form action='/save' method='POST'>"
                  "<label>Pilih Jaringan WiFi:</label>"
                  "<select name='ssid'>" + wifiOptions + "</select>"
                  "<label>Atau Ketik Nama WiFi Manual:</label>"
                  "<input type='text' name='manual_ssid' placeholder='SSID / Nama Hotspot'>"
                  "<label>Kata Sandi WiFi:</label>"
                  "<input type='password' name='password' placeholder='Password WiFi' required>"
                  "<button type='submit'>💾 Simpan & Hubungkan</button>"
                  "</form>"
                  "<div class='footer'>GUYUB Smart RT/RW IoT System &bull; 2026</div>"
                  "</div></body></html>";

    server.send(200, "text/html", html);
}

// Simpan WiFi baru ke Preferences & Restart ESP32
void handleSave() {
    String newSSID = server.arg("manual_ssid");
    if (newSSID.length() == 0) {
        newSSID = server.arg("ssid");
    }
    String newPass = server.arg("password");

    preferences.putString("ssid", newSSID);
    preferences.putString("pass", newPass);
    preferences.end();

    String html = "<!DOCTYPE html><html><head><meta charset='UTF-8'><meta name='viewport' content='width=device-width, initial-scale=1'>"
                  "<style>body{font-family:sans-serif;background:#0f172a;color:#fff;text-align:center;padding:50px 20px;}"
                  ".box{background:#1e293b;border-radius:20px;padding:30px;max-width:360px;margin:auto;border:1px solid #334155;}"
                  "h2{color:#4ade80;}</style></head><body>"
                  "<div class='box'>"
                  "<h2>✅ Berhasil Disimpan!</h2>"
                  "<p>ESP32 sedang merestart untuk terhubung ke WiFi: <b>" + newSSID + "</b></p>"
                  "<p style='color:#94a3b8;font-size:13px;'>Silakan sambungkan kembali HP Anda ke WiFi utama.</p>"
                  "</div></body></html>";

    server.send(200, "text/html", html);
    delay(2000);
    ESP.restart();
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
        Serial.println("❌ PERINGATAN: Modul RC522 TIDAK TERBACA OLEH ESP32!");
        Serial.println("   1. Pastikan VCC modul ke pin 3.3V (JANGAN KE 5V!)");
        Serial.println("   2. Pastikan pin SDA, SCK, MOSI, MISO, RST terpasang pas.");
        Serial.println("!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!\n");
    } else {
        Serial.println("✅ Modul RC522 BERHASIL TERHUBUNG DENGAN BAIK!\n");
    }
}

// ============================================================
// FORMAT UID KE HEX STRING
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
    http.addHeader("Accept", "application/json");
    http.addHeader("X-Device-Key", DEVICE_KEY);
    http.setTimeout(4000);

    String payload = "{\"device_id\":\"" + String(DEVICE_ID) + "\",\"ip\":\"" + WiFi.localIP().toString() + "\"}";
    int code = http.POST(payload);

    if (code == 200) {
        Serial.print("."); // Titik heartbeat tanda normal
    } else {
        Serial.printf("\n[HEARTBEAT] Respon HTTP: %d\n", code);
    }
    http.end();
}

// ============================================================
// KIRIM TAP UID KE SERVER
// ============================================================
void sendScanToServer(String uid) {
    if (WiFi.status() != WL_CONNECTED) {
        Serial.println("[ERROR] Tidak bisa kirim ke server: WiFi offline!");
        return;
    }

    HTTPClient http;
    http.begin(TAP_URL);
    http.addHeader("Content-Type", "application/json");
    http.addHeader("Accept", "application/json");
    http.addHeader("X-Device-Key", DEVICE_KEY);
    http.setTimeout(8000);

    String payload = "{\"uid\":\"" + uid + "\",\"device_id\":\"" + String(DEVICE_ID) + "\"}";

    Serial.printf("[HTTP] Mengirim UID %s ke server...\n", uid.c_str());
    int httpCode = http.POST(payload);

    Serial.printf("[HTTP] Status Response: %d\n", httpCode);

    if (httpCode == 200) {
        String response = http.getString();
        Serial.printf("[HTTP] Respons Server: %s\n", response.c_str());
        Serial.println("🎉 [SUKSES] Data tap kartu berhasil diproses server!\n");
    } else {
        Serial.printf("[HTTP ERROR] Kode: %d\n", httpCode);
    }

    http.end();
}
