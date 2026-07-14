# 🏠 GUYUB — Gerbang Urusan dan Layanan Warga Bersama

**GUYUB** adalah platform sistem informasi dan pelayanan digital modern berbasis web yang dirancang khusus untuk mengelola administrasi lingkungan RT/RW secara terpadu, transparan, cepat, dan efisien.

---

## ✨ Fitur Utama

- **🏛️ Dashboard Executive & Statistik Realtime**: Ringkasan kas RT, statistik warga, grafik transparansi keuangan, dan jadwal kegiatan.
- **💵 Keuangan & Iuran Digital**: Pengelolaan arus kas (pemasukan & pengeluaran), laporan keuangan, pembayaran QRIS/Virtual Account, serta iuran otomatis.
- **📝 Surat Online & Layanan Mandiri**: Pengajuan surat pengantar warga secara digital dengan persetujuan pengurus RT.
- **🛡️ Keamanan & Jadwal Ronda**: Pengelolaan regu ronda malam, jadwal piket, dan sistem pelaporan situasi lingkungan.
- **🩺 Posyandu & Kesehatan Keluarga**: Pencatatan tumbuh kembang balita, riwayat kesehatan lansia, dan informasi Posyandu.
- **🛍️ Koperasi & Pembinaan UMKM**: Marketplace usaha warga lingkungan untuk saling mendukung ekonomi lokal.
- **♻️ Bank Sampah Lingkungan**: Manajemen setoran dan penimbangan sampah terdaur ulang.
- **📢 Pengumuman & Aspirasi Warga**: Wadah komunikasi langsung warga dengan pengurus RT secara terbuka.
- **🔒 Audit Trail & CCTV Notifikasi**: Log aktivitas pengguna otomatis dan lonceng notifikasi realtime.

---

## 📁 Struktur Repositori

```
GUYUB/
├── backend/               # Core Application (Laravel 11, Engine & Views SPA)
│   ├── app/
│   │   ├── Http/Controllers/   # Logic & Controller
│   │   └── Models/             # Eloquent Database Models
│   ├── config/                 # Application Configurations
│   ├── database/               # Migrations & Seeders
│   ├── public/                 # Assets & User File Uploads
│   ├── resources/views/        # Blade Templates & Component Views
│   └── routes/                 # Web & API Route Definitions
├── frontend/              # Web Client Static Interface
└── README.md              # Dokumentasi Utama GUYUB
```

---

## 🚀 Panduan Instalasi & Penggunaan

### 1. Prasyarat Sistem
- **PHP** >= 8.2
- **Composer** >= 2.x
- **MySQL / MariaDB** (XAMPP Server)
- **Node.js & NPM** (Opsional)

### 2. Langkah Instalasi Backend (Laravel Core)

1. Masuk ke direktori `backend`:
   ```bash
   cd backend
   ```

2. Salin file environment dan atur koneksi database:
   ```bash
   cp .env.example .env
   ```

3. Install dependencies composer:
   ```bash
   composer install
   ```

4. Generate Application Key:
   ```bash
   php artisan key:generate
   ```

5. Jalankan migrasi database beserta data awal (seeders):
   ```bash
   php artisan migrate:fresh --seed
   ```

6. Jalankan server lokal:
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses melalui browser di: `http://127.0.0.1:8000` atau melalui server lokal XAMPP `http://localhost/kas-rt/backend/public`.

---

## 🔐 Kredensial Akun Demo

Untuk mencoba berbagai tingkat hak akses pengguna di dalam sistem GUYUB:

| Peran (Role) | Email / Username | Kata Sandi | Akses Utama |
|---|---|---|---|
| **Super Admin** | `superadmin@gmail.com` | `password` | Akses Penuh Sistem & Pengaturan |
| **Ketua RT** | `rt@gmail.com` | `password` | Manajerial Warga, Surat, & Kebijakan |
| **Bendahara** | `bendahara@gmail.com` | `password` | Pengelolaan Kas, Iuran, & Laporan Keuangan |
| **Warga** | `warga@gmail.com` | `password` | Layanan Surat, Iuran Saya, & UMKM |

---

## ⚡ Keunggulan Performa & Arsitektur

1. **SPA Interaktif (0ms Navigation)**: Menggunakan pola *Stale-While-Revalidate (SWR)* client-side caching untuk perpindahan halaman yang instan tanpa menderita loading berulang.
2. **Database Aggregation Efficient**: Query agregasi SQL dioptimalkan langsung di level database (`SUM()`, `LEFT JOIN` group by) tanpa membebankan alokasi memori PHP.
3. **Lazy Self-Healing Throttle**: Proteksi struktur tabel otomatis yang ter-cache secara efisien sehingga tidak membebani query SQL di setiap request.

---

© 2026 **GUYUB** — *Gerbang Urusan dan Layanan Warga Bersama*.
