# Backend Engine — GUYUB (Gerbang Urusan dan Layanan Warga Bersama)

Bagian ini merupakan server backend berbasis **Laravel 11** yang berfungsi menyediakan REST API, manajemen database MySQL (ORM Eloquent), otentikasi sesi, serta sistem administrasi partial view untuk SPA (Single Page Application) GUYUB.

## 🚀 Fitur Utama Backend

1. **REST API Endpoint:**
   - `/api/public/data` - Mengambil data pengumuman, UMKM, kegiatan, posyandu, dan ronda untuk dipublikasikan.
   - `/api/auth/user` - Pengecekan sesi login dan informasi detail role-based user.
2. **Dashboard Engine:**
   - Menghitung statistik kas masuk, kas keluar, saldo bersih, dan total warga terdaftar.
   - Mengelompokkan riwayat pengumuman, iuran, inventaris perangkat sistem, dan log aktivitas pengguna.
3. **Database Self-Healing:**
   - Sistem akan mendeteksi dan secara otomatis memulihkan struktur database jika tabel yang dibutuhkan tidak ditemukan atau kolom belum lengkap saat inisialisasi boot.

## 🛠️ Langkah Inisialisasi

1. Salin `.env.example` ke `.env`:
   ```bash
   cp .env.example .env
   ```
2. Pastikan database MySQL sudah dibuat di phpMyAdmin Anda (default nama: `kas-rt`).
3. Jalankan perintah instalasi dependency:
   ```bash
   composer install
   ```
4. Jalankan migrasi dan seeding database:
   ```bash
   php artisan migrate:fresh --seed
   ```
5. Nyalakan server lokal:
   ```bash
   php artisan serve
   ```
