# 🏠 GUYUB — Backend Core Engine

Dokumen ini menjelaskan arsitektur internal server backend **GUYUB** (*Gerbang Urusan dan Layanan Warga Bersama*) yang dibangun menggunakan kerangka kerja **Laravel 11**.

---

## 🛠️ Modul Utama Engine

1. **RBAC Permission System**:
   - Pengaturan matriks hak akses halaman dan tindakan (*Super Admin*, *RT*, *Bendahara*, *Warga*).
2. **CCTV Audit Trail Logger**:
   - Pencatatan aktivitas pengguna secara otomatis (CUD, Login, Ekspor, Backup) ke dalam tabel `activity_logs`.
3. **Database Self-Healing Engine**:
   - Verifikasi skema tabel otomatis yang di-throttle dengan cache 60 menit untuk menjamin stabilitas tanpa menurunkan kecepatan.
4. **Agregasi Keuangan High-Efficiency**:
   - Perhitungan kas dan pemasukan menggunakan agregasi SQL native untuk efisiensi penggunaan CPU dan RAM.

---

## 📋 Jalankan Server Lokal

```bash
cd backend
php artisan migrate:fresh --seed
php artisan serve
```

Aplikasi berjalan di: `http://127.0.0.1:8000`
