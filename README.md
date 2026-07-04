# Aplikasi Kas RT Digital System

Aplikasi berbasis web modern untuk digitalisasi administrasi lingkungan RT/RW. Proyek ini memisahkan arsitektur backend dan frontend secara profesional untuk modularitas, kemudahan pemeliharaan, dan performa tinggi.

---

## 📁 Struktur Repositori

```
Aplikasi_RT/
├── backend/               # Laravel 11 Core API & Database Layer
│   ├── app/
│   ├── config/
│   ├── database/
│   └── routes/
├── frontend/              # Static SPA Client (HTML5, TailwindCSS, Vanilla JS)
│   ├── assets/
│   │   ├── css/          # Modular CSS Files
│   │   └── js/           # Modular JS Actions & Router
│   ├── index.html         # Landing Page Publik
│   ├── login.html         # Form Login
│   └── dashboard.html     # Portal Panel Warga & Admin
└── README.md              # Panduan Utama Proyek
```

---

## 🛠️ Langkah Menjalankan Aplikasi

### 1. Jalankan Backend (API Laravel 11)

Pastikan XAMPP (Apache & MySQL) Anda sudah aktif.

1. Buka Terminal, masuk ke direktori `backend`:
   ```bash
   cd backend
   ```
2. Salin file `.env.example` ke `.env` (sesuaikan konfigurasi database jika diperlukan):
   ```bash
   cp .env.example .env
   ```
3. Instal dependencies Composer:
   ```bash
   composer install
   ```
4. Generate key aplikasi:
   ```bash
   php artisan key:generate
   ```
5. Jalankan migrasi dan seeder database untuk data awal (UMKM, Posyandu, Jadwal Ronda, Akun Demo):
   ```bash
   php artisan migrate:fresh --seed
   ```
6. Jalankan local development server:
   ```bash
   php artisan serve
   ```
   Backend API sekarang berjalan di: `http://127.0.0.1:8000`

### 2. Jalankan Frontend (SPA Client)

1. Buka folder `frontend` di file explorer Anda.
2. Klik dua kali pada file `index.html` untuk membuka halaman utama publik langsung di peramban (browser).
3. Anda juga dapat menjalankannya di server lokal XAMPP dengan meletakkan folder repositori ini di dalam direktori `C:/xampp/htdocs/`.
   * Akses URL publik: `http://localhost/kas-rt/frontend/index.html`
   * Akses Portal login: `http://localhost/kas-rt/frontend/login.html`

---

## 👥 Akun Demo Login

Untuk mencoba sistem administrasi internal RT, masuk menggunakan kredensial berikut:

| Peran (Role) | Email / Username | Kata Sandi |
|---|---|---|
| **Super Admin** | `superadmin@gmail.com` | `password` |
| **Ketua RT** | `rt@gmail.com` | `password` |
| **Bendahara** | `bendahara@gmail.com` | `password` |
| **Warga** | `warga@gmail.com` | `password` |

---

## 💡 Keunggulan Arsitektur BE/FE Terpisah

1. **Modular & Clean:** Kode CSS, JavaScript, dan HTML dipisahkan dalam folder modular (`assets/css` dan `assets/js`) sehingga lebih mudah dikembangkan dan dipelihara.
2. **Koneksi Resilien:** Frontend terintegrasi menggunakan Fetch API dengan sistem fallback otomatis (mencoba port XAMPP default terlebih dahulu kemudian port Artisan Serve `8000`).
3. **SPA Modern:** Halaman admin menggunakan sistem *Single Page Application* (SPA) dengan pergantian modul instan tanpa memuat ulang browser (no full reload).
