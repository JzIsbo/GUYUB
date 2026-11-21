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

| Modul / Fitur Sistem | 👑 Super Admin | 👤 Ketua RT | 💵 Bendahara | 🏡 Warga |
| :--- | :---: | :---: | :---: | :---: |
| **Dashboard & Grafik Lingkungan** | ✅ Penuh | ✅ Penuh | ✅ Penuh | 👁️ Ringkasan |
| **Persetujuan Akun Warga Baru** | ✅ Kelola | ✅ Setujui / Tolak | ❌ Dibatasi | ❌ Dibatasi |
| **Registrasi Kartu e-KTP / RFID** | ✅ Kelola | ✅ Kelola | ❌ Dibatasi | 💳 Kartu Sendiri |
| **Buku Kas & Transaksi Kas RT** | ✅ Penuh | 👁️ Monitoring | ✅ Input & Edit | ❌ Dibatasi |
| **Penerbitan Tagihan Iuran** | ✅ Penuh | 👁️ Monitoring | ✅ Buat & Tagih | 💳 Bayar Mandiri |
| **Inventaris & Peminjaman Aset** | ✅ Penuh | ✅ Setujui Pinjam | 👁️ Monitoring | 📝 Ajukan Pinjam |
| **Koperasi Sembako & Simpan Pinjam** | ✅ Penuh | 👁️ Monitoring | ✅ Verifikasi Dana | 🛒 Belanja & Menabung |
| **Posyandu, UMKM, Bank Sampah** | ✅ Penuh | ✅ Kelola | ✅ Kelola | 👁️ Partisipasi |
| **Surat Pengantar Online** | ✅ Penuh | ✅ Terbitkan Surat | 👁️ Monitoring | 📝 Buat Pengajuan |
| **Aspirasi & Masukan Warga** | ✅ Penuh | 💬 Tanggapi | 👁️ Monitoring | 📝 Kirim Aspirasi |
| **Backup Database & Audit Trail** | 🛠️ Backup/Restore | ❌ Dibatasi | ❌ Dibatasi | ❌ Dibatasi |

---

## 📡 Integrasi IoT Portal Gerbang (ESP32 + RFID e-KTP)

Source code firmware mikrokontroler tersedia di direktori [`esp32/rfid_gate/rfid_gate.ino`](esp32/rfid_gate/rfid_gate.ino).

### Skema Rangkaian Pin (Pinout):
* **Sensor MFRC522 (SPI)**:
  * `SDA / SS`: Pin `D10` (Arduino Nano ESP32) atau `GPIO 5` (ESP32 Wroom)
  * `SCK`: Pin `D13` / `GPIO 18`
  * `MOSI`: Pin `D11` / `GPIO 23`
  * `MISO`: Pin `D12` / `GPIO 19`
  * `RST`: Pin `D9` / `GPIO 22`
  * `3.3V` & `GND`
* **Relay Modul (Active LOW)**:
  * `IN`: Pin `D4` (Arduino Nano ESP32) atau `GPIO 4` (ESP32 Wroom)
  * `VCC`: `5V` & `GND`

### Endpoint Komunikasi API:
* `POST /api/rfid/heartbeat`: Mengirim status sinyal dan waktu respon alat ke dashboard.
* `POST /api/rfid/gate-check`: Mengirim scan UID kartu e-KTP; backend memeriksa apakah kartu terdaftar dan akun berstatus **Aktif**. Jika valid, server menginstruksikan relay terbuka selama 3 detik.
* `POST /api/rfid/scan`: Mengirim UID hasil tap saat mode pendaftaran kartu dibuka di antarmuka web.

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
| 🏢 **Ketua RW** | Ketua RW | `rw@gmail.com` | Monitoring Wilayah RW 02 |
| 🏡 **Ketua RT** | Ketua RT | `rt@gmail.com` | Kelola Warga, Approval & Kas RT 05 |
| 💵 **Bendahara RT** | Bendahara RT | `bendahara@gmail.com` | Pembukuan Kas & Tagihan Iuran |
| 📜 **Sekretaris RT**| Sekretaris RT | `sekretaris@gmail.com` | Surat Menyurat & Pengumuman |
| 👥 **Warga Terdaftar** | Steve Marvin Politon | `steve@gmail.com` | Layanan Mandiri & Kartu RFID |

---

<div align="center">

© 2026 **GUYUB** — *Gerbang Urusan dan Layanan Warga Bersama*.  
Dikembangkan untuk mewujudkan tata kelola lingkungan rukun tetangga yang transparan, aman, dan berdaya digital.

</div>
