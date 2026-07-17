<div align="center">

# 🏠 GUYUB — Gerbang Urusan & Layanan Warga Bersama

![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-3.4-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Platform](https://img.shields.io/badge/Architecture-Single_Page_App_(SPA)-2563EB?style=for-the-badge)

<p align="center">
  <b>Solusi Digital Terpadu Administrasi, Keuangan, & Pelayanan Mandiri Lingkungan RT/RW Modern</b>
</p>

<p align="center">
  <a href="#-fitur-unggulan">Fitur Utama</a> •
  <a href="#-matriks-hak-akses-role">Matriks Role</a> •
  <a href="#-arsitektur--optimasi-performa">Arsitektur</a> •
  <a href="#-sistem-antarmuka-responsif-mobile">Mobile UX</a> •
  <a href="#-panduan-instalasi--jalankan-sistem">Instalasi</a> •
  <a href="#-kredensial-akun-demo">Demo Accounts</a>
</p>

---

</div>

## 📌 Tentang GUYUB

**GUYUB** (*Gerbang Urusan dan Layanan Warga Bersama*) adalah ekosistem aplikasi web Single Page Application (SPA) hibrida berkecepatan tinggi yang dirancang khusus untuk mendigitalisasi seluruh aktivitas warga dan pengurus RT/RW secara **transparan**, **akuntabel**, **cepat**, dan **bebas hambatan**.

Dengan integrasi fitur keuangan modern (QRIS & Payment Gateway), administrasi surat-menyurat instan, hingga sistem pemantauan ronda malam, layanan kesehatan Posyandu, dan tabungan Bank Sampah, GUYUB menciptakan keharmonisan serta transparansi penuh di tingkat komunitas warga.

---

## ✨ Fitur Unggulan

### 🏛️ 1. Manajemen Administrasi & Kepengurusan
* **Surat Online Instan**: Pengajuan surat pengantar warga secara digital dengan sistem persetujuan instan oleh Ketua RT.
* **Pengumuman Publik**: Publikasi informasi resmi RT yang dapat dibaca oleh seluruh warga secara terbuka.
* **Direktori Warga & Pengurus**: Pencatatan data identitas warga, kepala keluarga, serta susunan pengurus RT terstruktur (mendukung integrasi data gender/jenis kelamin).
* **Inventaris Aset & Perangkat Sistem**: Pendataan perangkat dan aset inventaris lingkungan RT dengan pelacakan penanggung jawab.

### 💵 2. Keuangan & Iuran Terintegrasi
* **Pencatatan Arus Kas**: Transparansi dana masuk (pemasukan) dan pengeluaran kas RT secara realtime.
* **Pembayaran Online & QRIS**: Dukungan Virtual Account & QRIS otomatis untuk pembayaran iuran bulanan warga.
* **Laporan Arus Kas & Ekspor PDF**: Penjumlahan agregat otomatis dan laporan keuangan yang siap diunduh dalam format PDF resmi.

### 🛡️ 3. Keamanan & Layanan Komunitas
* **Jadwal Ronda Malam**: Pembagian regu piket ronda malam per hari beserta nama koordinator shift.
* **Posyandu Balita & Lansia**: Pencatatan tumbuh kembang anak dan riwayat pemeriksaan kesehatan lansia (mendukung pelacakan data gender/jenis kelamin).
* **Marketplace UMKM Warga**: Wadah promosi usaha lokal antar warga lingkungan dengan integrasi peta lokasi usaha.
* **Bank Sampah Lingkungan**: Catatan penimbangan dan setoran sampah terdaur ulang.
* **Koperasi Warga & Rukem**: Pengelolaan simpan pinjam, modal usaha UMKM, serta dana santunan duka cita warga (Rukem).
* **Kotak Aspirasi & Notifikasi Realtime**: Penyampaian masukan warga dan lonceng notifikasi aktivitas otomatis.

---

## 👥 Matriks Hak Akses Role (Role-Based Access Control)

Sistem **GUYUB** dilengkapi dengan pembagian hak akses (*RBAC*) yang ketat untuk menjamin keamanan data:

| Modul / Fitur | 👑 Super Admin | 👤 Ketua RT | 💵 Bendahara | 🏡 Warga |
|---|:---:|:---:|:---:|:---:|
| **Dashboard Utama** | ✅ Full | ✅ Full | ✅ Full | ✅ View Only |
| **Surat Online** | ✅ Kelola & Approve | ✅ Kelola & Approve | 👁️ Lihat Data | 📝 Ajukan Surat |
| **Pengumuman RT** | ✅ Buat / Edit / Hapus | ✅ Buat / Edit / Hapus | 👁️ Lihat | 👁️ Lihat |
| **Tagihan & Status Pembayaran** | ✅ Full Access | 👁️ Monitoring | ✅ Full Access | 💳 Bayar Tagihan |
| **Pemasukan & Pengeluaran Kas** | ✅ Full Access | 👁️ Monitoring | ✅ Full Access | ❌ Closed |
| **Laporan Kas & Ekspor PDF** | ✅ Full Access | ✅ Export PDF | ✅ Full Access | ❌ Closed |
| **Data Warga & Pengurus RT** | ✅ Edit / Delete | ✅ Edit / Delete | 👁️ Direktori | 👁️ Direktori |
| **Jadwal Ronda & Keamanan** | ✅ Kelola Shift | ✅ Kelola Shift | 👁️ Lihat | 👁️ Lihat Jadwal |
| **Posyandu, UMKM, Bank Sampah** | ✅ Kelola Data | ✅ Kelola Data | ✅ Kelola Data | 👁️ Partisipasi |
| **Log Aktivitas & Audit Trail** | ✅ Complete Logs | ❌ Closed | ❌ Closed | ❌ Closed |
| **Pengaturan Akun & Profil** | ✅ Auto-Sync Warga | ✅ Auto-Sync Warga | ✅ Auto-Sync Warga | ✅ Auto-Sync Warga |

---

## 🏗️ Arsitektur & Optimasi Performa

```text
+-------------------------------------------------------------------------------+
|                                BROWSER CLIENT                                 |
|  +-------------------------------------------------------------------------+  |
|  | Event-driven Prefetching Engine (mouseenter & touchstart)               |  |
|  | SWR Caching Router (0ms Instant Page Transitions)                       |  |
|  | Top Loading Progress Bar & Custom Flyout Tooltips                       |  |
|  +-------------------------------------------------------------------------+  |
+---------------------------------------+---------------------------------------+
                                        | AJAX (JSON / HTML Partial)
                                        v
+-------------------------------------------------------------------------------+
|                              LARAVEL 11 BACKEND                               |
|  +------------------------------------+------------------------------------+  |
|  | Role Permission Middleware         | SQL SUM Aggregation Engine         |  |
|  | Audit Trail CCTV Logger            | Self-Healing DB Cache Throttle     |  |
|  +------------------------------------+------------------------------------+  |
+---------------------------------------+---------------------------------------+
                                        | PDO
                                        v
+-------------------------------------------------------------------------------+
|                                MYSQL DATABASE                                 |
+-------------------------------------------------------------------------------+
```

1. **⚡ SWR (Stale-While-Revalidate) Page Caching**: Perpindahan halaman instan tanpa jeda *loading* berulang saat bernavigasi.
2. **🚀 Event-driven Prefetching Engine**:
   - Memindai seluruh tautan dengan selektor `[onclick*="switchPage"]`.
   - Menggunakan listener `mouseenter` (desktop) dan `touchstart` (mobile) untuk memicu prefetch halaman 100ms lebih awal sebelum browser mengeksekusi aksi klik pengguna.
3. **📊 Top Loading Progress Bar**: Menyediakan umpan balik visual instan di bagian teratas layar viewport saat request jaringan sedang aktif (*cache miss*).
4. **🗄️ Database Query Aggregation**: Perhitungan saldo dan laporan kas diproses di level database engine menggunakan fungsi agregat `SUM()`, menghemat memori PHP hingga 90%.
5. **🛡️ Lazy Database Self-Healing**: Validasi skema database otomatis menggunakan penyimpan cache waktu sehingga tidak membebani query per detik.
6. **🔒 120 Protected Action Endpoints**: Seluruh pengiriman formulir dan tombol aksi pada 12 modul dilindungi dengan mekanisme pembersihan cache otomatis (*cache invalidation*) pasca-mutasi untuk menjamin keselarasan data.

---

## 📱 Sistem Antarmuka Responsif (Mobile UX)

* **Viewport-Aware Theme Loader**: Script pemindai dinamis pada `<head>` mendeteksi ukuran layar secara instan. Jika lebar viewport di bawah `768px`, cookie `device_mode=mobile` disematkan dan layout khusus mobile dari folder `partials/mobile/` dimuat secara otomatis dari sisi server.
* **Vertical Mobile Stacked Header**: Header halaman utama publik (`welcome`) tersusun secara vertikal terpusat di mobile, mempertahankan logo **GUYUB** beserta penulisan taglinenya secara lengkap di tengah, dengan tombol aksi tersusun rapi di bagian bawah.
* **Alineasi Tombol Aksi Bawah**: Pada bilah menu atas mobile, tombol hamburger garis 3 diposisikan di kiri mentok, tombol Dashboard/Login di kanan mentok, dan ikon ubah tema tetap berada di samping tombol Dashboard.
* **Akses Data Keluarga Pintar**: Tab kelima pada bilah navigasi bawah mobile dialihkan secara khusus ke menu **Keluarga** (`/data-keluarga`) untuk akses cepat dokumen Kartu Keluarga.

---

## 🚀 Panduan Instalasi & Jalankan Sistem

### 1. Prasyarat Sistem
- **PHP** >= 8.2 (Ekstensi: OpenSSL, PDO, Mbstring, Tokenizer, XML)
- **Composer** >= 2.x
- **MySQL Server / MariaDB** (XAMPP / Laragon)

### 2. Langkah Instalasi Backend

1. Buka Terminal / Command Prompt, lalu masuk ke folder `backend`:
   ```bash
   cd backend
   ```

2. Buat berkas environment dari template:
   ```bash
   cp .env.example .env
   ```

3. Pasang dependency composer:
   ```bash
   composer install
   ```

4. Buat kunci enkripsi aplikasi:
   ```bash
   php artisan key:generate
   ```

5. Jalankan migrasi tabel dan seeding data awal:
   ```bash
   php artisan migrate:fresh --seed
   ```

6. Jalankan server lokal Laravel:
   ```bash
   php artisan serve
   ```
   Aplikasi siap diakses di: **`http://127.0.0.1:8000`**

---

## 🔑 Kredensial Akun Demo

Gunakan akun pengujian di bawah ini untuk mencoba antarmuka dan hak akses yang berbeda:

| Peran (Role) | Email / Username | Kata Sandi |
|---|---|---|
| 👑 **Super Admin** | `superadmin@gmail.com` | `password` |
| 👤 **Ketua RT** | `rt@gmail.com` | `password` |
| 💵 **Bendahara** | `bendahara@gmail.com` | `password` |
| 🏡 **Warga** | `warga@gmail.com` | `password` |

---

<div align="center">

© 2026 **GUYUB** — *Gerbang Urusan dan Layanan Warga Bersama*.  
Dibuat untuk lingkungan warga yang transparan, modern, dan saling terhubung.

</div>
