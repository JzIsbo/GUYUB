# 🏠 GUYUB — Gerbang Urusan & Layanan Warga Bersama

[![Production Live](https://img.shields.io/badge/Production%20Live-guyub.unique--monsters.my.id-0070f3?style=flat-square&logo=google-chrome&logoColor=white)](https://guyub.unique-monsters.my.id)
[![Laravel Version](https://img.shields.io/badge/Laravel-11.x%20%2F%2012.x-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-8.3-777BB4?style=flat-square&logo=php&logoColor=white)](https://php.net)
[![TailwindCSS](https://img.shields.io/badge/TailwindCSS-3.4-38B2AC?style=flat-square&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![MySQL Version](https://img.shields.io/badge/MySQL-8.0%20%7C%208.4-4479A1?style=flat-square&logo=mysql&logoColor=white)](https://mysql.com)
[![IoT ESP32](https://img.shields.io/badge/IoT-ESP32%20RFID%20Portal-E7352C?style=flat-square&logo=espressif&logoColor=white)](#-integrasi-iot-portal-gerbang-esp32--rfid-e-ktp)
[![Architecture](https://img.shields.io/badge/Architecture-Hybrid%20SPA%20%2F%20Vanilla%20JS-2563EB?style=flat-square)](#)

**GUYUB** (*Gerbang Urusan dan Layanan Warga Bersama*) adalah sistem tata kelola lingkungan perumahan RT/RW pintar (Smart Neighborhood Platform) berbasis web modern dan Internet of Things (IoT). Sistem ini menggabungkan backend andal **Laravel** dengan arsitektur antarmuka **Single Page Application (SPA) berbasis Vanilla JS** yang cepat, responsif, dan terintegrasi langsung dengan perangkat keras **ESP32 RFID/NFC Portal Gate**.

Aplikasi ini mendigitalisasi seluruh aspek kehidupan warga lingkungan: kependudukan & KK, pembukuan kas & iuran bulanan, peminjaman aset fasum, koperasi sembako & pinjaman modal, posyandu, bank sampah, kerja bakti, rukun kematian (rukem), surat pengantar online, hingga akses portal otomatis perumahan menggunakan kartu e-KTP.

🌐 **Demo Live Production**: [https://guyub.unique-monsters.my.id](https://guyub.unique-monsters.my.id)

---

## 🛠️ Spesifikasi Teknologi (Tech Stack)

### ⚙️ 1. Backend & Server
* **Framework**: [Laravel 11.x / 12.x](https://laravel.com) (PHP 8.3)
* **Database**: MySQL 8.0 / 8.4 (Percona Server)
* **Payment Gateway**: Midtrans SDK (`midtrans/midtrans-php`) untuk QRIS dinamis & Virtual Account
* **Ekspor Dokumen**: `maatwebsite/excel` & format cetak laporan keuangan/kependudukan PDF
* **Keamanan & Otorisasi**: Role-Based Access Control (RBAC) 8 tingkat (Super Admin, RW, Sekretaris RW, Bendahara RW, RT, Sekretaris RT, Bendahara RT, Warga)
* **REST API IoT**: Endpoint verifikasi perangkat portal terproteksi header `X-Device-Key`

### 🎨 2. Frontend & Arsitektur SPA
* **SPA Engine**: Custom Vanilla JS Router — Navigasi instan via DOM-swapping tanpa reload halaman, history API, dan background prefetching
* **Desain UI**: [Tailwind CSS v3.4](https://tailwindcss.com) — Antarmuka modern, responsif penuh di mobile/tablet/desktop, dan dukungan tema gelap (*dark mode*)
* **Micro-Reactivity**: [Alpine.js](https://alpinejs.dev) — Animasi modal interaktif, dropdown, tab switcher, dan drawer panel
* **Visualisasi Statistik**: [Chart.js](https://www.chartjs.org) — Grafik arus kas bulanan (pemasukan vs pengeluaran) dan doughnut chart demografi warga
* **Tipografi & Ikon**: Google Fonts (*Plus Jakarta Sans* & *Poppins*), [FontAwesome 6 Pro/Free](https://fontawesome.com)

### 📡 3. Perangkat Keras IoT (Gate Access Control)
* **Mikrokontroler**: ESP32 Dev Module (Wroom-32) / Arduino Nano ESP32
* **Sensor RFID/NFC**: RC522 (13.56 MHz SPI) — Kompatibel membaca kartu e-KTP, kartu Mifare 1K, dan gantungan kunci RFID
* **Aktuator**: Relay 5V (Active LOW) terhubung ke motor portal gerbang otomatis
* **Komunikasi**: HTTP REST Client via WiFi dengan SSL/TLS langsung ke server GUYUB

---

## 🗺️ Fitur Utama Ekosistem GUYUB (38 Halaman / Modul)

### 👥 1. Administrasi Kependudukan & Akun
* **Data Warga**: Pendataan NIK, No. KK, Usia, Agama, Jenis Kelamin (Gender), Status Perkawinan, Alamat Blok/Nomor Rumah, dan Status Kependudukan.
* **Manajemen Kartu Keluarga (KK)**: Pengelompokan warga per Kepala Keluarga, penambahan anggota keluarga, dan cetak daftar KK.
* **Approval Registrasi Warga**: Validasi dan persetujuan pendaftaran akun warga mandiri oleh Ketua RT sebelum akun dapat login.
* **Struktur Pengurus RT/RW**: Daftar susunan pengurus lingkungan beserta masa jabatan dan kontak resmi.

### 💳 2. Pengelolaan Keuangan, Kas, & Iuran Lingkungan
* **Buku Kas & Transaksi**: Pencatatan kas masuk dan kas keluar dengan pembagian kategori dinamis (operasional, perbaikan fasum, santunan, dll.).
* **Master Iuran & Tagihan Otomatis**: Konfigurasi iuran wajib/sukarela (Keamanan/Satpam, Sampah, Kas Rutin RT, Dana Sosial) dan generasi tagihan bulanan ke warga.
* **Pembayaran Online (QRIS & Midtrans)**: Pembayaran iuran via scan QRIS atau transfer rekening kas RT dengan verifikasi bukti bayar.
* **Laporan Arus Kas & Ekspor**: Ringkasan saldo realtime, grafik transaksi bulanan, dan ekspor data laporan kas ke Excel / PDF.

### 🚪 3. Akses Portal e-KTP / RFID (Smart Gate IoT)
* **Pendaftaran Kartu e-KTP Mandiri**: Warga mendaftarkan kartu e-KTP mereka secara interaktif melalui layar browser yang terhubung ke scanner ESP32.
* **Pencegahan Duplikasi Kartu**: Sistem memvalidasi UID kartu secara realtime; kartu yang sudah terdaftar di akun lain otomatis ditolak dengan notifikasi peringatan.
* **Log Aktivitas Portal (Audit Trail)**: Pencatatan otomatis setiap kali portal dibuka (nama warga, waktu akses, UID kartu, dan status gerbang).
* **Indikator Status Scanner**: Deteksi heartbeat alat ESP32 secara realtime (Online / Offline).

### 📦 4. Inventaris Fasilitas Umum & Peminjaman Aset
* **Pencatatan Aset RT**: Inventaris tenda lipat, kursi rapat Chitose, sound system wireless, mesin potong rumput, HT keamanan, dan unit portal otomatis.
* **Pengajuan Pinjam Aset Warga**: Warga dapat mengajukan pinjaman tenda/kursi untuk hajatan keluarga secara online.
* **Pemeriksaan Pengembalian Fisik**: Pengurus memeriksa kondisi fisik aset saat dikembalikan, mencatat riwayat peminjaman, serta mengenakan denda jika terjadi kerusakan.

### 🛒 5. Koperasi Warga Guyub
* **Katalog Sembako Murah**: Pembelian beras premium, minyak goreng, gula pasir, telur, dan sembako secara online via kas warga/QRIS.
* **Simpan Pinjam Mandiri**: Pengelolaan Simpanan Pokok & Simpanan Wajib anggota serta pengajuan pinjaman modal usaha dengan kalkulasi tenor angsuran bulanan.
* **Permodalan UMKM**: Pengajuan bantuan permodalan bergulir bagi pengusaha mikro lingkungan.
* **Pembukuan Keuangan Koperasi**: Laporan kas terpisah khusus transaksi unit usaha koperasi RT.

### 🏥 6. Layanan Sosial, Lingkungan, & Kesehatan
* **Posyandu Melati**: Jadwal penimbangan balita, imunisasi polio, vitamin A, serta pemeriksaan kesehatan lansia (tensi, cek gula darah, kolesterol).
* **Bank Sampah Berdaya**: Penimbangan sampah terpilah (kardus, botol plastik PET, minyak jelantah) dan konversi rupiah langsung ke saldo warga.
* **Kerja Bakti & Gotong Royong**: Kalender kegiatan bersih lingkungan, pembersihan got saluran air, dan pengecatan fasum.
* **Rukun Kematian (Rukem)**: Pendataan duka cita warga dan pencatatan penyaluran uang santunan duka cita secara transparan.

### 📝 7. Pelayanan Surat & Keamanan Lingkungan
* **Surat Pengantar Digital**: Pembuatan surat pengantar domisili, permohonan KTP/KK, dan surat keterangan lain dengan approval Ketua RT 1-klik.
* **Kotak Aspirasi Warga**: Saluran masukan, saran fasilitas umum, dan aduan lingkungan langsung ke pengurus RT.
* **Jadwal Ronda Malam & Pos Kamling**: Pembagian giliran ronda malam warga dan pelaporan insiden keamanan.
* **Peraturan & SK RT**: Dokumen resmi tata tertib lingkungan dan surat keputusan pengurus yang dapat diunduh warga.

---

## 👥 Matriks Hak Akses Peran (Role-Based Access Control)

Sistem GUYUB menerapkan kontrol akses berbasis peran (RBAC) granular untuk 8 tingkat hak akses pengguna:

| Modul / Menu Sistem | 👑 Super Admin | 🏢 Ketua RW | 📜 Sekr. RW | 💵 Bend. RW | 🏡 Ketua RT | 📝 Sekr. RT | 💰 Bend. RT | 👥 Warga |
| :--- | :---: | :---: | :---: | :---: | :---: | :---: | :---: | :---: |
| **Dashboard Lingkungan** | ✅ Penuh | ✅ Penuh | ✅ Penuh | ✅ Penuh | ✅ Penuh | ✅ Penuh | ✅ Penuh | 👁️ Ringkasan |
| **Portal Akses (Smart Gate)** | ✅ Penuh | ✅ Monitoring | ✅ Monitoring | ✅ Monitoring | ✅ Kelola | ✅ Monitoring | ✅ Monitoring | 🚪 Akses Gate |
| **Kartu e-KTP (RFID)** | ✅ Penuh | 👁️ Monitoring | 👁️ Monitoring | 👁️ Monitoring | ✅ Kelola | 👁️ Monitoring | 👁️ Monitoring | 💳 KTP Sendiri |
| **Buku Kas & Transaksi** | ✅ Penuh | ❌ Dibatasi | ❌ Dibatasi | ✅ Kelola RW | ✅ Kelola RT | ❌ Dibatasi | ✅ Kelola RT | ❌ Dibatasi |
| **Tagihan & Iuran Warga** | ✅ Penuh | 👁️ Rekap | 👁️ Rekap | ✅ Kelola RW | ✅ Kelola RT | 👁️ Laporan | ✅ Kelola RT | 💳 Tagihan Saya |
| **Pembayaran Online & QRIS** | ✅ Penuh | 👁️ Monitoring | ❌ Dibatasi | ✅ Kelola RW | ✅ Kelola RT | ❌ Dibatasi | ✅ Kelola RT | 💳 Bayar Mandiri |
| **Laporan Kas & Keuangan** | ✅ Penuh | ✅ Semua Lap. | 👁️ Laporan | ✅ Semua Lap. | ✅ Semua Lap. | 👁️ Laporan | ✅ Semua Lap. | 👁️ Lap. Koperasi |
| **Persetujuan Akun Warga** | ✅ Kelola | ✅ Monitoring | ✅ Monitoring | ❌ Dibatasi | ✅ Setujui/Tolak | ✅ Setujui/Tolak | ❌ Dibatasi | ❌ Dibatasi |
| **Data Warga & Keluarga** | ✅ Kelola | ✅ Monitoring | ✅ Monitoring | 👁️ Keluarga | ✅ Kelola RT | ✅ Kelola RT | ✅ Kelola RT | 👨‍👩‍👧‍👦 KK Saya |
| **Aset / Inventaris RT** | ✅ Kelola | 👁️ Monitoring | ❌ Dibatasi | ❌ Dibatasi | ✅ Kelola | ✅ Kelola | ❌ Dibatasi | 📝 Ajukan Pinjam |
| **Koperasi & Simpan Pinjam**| ✅ Kelola | ✅ Monitoring | ✅ Monitoring | ✅ Kelola Kas | ✅ Kelola | ✅ Catat | ✅ Kelola | 🛒 Belanja/Tabung |
| **Bank Sampah & Posyandu** | ✅ Kelola | ✅ Monitoring | ✅ Monitoring | ❌ Dibatasi | ✅ Kelola | ✅ Kelola | ✅ Kelola | 👁️ Partisipasi |
| **Surat Pengantar Online** | ✅ Kelola | ❌ Dibatasi | ✅ Terbitkan | ❌ Dibatasi | ✅ Setujui | ✅ Terbitkan | ✅ Lihat | 📝 Buat Surat |
| **Pengumuman & Aspirasi** | ✅ Kelola | ✅ Buat/Pantau | ✅ Buat/Pantau | ❌ Dibatasi | ✅ Buat/Pantau | ✅ Buat/Pantau | ✅ Buat/Pantau | 💬 Kirim Masukan |
| **Keamanan, Ronda & Insiden**| ✅ Kelola | ✅ Monitoring | ✅ Monitoring | ❌ Dibatasi | ✅ Kelola | ✅ Kelola | ✅ Kelola | 🛡️ Jadwal Ronda |
| **Peraturan SK & Kerja Bakti**| ✅ Kelola | ✅ Kelola | ✅ Kelola | ❌ Dibatasi | ✅ Kelola | ✅ Kelola | ✅ Kelola | 📜 Unduh SK & Hadir |
| **Pengaturan & Profil Akun** | ✅ Kelola | ⚙️ Profil | ⚙️ Profil | ⚙️ Profil | ⚙️ Profil/RT | ⚙️ Profil | ⚙️ Profil | ⚙️ Profil Mandiri |
| **Backup & Restore Database**| 🛠️ Penuh | ❌ Dibatasi | ❌ Dibatasi | ❌ Dibatasi | ❌ Dibatasi | ❌ Dibatasi | ❌ Dibatasi | ❌ Dibatasi |

---

## 📡 Integrasi IoT Portal Gerbang (ESP32 + RFID e-KTP)

Sistem GUYUB dilengkapi integrasi perangkat keras portal perumahan otomatis berbasis IoT. Source code firmware ESP32 lengkap dan siap flash tersedia di direktori:
👉 [`esp32/smart_rw_rfid_reader/smart_rw_rfid_reader.ino`](esp32/smart_rw_rfid_reader/smart_rw_rfid_reader.ino)

### 🔌 Panduan Catu Daya Adaptor Eksternal & Modul HW-131
Untuk memastikan kinerja stabil tanpa terjadi *brownout* (ESP32 restart saat motor servo bergerak menarik arus beban tinggi), gunakan **Breadboard Power Supply Module HW-131** dengan adaptor eksternal:

```text
  [ Adaptor DC 9V/12V (1-2A) ]
             │
             ▼ (Jack DC)
   ┌────────────────────────────────────────┐
   │        Modul Power Supply HW-131       │
   │  [Jumper A: 5V]      [Jumper B: 3.3V]  │
   └───┬──────────┬───────────┬─────────┬───┘
       │          │           │         │
    +5V Rail     GND      +3.3V Rail   GND
       │          │           │         │
       ├──────────┼───────────┴─────────┼──► RC522 VCC (Wajib 3.3V)
       │          ├─────────────────────┼──► RC522 GND
       ├──────────┼─────────────────────┼──► Servo SG90 VCC (Merah, 5V)
       │          ├─────────────────────┼──► Servo SG90 GND (Coklat)
       ├──────────┼─────────────────────┼──► LCD 16x2 I2C VCC (5V)
       │          ├─────────────────────┼──► LCD 16x2 I2C GND
       │          └─────────────────────┼──► ESP32 GND (COMMON GROUND!)
       └────────────────────────────────┴──► ESP32 VIN / 5V
```

> ⚠️ **PENTING — ATURAN COMMON GROUND**: Seluruh jalur Ground (`GND`) dari modul HW-131, ESP32, Servo SG90, sensor RC522, dan LCD 16x2 **WAJIB terhubung bersama ke satu jalur GND yang sama**. Tanpa common ground, sinyal data PWM dan I2C akan mengalami noise dan servo tidak berputar semestinya.
>
> ⚠️ **TEGANGAN SENSOR RC522**: Pin `3.3V` sensor RC522 wajib mengambil tegangan 3.3V (dari pin `3V3` ESP32 atau rel 3.3V HW-131). **Jangan pernah menghubungkan RC522 ke tegangan 5V** karena dapat merusak IC RFID.

### 📌 Skema Pinout Hardware (ESP32 DevKit 30-Pin):

| Modul Hardware | Pin Komponen | Pin ESP32 | Keterangan & Fungsi |
| :--- | :--- | :--- | :--- |
| **RFID RC522 (SPI)** | SDA / SS | **GPIO 5 (D5)** | SPI Slave Select |
| | SCK | **GPIO 18 (D18)** | SPI Serial Clock |
| | MOSI | **GPIO 23 (D23)** | SPI Master Out Slave In |
| | MISO | **GPIO 19 (D19)** | SPI Master In Slave Out |
| | RST | **GPIO 27 (D27)** | Reset RC522 |
| | 3.3V & GND | **3.3V & GND** | Wajib 3.3V regulasi stabil |
| **LCD 16x2 I2C (PCF8574)** | SDA | **GPIO 21 (D21)** | Jalur Data I2C |
| | SCL | **GPIO 22 (D22)** | Jalur Clock I2C |
| | VCC & GND | **Rel 5V & GND HW-131** | Catu daya layar LCD |
| **Servo SG90 (Palang)** | Signal / PWM | **GPIO 13 (D13)** | Kontrol sudut palang (0° tutup, 90° buka) |
| | VCC (Merah) | **Rel 5V HW-131** | Catu daya motor servo |
| | GND (Coklat) | **GND Bersama** | Ground motor servo |
| **Buzzer Aktif/Pasif** | Pin (+) | **GPIO 25 (D25)** | Suara notifikasi gerbang & nada peringatan |
| | Pin (-) | **GND Bersama** | Ground buzzer |
| **LED Indikator** | Pin (+) | **GPIO 26 (D26)** | Resistor 220Ω (disinkronkan ke GPIO 2 internal) |
| | Pin (-) | **GND Bersama** | Ground LED |
| **Tombol BOOT** | Tombol Fisik | **GPIO 0 (BOOT)** | Tahan 2 detik untuk membuka Hotspot Portal |

---

### 📶 Konektivitas WiFi & Hotspot Web Captive Portal
Firmware ESP32 GUYUB dilengkapi fitur **Auto-Fallback Captive Portal** dengan radio RF berdaya tinggi (`WiFi.setTxPower(WIFI_POWER_19_5dBm)`):

1. **Mode Normal (Station / WiFi):**
   - ESP32 otomatis mencoba terhubung ke WiFi/Hotspot yang tersimpan di memori NVS Flash (default: `POCOX3NFC` / `12345678`).
   - Mengirim denyut status online (*heartbeat*) setiap 2-3 detik ke server GUYUB.
   - Mengirim data kartu saat warga melakukan tap.
2. **Mode Cadangan (SoftAP Hotspot HP):**
   - Jika dalam 12 detik WiFi tidak ditemukan atau sinyal terputus, ESP32 **otomatis memancarkan sinyal WiFi hotspot sendiri**:
     * **Nama Hotspot (SSID)**: `ESP32_1_JDIF`
     * **Kata Sandi (Password)**: `876543210`
     * **Alamat Web Portal**: `http://192.168.4.1`
   - Buka browser HP ke `192.168.4.1`, pilih tombol pintasan **🔵 Julius (Guyub)**, masukkan nama WiFi dan password baru, lalu klik **Simpan & Reboot Alat**.

---

### 🚀 Endpoint API IoT Backend (Laravel):
* `POST /api/rfid/tap`: Menerima scan kartu e-KTP / RFID dari ESP32.
  * Jika kartu terdaftar & akun aktif: Mengembalikan HTTP 200 dengan payload `"status": "gate_opened"`, `"open_gate": true`, `"direction": "masuk"`, `"buzzer": "gate_in"`, `"user_name": "..."`. Layar LCD menampilkan nama warga, buzzer menyala, dan palang servo membuka 90°.
  * Jika kartu belum terdaftar: Mengembalikan HTTP 403 dengan payload `"status": "unregistered"`, `"open_gate": false`, `"message": "Kartu belum terdaftar di sistem. Akses ditolak."`. LCD menampilkan *"Belum Terdaftar!"*, buzzer membunyikan nada error, dan palang gerbang tetap tertutup.
* `POST /api/rfid/heartbeat`: Menerima denyut online ESP32 setiap 2 detik dan menyimpannya di Cache Laravel untuk memonitor ketersediaan alat di web.
* `POST /rfid/manual-open`: Endpoint web terautentikasi untuk membuka palang portal secara manual dari tombol dashboard.
* `GET /rfid/portal-live`: Endpoint polling AJAX realtime setiap 3.5 detik untuk memperbarui tabel aktivitas portal tanpa reload halaman.

---

## 💻 Panduan Instalasi Lokal (Local Development)

### Kebutuhan Sistem:
* PHP >= 8.3 dengan ekstensi: `pdo_mysql`, `mbstring`, `openssl`, `xml`, `gd`, `curl`.
* Composer >= 2.x
* MySQL Server >= 8.0 / MariaDB >= 10.4

### Langkah-Langkah:
1. **Clone repositori**:
   ```bash
   git clone https://github.com/JzIsbo/GUYUB.git
   cd GUYUB/backend
   ```

2. **Salin berkas `.env` dan konfigurasikan database**:
   ```bash
   cp .env.example .env
   ```
   *Sesuaikan `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` dengan kredensial MySQL lokal Anda.*

3. **Install dependensi PHP via Composer**:
   ```bash
   composer install
   ```

4. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```

5. **Jalankan migrasi database & seed master data lengkap**:
   ```bash
   php artisan migrate --force
   php artisan db:seed --class='Database\Seeders\CompleteMasterSeeder'
   ```
   *Perintah ini akan membuat seluruh 38 tabel dan mengisi data warga, transaksi kas, inventaris, sembako koperasi, jadwal posyandu, dan aturan RT secara otomatis.*

6. **Tautkan penyimpanan berkas (Storage Link)**:
   ```bash
   php artisan storage:link
   ```

7. **Jalankan web server lokal**:
   ```bash
   php artisan serve --port=8000
   ```
   Buka browser di **`http://localhost:8000`**.

---

## 🔑 Kredensial Akun Pengujian (Demo Accounts)

Semua akun demo di bawah ini menggunakan kata sandi default: **`password`**

| Peran Akun | Nama Pengguna | Alamat Email | Hak Akses Utama |
| :--- | :--- | :--- | :--- |
| 👑 **Super Admin** | Super Admin | `superadmin@gmail.com` | Akses Penuh Sistem (38 Modul) |
| 🏢 **Ketua RW** | Ketua RW | `rw@gmail.com` | Monitoring Wilayah RW 02 & Rekap Lingkungan |
| 🏢 **Sekretaris RW** | Sekretaris RW | `sekretarisrw@gmail.com` | Administrasi, Persuratan & Pengumuman RW 02 |
| 🏢 **Bendahara RW** | Bendahara RW | `bendahararw@gmail.com` | Pembukuan & Laporan Kas Keuangan RW 02 |
| 🏡 **Ketua RT** | Ketua RT | `rt@gmail.com` | Kelola Warga, Approval Registrasi, Aset & Kas RT 05 |
| 📜 **Sekretaris RT**| Sekretaris RT | `sekretaris@gmail.com` | Administrasi Warga, Surat Pengantar & Notulensi RT |
| 💵 **Bendahara RT** | Bendahara RT | `bendahara@gmail.com` | Pembukuan Kas RT, Tagihan Iuran & Koperasi |
| 👥 **Warga Terdaftar** | Steve Marvin Politon | `steve@gmail.com` | Layanan Mandiri, Simpan Pinjam & Akses e-KTP RFID |
| 👥 **Warga Demo** | Warga | `warga@gmail.com` | Layanan Mandiri Warga Umum |

---

<div align="center">

© 2026 **GUYUB** — *Gerbang Urusan dan Layanan Warga Bersama*.  
Dikembangkan untuk mewujudkan tata kelola lingkungan rukun tetangga yang transparan, aman, dan berdaya digital.

</div>
