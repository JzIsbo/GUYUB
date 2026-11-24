/*
 * ============================================================
 * GUYUB RFID Gate Controller — ESP32 + MFRC522 + Relay Portal
 * ============================================================
 * Fitur:
 *   - Auto AP / Web Portal: Jika WiFi tidak terhubung, otomatis memancarkan
 *     WiFi "GUYUB-GATE-SETUP" (192.168.4.1) untuk konfigurasi mudah lewat HP.
 *   - Memori NVS: SSID & Password tersimpan aman di flash ESP32.
 *   - Scan e-KTP / RFID card (13.56 MHz Mifare)
 *   - Kirim UID ke server GUYUB (/api/rfid/gate-check)
 *   - Jika authorized (200 OK): Aktifkan relay (buka portal) X detik, LED hijau, buzzer beep
 *   - Jika unauthorized (403): LED merah 2 detik, buzzer 2x beep
 *   - Support Arduino Nano ESP32 & ESP32 DevKit 30/38 pin
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
// KONFIGURASI DEFAULT SERVER & WIFI
// ============================================================
const char* DEFAULT_SSID     = "POCOX3NFC";
const char* DEFAULT_PASSWORD = "12345678";

// URL endpoint gate-check di server GUYUB
const char* SERVER_URL       = "https://guyub.unique-monsters.my.id/api/rfid/gate-check";

// API key perangkat
const char* DEVICE_KEY       = "smartrw-rfid-device";
const char* DEVICE_ID        = "gate-01";

// Access Point Config
const char* AP_SSID          = "GUYUB-GATE-SETUP";
const char* AP_PASS          = "12345678";

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

// Relay aktif LOW
#define RELAY_ACTIVE_LEVEL  LOW
#define RELAY_IDLE_LEVEL    HIGH

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
const unsigned long SCAN_COOLDOWN_MS = 4000;

// Prototypes
String readUID();
void checkAccess(String uid);
void openGate(int seconds);
void denyAccess();
void beepSuccess();
void beepDenied();
void startAccessPoint();
void handleRoot();
void handleSave();

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

    // Baca NVS
    preferences.begin("guyub-gate", false);
    wifi_ssid     = preferences.getString("ssid", DEFAULT_SSID);
    wifi_password = preferences.getString("pass", DEFAULT_PASSWORD);

    // Koneksi WiFi
    Serial.printf("Connecting to WiFi: %s ...\n", wifi_ssid.c_str());
    WiFi.mode(WIFI_STA);
    WiFi.begin(wifi_ssid.c_str(), wifi_password.c_str());

    int retries = 0;
    while (WiFi.status() != WL_CONNECTED && retries < 25) {
        delay(500);
        Serial.print(".");
        retries++;
    }

    if (WiFi.status() == WL_CONNECTED) {
        isAPMode = false;
        Serial.printf("\nWiFi OK! IP: %s\n", WiFi.localIP().toString().c_str());
        // Blink hijau 3x
        for (int i = 0; i < 3; i++) {
            digitalWrite(LED_GREEN, HIGH); delay(150);
            digitalWrite(LED_GREEN, LOW);  delay(150);
        }
    } else {
        Serial.println("\nWiFi GAGAL! Mengaktifkan Hotspot Setup...");
        startAccessPoint();
    }

    Serial.println("Siap membaca kartu e-KTP / RFID.");
}

// ============================================================
void loop() {
    if (isAPMode) {
        dnsServer.processNextRequest();
        server.handleClient();
        return;
    }

    // Cek koneksi WiFi
    if (WiFi.status() != WL_CONNECTED) {
        digitalWrite(LED_RED, HIGH);
        Serial.println("WiFi terputus! Reconnecting...");
        WiFi.reconnect();
        delay(4000);
        digitalWrite(LED_RED, LOW);
        return;
    }

    // Deteksi kartu
    if (!rfid.PICC_IsNewCardPresent()) return;
    if (!rfid.PICC_ReadCardSerial())   return;

    String uid = readUID();

    // Debounce
    if (uid == lastUID && (millis() - lastScanTime) < SCAN_COOLDOWN_MS) {
        rfid.PICC_HaltA();
        rfid.PCD_StopCrypto1();
        return;
    }

    lastUID = uid;
    lastScanTime = millis();

    Serial.println("---------------------------------");
    Serial.printf("KARTU TERDETEKSI: %s\n", uid.c_str());

    checkAccess(uid);

    rfid.PICC_HaltA();
    rfid.PCD_StopCrypto1();
}

// ============================================================
void startAccessPoint() {
    isAPMode = true;
    WiFi.disconnect();
    WiFi.mode(WIFI_AP);
    WiFi.softAP(AP_SSID, AP_PASS);

    IPAddress apIP(192, 168, 4, 1);
    WiFi.softAPConfig(apIP, apIP, IPAddress(255, 255, 255, 0));
    dnsServer.start(53, "*", apIP);

    server.on("/", handleRoot);
    server.on("/save", HTTP_POST, handleSave);
    server.onNotFound(handleRoot);
    server.begin();

    Serial.println("==================================================");
    Serial.println("🌐 [AP MODE] HOTSPOT CONFIGURASI AKTIF!");
    Serial.printf ("   SSID     : %s\n", AP_SSID);
    Serial.printf ("   Password : %s\n", AP_PASS);
    Serial.println("   IP Web   : http://192.168.4.1");
    Serial.println("==================================================");
}

void handleRoot() {
    int n = WiFi.scanNetworks();
    String wifiOptions = "";
    if (n == 0) {
        wifiOptions = "<option value=''>Tidak ada WiFi terdeteksi</option>";
    } else {
        for (int i = 0; i < n; ++i) {
            String ssid = WiFi.SSID(i);
            wifiOptions += "<option value='" + ssid + "'>" + ssid + "</option>";
        }
    }

    String html = "<!DOCTYPE html><html><head><meta charset='UTF-8'><meta name='viewport' content='width=device-width, initial-scale=1'>"
                  "<title>GUYUB Gate - WiFi Setup</title>"
                  "<style>"
                  "body{font-family:sans-serif;background:#0f172a;color:#f8fafc;padding:20px;display:flex;justify-content:center;align-items:center;min-height:90vh;margin:0;}"
                  ".card{background:#1e293b;border-radius:20px;padding:26px;max-width:380px;width:100%;border:1px solid #334155;}"
                  "h2{margin:0 0 10px;color:#38bdf8;text-align:center;}"
                  "p{font-size:13px;color:#94a3b8;text-align:center;margin-bottom:20px;}"
                  "label{font-size:12px;font-weight:bold;display:block;margin-top:14px;color:#cbd5e1;}"
                  "select,input{width:100%;padding:12px;margin-top:6px;border-radius:10px;border:1px solid #475569;background:#0f172a;color:#fff;box-sizing:border-box;font-size:14px;}"
                  "button{width:100%;background:#2563eb;color:#fff;border:none;padding:14px;border-radius:12px;font-weight:bold;margin-top:22px;cursor:pointer;font-size:15px;}"
                  "</style></head><body><div class='card'>"
                  "<h2>🚪 GUYUB Gate Setup</h2>"
                  "<p>Pilih koneksi WiFi untuk mengaktifkan palang pintu portal.</p>"
                  "<form action='/save' method='POST'>"
                  "<label>Pilih WiFi:</label><select name='ssid'>" + wifiOptions + "</select>"
                  "<label>Atau Nama WiFi Manual:</label><input type='text' name='manual_ssid' placeholder='SSID'>"
                  "<label>Password WiFi:</label><input type='password' name='password' placeholder='Password' required>"
                  "<button type='submit'>Simpan & Hubungkan</button>"
                  "</form></div></body></html>";
    server.send(200, "text/html", html);
}

void handleSave() {
    String newSSID = server.arg("manual_ssid");
    if (newSSID.length() == 0) newSSID = server.arg("ssid");
    String newPass = server.arg("password");

    preferences.putString("ssid", newSSID);
    preferences.putString("pass", newPass);
    preferences.end();

    String html = "<!DOCTYPE html><html><body style='font-family:sans-serif;background:#0f172a;color:#fff;text-align:center;padding:50px;'>"
                  "<h2>✅ Berhasil Disimpan!</h2><p>ESP32 Gate Controller sedang restart...</p></body></html>";
    server.send(200, "text/html", html);
    delay(2000);
    ESP.restart();
}

// ============================================================
void checkAccess(String uid) {
    HTTPClient http;
    http.begin(SERVER_URL);
    http.addHeader("Content-Type", "application/json");
    http.addHeader("Accept", "application/json");
    http.addHeader("X-Device-Key", DEVICE_KEY);
    http.setTimeout(6000);

    String payload = "{\"uid\":\"" + uid + "\",\"device_id\":\"" + String(DEVICE_ID) + "\"}";
    int httpCode = http.POST(payload);

    Serial.printf("Server Response: %d\n", httpCode);

    if (httpCode == 200) {
        String res = http.getString();
        int gateOpenSec = 5;
        int idx = res.indexOf("\"gate_open_seconds\":");
        if (idx >= 0) {
            gateOpenSec = res.substring(idx + 20, idx + 23).toInt();
            if (gateOpenSec <= 0) gateOpenSec = 5;
        }

        Serial.println(">>> AKSES DITERIMA — Buka portal!");
        beepSuccess();
        openGate(gateOpenSec);
    } else {
        Serial.println(">>> AKSES DITOLAK");
        denyAccess();
    }
    http.end();
}

void openGate(int seconds) {
    digitalWrite(LED_GREEN, HIGH);
    digitalWrite(RELAY_PIN, RELAY_ACTIVE_LEVEL);
    delay(seconds * 1000);
    digitalWrite(RELAY_PIN, RELAY_IDLE_LEVEL);
    digitalWrite(LED_GREEN, LOW);
}

void denyAccess() {
    digitalWrite(LED_RED, HIGH);
    beepDenied();
    delay(1500);
    digitalWrite(LED_RED, LOW);
}

void beepSuccess() {
    tone(BUZZER_PIN, 2000, 150);
}

void beepDenied() {
    tone(BUZZER_PIN, 800, 200);
    delay(250);
    tone(BUZZER_PIN, 800, 200);
}

String readUID() {
    String uid = "";
    for (byte i = 0; i < rfid.uid.size; i++) {
        if (rfid.uid.uidByte[i] < 0x10) uid += "0";
        uid += String(rfid.uid.uidByte[i], HEX);
    }
    uid.toUpperCase();
    return uid;
}
