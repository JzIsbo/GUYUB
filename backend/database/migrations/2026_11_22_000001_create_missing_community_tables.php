<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. TAMBAH KOLOM LOKASI KE UMKM
        if (!Schema::hasTable('umkms')) {
            Schema::create('umkms', function (Blueprint $table) {
                $table->id();
                $table->string('nama_usaha');
                $table->string('pemilik');
                $table->string('kategori')->default('Kuliner');
                $table->string('kontak');
                $table->string('lokasi')->nullable();
                $table->text('deskripsi')->nullable();
                $table->string('gambar')->nullable();
                $table->string('status')->default('Aktif');
                $table->timestamps();
            });
        } elseif (!Schema::hasColumn('umkms', 'lokasi')) {
            Schema::table('umkms', function (Blueprint $table) {
                $table->string('lokasi')->nullable()->after('kontak');
            });
        }

        // 2. TABEL ASSET LOANS (PEMINJAMAN INVENTARIS RT)
        if (!Schema::hasTable('asset_loans')) {
            Schema::create('asset_loans', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->string('nama_warga');
                $table->string('nama_aset');
                $table->integer('jumlah_unit')->default(1);
                $table->date('tanggal_pinjam');
                $table->date('tanggal_kembali');
                $table->text('keperluan');
                $table->string('status')->default('Menunggu Approval'); // Menunggu Approval, Disetujui, Ditolak, Sedang Dipinjam, Proses Pengembalian, Sudah Dikembalikan
                $table->text('catatan_admin')->nullable();
                $table->date('tanggal_dikembalikan_aktual')->nullable();
                $table->string('kondisi_pengembalian')->nullable();
                $table->text('catatan_pengembalian')->nullable();
                $table->decimal('denda_kerusakan', 12, 2)->default(0);
                $table->timestamps();
            });
        }

        // 3. TABEL KOPERASI ORDERS (PESANAN SEMBAKO / KEBUTUHAN POKOK)
        if (!Schema::hasTable('koperasi_orders')) {
            Schema::create('koperasi_orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->string('nama_warga');
                $table->unsignedBigInteger('item_id')->nullable();
                $table->string('nama_produk');
                $table->integer('jumlah')->default(1);
                $table->decimal('total_harga', 15, 2)->default(0);
                $table->string('metode_pembayaran')->default('Tunai');
                $table->string('status')->default('Menunggu'); // Menunggu, Diproses, Selesai, Dibatalkan
                $table->timestamps();
            });
        }

        // 4. TABEL KOPERASI SIMPANANS (SIMPANAN POKOK / WAJIB / SUKARELA)
        if (!Schema::hasTable('koperasi_simpanans')) {
            Schema::create('koperasi_simpanans', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->string('nama_warga');
                $table->string('jenis_simpanan'); // Simpanan Pokok, Simpanan Wajib, Simpanan Sukarela
                $table->decimal('jumlah', 15, 2);
                $table->string('metode_pembayaran')->default('Tunai');
                $table->text('keterangan')->nullable();
                $table->string('status')->default('Menunggu Verifikasi'); // Menunggu Verifikasi, Disetujui, Ditolak
                $table->timestamps();
            });
        }

        // 5. TABEL KOPERASI PINJAMANS (SIMPAN PINJAM WARGA)
        if (!Schema::hasTable('koperasi_pinjamans')) {
            Schema::create('koperasi_pinjamans', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->string('nama_warga');
                $table->decimal('jumlah_pinjaman', 15, 2);
                $table->integer('tenor_bulan')->default(1);
                $table->decimal('angsuran_per_bulan', 15, 2)->default(0);
                $table->text('keperluan');
                $table->string('status')->default('Menunggu'); // Menunggu, Disetujui, Ditolak, Lunas
                $table->text('catatan_pengurus')->nullable();
                $table->timestamps();
            });
        }

        // 6. TABEL KOPERASI PERMODALANS (BANTUAN MODAL UMKM)
        if (!Schema::hasTable('koperasi_permodalans')) {
            Schema::create('koperasi_permodalans', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->string('nama_warga');
                $table->string('nama_usaha');
                $table->string('kategori_umkm')->default('Kuliner');
                $table->decimal('nominal_pengajuan', 15, 2);
                $table->text('deskripsi_usaha');
                $table->string('status')->default('Menunggu'); // Menunggu, Dicairkan, Ditolak
                $table->text('catatan_pengurus')->nullable();
                $table->timestamps();
            });
        }

        // 7. TABEL PERATURAN SKS (DOKUMEN SK & PERATURAN RT/RW)
        if (!Schema::hasTable('peraturan_sks')) {
            Schema::create('peraturan_sks', function (Blueprint $table) {
                $table->id();
                $table->string('judul');
                $table->string('nomor_dokumen')->nullable();
                $table->string('kategori');
                $table->date('tanggal_berlaku');
                $table->text('keterangan')->nullable();
                $table->string('file_path')->nullable();
                $table->string('status')->default('Aktif'); // Aktif, Arsip
                $table->timestamps();
            });
        }

        // 8. TABEL KERJA BAKTIS (JADWAL GOTONG ROYONG & KERJA BAKTI)
        if (!Schema::hasTable('kerja_baktis')) {
            Schema::create('kerja_baktis', function (Blueprint $table) {
                $table->id();
                $table->string('nama_kegiatan');
                $table->date('tanggal');
                $table->string('waktu_mulai')->default('07:00');
                $table->string('waktu_selesai')->default('11:00');
                $table->string('lokasi');
                $table->string('perlengkapan')->nullable();
                $table->text('keterangan')->nullable();
                $table->string('status')->default('Mendatang'); // Mendatang, Selesai, Dibatalkan
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kerja_baktis');
        Schema::dropIfExists('peraturan_sks');
        Schema::dropIfExists('koperasi_permodalans');
        Schema::dropIfExists('koperasi_pinjamans');
        Schema::dropIfExists('koperasi_simpanans');
        Schema::dropIfExists('koperasi_orders');
        Schema::dropIfExists('asset_loans');

        if (Schema::hasColumn('umkms', 'lokasi')) {
            Schema::table('umkms', function (Blueprint $table) {
                $table->dropColumn('lokasi');
            });
        }
    }
};
