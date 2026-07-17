<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah kolom yang hilang di tagihans
        Schema::table('tagihans', function (Blueprint $table) {
            if (!Schema::hasColumn('tagihans', 'warga_id')) {
                $table->unsignedBigInteger('warga_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('tagihans', 'periode')) {
                $table->string('periode')->nullable()->after('jenis_tagihan');
            }
            if (!Schema::hasColumn('tagihans', 'metode_bayar')) {
                $table->string('metode_bayar')->nullable()->after('jumlah');
            }
            if (!Schema::hasColumn('tagihans', 'bukti_bayar')) {
                $table->string('bukti_bayar')->nullable()->after('metode_bayar');
            }
            if (!Schema::hasColumn('tagihans', 'catatan')) {
                $table->text('catatan')->nullable()->after('bukti_bayar');
            }
            if (!Schema::hasColumn('tagihans', 'tanggal_lunas')) {
                $table->date('tanggal_lunas')->nullable()->after('catatan');
            }
        });

        // 2. Standarisasi status yang ada (menunggu → belum_bayar, berhasil → lunas, menunggu konfirmasi → menunggu_verifikasi)
        DB::statement("UPDATE tagihans SET status = 'belum_bayar' WHERE status IN ('menunggu', 'pending', 'Menunggu')");
        DB::statement("UPDATE tagihans SET status = 'lunas' WHERE status IN ('berhasil', 'Berhasil', 'settlement', 'success')");
        DB::statement("UPDATE tagihans SET status = 'menunggu_verifikasi' WHERE status IN ('menunggu konfirmasi', 'Menunggu Konfirmasi')");

        // 3. Tambah tagihan_id ke online_payments jika belum ada
        if (!Schema::hasColumn('online_payments', 'tagihan_id')) {
            Schema::table('online_payments', function (Blueprint $table) {
                $table->unsignedBigInteger('tagihan_id')->nullable()->after('id');
            });
        }

        // 4. Drop tabel yang tidak dipakai
        Schema::dropIfExists('contributions_payment');
        Schema::dropIfExists('payments');
    }

    public function down(): void
    {
        // Kembalikan kalau di-rollback
        Schema::table('tagihans', function (Blueprint $table) {
            $table->dropColumn(['warga_id', 'periode', 'metode_bayar', 'bukti_bayar', 'catatan', 'tanggal_lunas']);
        });
        Schema::table('online_payments', function (Blueprint $table) {
            $table->dropColumn('tagihan_id');
        });
    }
};
