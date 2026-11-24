<?php

return [
    /*
    |--------------------------------------------------------------------------
    | RFID Device API Key
    |--------------------------------------------------------------------------
    | Key rahasia yang diprogram ke ESP32. Ganti di .env dengan nilai acak
    | yang panjang. Semua perangkat IoT wajib kirim key ini di setiap request.
    */
    'device_key' => env('RFID_DEVICE_KEY', 'smartrw-rfid-device'),

    /*
    |--------------------------------------------------------------------------
    | Session Registrasi TTL (menit)
    |--------------------------------------------------------------------------
    | Berapa menit sesi registrasi KTP aktif setelah user klik "Daftarkan KTP".
    | Setelah waktu ini habis, user harus klik lagi.
    */
    'session_ttl_minutes' => (int) env('RFID_SESSION_TTL', 5),

    /*
    |--------------------------------------------------------------------------
    | Durasi Portal Terbuka (detik)
    |--------------------------------------------------------------------------
    | Info untuk ESP32: berapa detik relay portal terbuka setelah akses diberikan.
    | Nilai ini dikirim dalam response gate-check ke ESP32.
    */
    'gate_open_seconds' => (int) env('RFID_GATE_OPEN_SECONDS', 5),
];
