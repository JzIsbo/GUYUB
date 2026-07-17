# 🏠 GUYUB — Gerbang Urusan & Layanan Warga Bersama

[![Laravel Version](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-8.2%20%7C%208.3-777BB4?style=flat-square&logo=php&logoColor=white)](https://php.net)
[![TailwindCSS](https://img.shields.io/badge/TailwindCSS-3.4-38B2AC?style=flat-square&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat-square&logo=mysql&logoColor=white)](https://mysql.com)
[![Architecture](https://img.shields.io/badge/Architecture-Hybrid_SPA_/_SWR-2563EB?style=flat-square)](#)
[![Mobile Responsive](https://img.shields.io/badge/Mobile_UX-Responsive_/_Viewport_Aware-059669?style=flat-square)](#)

GUYUB (*Gerbang Urusan dan Layanan Warga Bersama*) adalah platform tata kelola lingkungan RT/RW modern berbasis web hibrida yang memadukan keandalan backend **Laravel 11** dengan arsitektur **Single Page Application (SPA) berbasis Vanilla JS** yang cepat, responsif, dan interaktif.

Platform ini menjadi jembatan digital antara pengurus wilayah (Ketua RT, Ketua RW, Bendahara) dengan warga sekitar untuk mewujudkan pengelolaan administrasi yang transparan, keuangan yang akuntabel, serta pelayanan mandiri yang praktis.

---

## 🗺️ Fitur Utama Ekosistem GUYUB

### 📂 1. Administrasi & Kependudukan (Master Data)
* **Manajemen Data Warga Terintegrasi**: Pendataan identitas lengkap warga mencakup NIK, No. KK, Usia, Agama, Alamat, Status Hubungan Keluarga, serta **Integrasi Jenis Kelamin (Gender)** secara penuh pada seluruh formulir.
* **Manajemen Kartu Keluarga (KK)**: Pengelompokan warga berdasarkan kartu keluarga dengan fitur tambah/ubah anggota keluarga langsung dari panel detail KK.
* **Persetujuan Pendaftaran Warga**: Sistem validasi pendaftaran mandiri warga baru oleh Ketua RT sebelum data resmi masuk ke database kependudukan.
* **Data Profil Pengurus RT & RW**: Struktur kepengurusan resmi wilayah lingkungan RT 05 / RW 12 di Perumahan Grand Guyub Residence.

### 💳 2. Sistem Keuangan & Iuran Lingkungan
* **Pencatatan Transaksi Kas RT**: Modul pencatatan Buku Kas Masuk (Pemasukan) dan Buku Kas Keluar (Pengeluaran) lengkap dengan sistem klasifikasi kategori (Operasional, Iuran, Santunan, Kas Sosial, dll.).
* **Generasi Tagihan Warga**: Pembuatan tagihan iuran rutin (kas bulanan, keamanan, sampah) untuk seluruh warga terdaftar secara otomatis.
* **Virtual Account & Pembayaran QRIS**: Halaman pembayaran yang menampilkan kode QRIS dinamis dan petunjuk pembayaran Virtual Account demi memudahkan pembayaran iuran nontunai.
* **Laporan Arus Kas & Ekspor PDF**: Agregasi nominal keuangan secara realtime langsung dari database engine menggunakan optimasi SQL `SUM()`, lengkap dengan cetak laporan PDF resmi siap unduh.

### 💌 3. Pelayanan Mandiri & Interaksi Warga
* **Pengajuan Surat Pengantar Online**: Warga dapat mengajukan permohonan surat pengantar (Surat Keterangan Domisili, Surat Kematian, Pengantar Pembuatan KTP, dll.) secara mandiri. Ketua RT dapat meninjau, menyetujui, atau menolak permohonan dengan satu klik.
* **Papan Pengumuman Digital**: Media informasi terpusat untuk membagikan berita penting, kegiatan kerja bakti, rapat warga, atau maklumat RT.
* **Kotak Aspirasi & Masukan**: Saluran khusus warga untuk menyampaikan keluhan, saran, atau aduan kebersihan dan keamanan secara langsung kepada pengurus RT.
* **Lonceng Notifikasi Realtime**: Notifikasi instan di header panel admin untuk memberitahu pengurus apabila ada pengajuan surat baru, pembayaran iuran menunggu konfirmasi, atau pendaftaran warga baru.

### 🏥 4. Layanan Sosial & Ekonomi Warga
* **Bank Sampah Mandiri**: Catatan setoran sampah terdaur ulang warga, penimbangan berat (kg), serta kalkulasi nilai rupiah tabungan bank sampah warga.
* **Posyandu Balita & Lansia**: Pencatatan tumbuh kembang anak (berat/tinggi badan) dan rekam medis lansia terintegrasi, lengkap dengan pencatatan otomatis jenis kelamin peserta.
* **Koperasi & UMKM Guyub**:
  - **Koperasi Simpan Pinjam**: Pencatatan total modal usaha, simpanan wajib/pokok warga, serta pengajuan pinjaman dengan pelacakan status persetujuan.
  - **Pemesanan Sembako Koperasi**: Formulir pemesanan bahan pokok (beras, minyak, telur) secara kolektif dengan metode pembayaran tunai/kas warga.
  - **Marketplace UMKM**: Etalase digital untuk mempromosikan usaha milik warga sekitar dengan penyertaan peta lokasi usaha.
* **Rukun Kematian (Rukem)**: Pengelolaan dana sosial duka, pendataan peristiwa kematian warga, serta pencatatan penyaluran uang santunan duka cita secara transparan.

---

## 👥 Matriks Hak Akses Peran (Role-Based Access Control)

GUYUB mengimplementasikan otorisasi tingkat lanjut berbasis peran (*Role*) untuk memastikan keamanan data kependudukan dan keuangan wilayah:

| Modul / Fitur / Hak Akses | 👑 Super Admin | 👤 Ketua RT | 💵 Bendahara | 🏡 Warga |
| :--- | :---: | :---: | :---: | :---: |
| **Dashboard Analitis & Statistik** | 🛠️ Kelola / Edit | 🛠️ Kelola / Edit | 🛠️ Kelola / Edit | 👁️ Hanya Lihat |
| **Persetujuan Warga Baru** | ✅ Terima / Tolak | ✅ Terima / Tolak | ❌ Tidak Ada Akses | ❌ Tidak Ada Akses |
| **Surat Online (Approval)** | ✅ Setujui / Tolak | ✅ Setujui / Tolak | 👁️ Hanya Lihat | 📝 Ajukan Mandiri |
| **Pengumuman & Berita RT** | 🛠️ Kelola / Edit | 🛠️ Kelola / Edit | 👁️ Hanya Lihat | 👁️ Hanya Lihat |
| **Tagihan Iuran Warga** | 🛠️ Buat & Edit | 👁️ Monitoring | 🛠️ Buat & Edit | 💳 Bayar & Upload Bukti |
| **Pemasukan & Pengeluaran Kas** | 🛠️ Kelola / Edit | 👁️ Monitoring | 🛠️ Kelola / Edit | ❌ Tidak Ada Akses |
| **Ekspor PDF Laporan Kas** | ✅ Unduh PDF | ✅ Unduh PDF | ✅ Unduh PDF | ❌ Tidak Ada Akses |
| **Jadwal Ronda & Keamanan** | 🛠️ Kelola Shift | 🛠️ Kelola Shift | 👁️ Hanya Lihat | 👁️ Lihat Jadwal |
| **Posyandu, UMKM, Bank Sampah** | 🛠️ Kelola / Edit | 🛠️ Kelola / Edit | 🛠️ Kelola / Edit | 👁️ Partisipasi |
| **Koperasi Warga (Simpan Pinjam)**| 🛠️ Kelola / Edit | 👁️ Monitoring | 🛠️ Kelola / Edit | 📝 Ajukan Pinjaman / Sembako|
| **Log Aktivitas & Audit Trail** | 👁️ Lihat Log | ❌ Tidak Ada Akses | ❌ Tidak Ada Akses | ❌ Tidak Ada Akses |
| **Pengaturan Data Diri (Profil)** | 🛠️ Auto-Sync Warga| 🛠️ Auto-Sync Warga| 🛠️ Auto-Sync Warga| 🛠️ Auto-Sync Warga|

---

## ⚡ Arsitektur & Rekayasa Performa SPA Hibrida

Platform GUYUB tidak menggunakan framework JS berat (seperti React/Vue) untuk meminimalkan beban memori client, melainkan menggunakan **Arsitektur Custom SPA Router berbasis Vanilla JS** yang sangat dioptimalkan:

```text
                                +-------------------------------------------+
                                |               BROWSER CLIENT              |
                                |  +-------------------------------------+  |
                                |  | - SPA Router (DOM Swapping)         |  |
                                |  | - Event-driven Prefetching (0ms)    |  |
                                |  | - Dynamic Theme Switcher            |  |
                                |  | - Top Loading Progress Bar          |  |
                                |  +-------------------------------------+  |
                                +---------------------+---------------------+
                                                      |
                                                      | AJAX (JSON / HTML Partial)
                                                      v
                                +---------------------+---------------------+
                                |             LARAVEL 11 BACKEND            |
                                |  +-------------------------------------+  |
                                |  | - Viewport-Aware Response resolver  |  |
                                |  | - Transaction Safe DB Aggregation    |  |
                                |  | - Cache Invalidation (120 actions)  |  |
                                |  +-------------------------------------+  |
                                +---------------------+---------------------+
                                                      |
                                                      | Eloquent ORM (PDO)
                                                      v
                                +---------------------+---------------------+
                                |               MYSQL DATABASE              |
                                +-------------------------------------------+
```

### 1. Sistem Caching & Prefetching Latar Belakang (0ms Page Loading)
- **Event-Driven Prefetcher**: Ketika halaman awal dimuat, script memindai seluruh elemen navigasi bertanda `[onclick*="switchPage"]`. Halaman-halaman tujuan akan otomatis diunduh (*fetch*) secara asinkron di latar belakang dan disimpan dalam memori `window.pageCache`.
- **Touch & Hover Optimization**: Menempelkan event listener `mouseenter` (desktop) dan `touchstart` (mobile) pada tombol navigasi. Halaman akan mulai di-prefetch 100ms hingga 150ms sebelum pengguna melepaskan ketukan jari mereka, menghasilkan transisi layar instan tanpa delay (*zero loading time*).
- **Top Loading Progress Bar**: Sebagai bentuk umpan balik visual (*visual feedback*), progress bar tipis gradasi warna biru setinggi `3px` akan meluncur di puncak layar jika terjadi pemuatan data dari jaringan (*cache miss*).

### 2. Viewport-Aware Server Side Rendering (Mobile UX)
- **Automatic Device Mode Sync**: Script pendeteksi ukuran layar pada `<head>` mendeteksi lebar viewport secara instan. Jika lebar viewport di bawah `768px`, sistem menyematkan cookie `device_mode=mobile`.
- **Dynamic Partial View Resolver**: Di tingkat pengontrol ([AdminController.php](file:///c:/xampp/htdocs/kas-rt/backend/app/Http/Controllers/AdminController.php)), Laravel membaca status cookie tersebut dan meresolusi sub-halaman khusus dari direktori `admin/partials/mobile/` sehingga tata letak antarmuka beralih 100% menjadi ramah genggaman tangan.
- **Vertical Mobile Stacked Header**: Tampilan banner atas halaman landing publik (`welcome`) tersusun secara vertikal terpusat di mobile, mempertahankan logo **GUYUB** beserta penulisan taglinenya secara lengkap di tengah, dengan tombol aksi tersusun rapi di bagian bawah.
- **Alineasi Tombol Aksi Bawah**: Pada bilah menu atas mobile, tombol hamburger garis 3 diposisikan di kiri mentok, tombol Dashboard/Login di kanan mentok, dan ikon ubah tema tetap berada di samping tombol Dashboard.

### 3. Perlindungan Integritas Aksi (120 Protected Endpoints)
- **Cache Auto-Invalidation**: Untuk menjamin keselarasan data, seluruh pengiriman formulir dan tombol aksi pada 12 modul (total 120 titik eksekusi) dilindungi dengan mekanisme pembersihan cache otomatis (*cache invalidation*) pasca-mutasi. Perubahan data pada modul apa pun akan langsung memperbarui grafik dashboard secara realtime pada navigasi berikutnya.

---

## 🛠️ Panduan Instalasi & Menjalankan Aplikasi

### 1. Kebutuhan Sistem Minimum
* **PHP** >= 8.2 (Pastikan ekstensi `openssl`, `pdo_mysql`, `mbstring`, `xml`, dan `gd` aktif di berkas `php.ini`).
* **Composer** >= 2.x.
* **MySQL Server** 8.0 atau **MariaDB** 10.4 (bawaan XAMPP / Laragon).

### 2. Langkah-Langkah Pemasangan Lokal

1. **Unduh repositori dan masuk ke folder `backend`**:
   ```bash
   git clone https://github.com/JzIsbo/GUYUB.git
   cd GUYUB/backend
   ```

2. **Salin dan sesuaikan berkas konfigurasi environment**:
   ```bash
   cp .env.example .env
   ```
   *Buka berkas `.env` yang baru dibuat, sesuaikan nama database (`DB_DATABASE`), username (`DB_USERNAME`), dan password (`DB_PASSWORD`) sesuai konfigurasi database MySQL Anda.*

3. **Pasang pustaka dependensi PHP**:
   ```bash
   composer install
   ```

4. **Buat Application Key unik**:
   ```bash
   php artisan key:generate
   ```

5. **Jalankan migrasi tabel database beserta data seeder awal**:
   ```bash
   php artisan migrate:fresh --seed
   ```
   *Perintah ini akan membuat struktur database baru yang bersih dan mengisinya dengan 203 data warga simulasi yang realistis, jadwal ronda, kas masuk-keluar, serta data koperasi.*

6. **Tautkan direktori penyimpanan file (Storage Link)**:
   ```bash
   php artisan storage:link
   ```

7. **Jalankan web server lokal**:
   ```bash
   php artisan serve
   ```
   Aplikasi Anda kini berjalan dan siap diakses di browser melalui tautan: **`http://127.0.0.1:8000`**

---

## 🔑 Kredensial Akun Pengujian (Demo Accounts)

Untuk mempermudah pengujian matriks hak akses dan peran user, silakan gunakan kredensial demo di bawah ini:

| Akun Peran | Alamat Email / Username | Kata Sandi |
| :--- | :--- | :--- |
| 👑 **Super Admin** | `superadmin@gmail.com` | `password` |
| 👤 **Ketua RT** | `rt@gmail.com` | `password` |
| 💵 **Bendahara** | `bendahara@gmail.com` | `password` |
| 🏡 **Warga** | `warga@gmail.com` | `password` |

---

## 🔧 Pemeliharaan & Troubleshooting

* **Membersihkan Cache Aplikasi**:
  Jika Anda melakukan perubahan struktural pada berkas blade tetapi perubahan tidak langsung tampil di browser, bersihkan cache kompilasi template dengan perintah:
  ```bash
  php artisan view:clear
  php artisan cache:clear
  ```

* **Reset Data Ulang**:
  Untuk mengembalikan kondisi database ke pengaturan pabrik (seperti semula saat awal instalasi), jalankan perintah:
  ```bash
  php artisan db:seed --class=TambahWargaDanTransaksiSeeder
  ```

---

<div align="center">

© 2026 **GUYUB** — *Gerbang Urusan dan Layanan Warga Bersama*.  
Dirancang dengan integritas penuh untuk menciptakan transparansi keuangan dan kepraktisan layanan mandiri warga.

</div>
