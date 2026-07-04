# Frontend Static Client - Kas RT Digital

Bagian ini merupakan antarmuka pengguna (user interface) dari Aplikasi Kas RT Digital. Dibangun menggunakan teknologi web standar tanpa framework JS berat agar performanya sangat cepat, ringan, dan mudah di-hosting.

## 📁 Folder Assets & File Struktur

```
frontend/
├── assets/
│   ├── css/
│   │   ├── global.css      # Konfigurasi Font (Poppins & Plus Jakarta), variabel warna, reset, dan utility kelas
│   │   ├── landing.css     # Kustomisasi hero background, tab tombol publik, dan kartu UMKM/Kegiatan
│   │   ├── login.css       # Tampilan antarmuka login yang modern dan animasi melayang (floating)
│   │   └── dashboard.css   # Tampilan navigasi sidebar, custom scrollbar, dan modul aktif admin panel
│   └── js/
│       ├── app.js          # Pengaturan URL API utama & fallback, override alert default menggunakan SweetAlert2
│       ├── landing.js      # Pemanggilan data publik dari API backend, perpindahan tab (Pengumuman, UMKM, Posyandu, Ronda)
│       ├── login.js        # Validasi form, toggle password, post request ke Auth API backend
│       └── dashboard.js    # SPA router (pergantian modul menu tanpa page-reload), manajemen sub-menu dropdown, logout confirmation
├── index.html              # Halaman publik warga (informasi umum, kegiatan, dan jadwal ronda)
├── login.html              # Gerbang masuk ke portal admin & warga
└── dashboard.html          # Panel kendali dashboard internal RT
```

## 🔌 Integrasi API Backend

Frontend berkomunikasi dengan backend menggunakan **Fetch API** secara asinkronus (AJAX). Untuk menjamin kehandalan sistem saat dijalankan di komputer lokal warga, frontend dilengkapi dengan mekanisme **API Fallback**:

1. **Jalur Utama:** `../backend/public` (Menggunakan konfigurasi default jika diletakkan langsung di dalam direktori virtual host / `htdocs` Apache).
2. **Jalur Cadangan:** `http://127.0.0.1:8000` (Secara otomatis terpicu jika server utama mati, mengarah langsung ke Artisan Serve development port).
