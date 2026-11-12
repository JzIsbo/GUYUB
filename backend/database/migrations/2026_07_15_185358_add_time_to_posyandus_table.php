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
        if (!Schema::hasTable('posyandus')) {
            Schema::create('posyandus', function (Blueprint $table) {
                $table->id();
                $table->string('nama_kegiatan');
                $table->string('target_peserta')->default('Balita & Lansia');
                $table->date('tanggal');
                $table->time('waktu_mulai')->nullable();
                $table->time('waktu_selesai')->nullable();
                $table->string('lokasi');
                $table->text('keterangan')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('posyandus', function (Blueprint $table) {
                if (!Schema::hasColumn('posyandus', 'waktu_mulai')) {
                    $table->time('waktu_mulai')->nullable()->after('tanggal');
                }
                if (!Schema::hasColumn('posyandus', 'waktu_selesai')) {
                    $table->time('waktu_selesai')->nullable()->after('waktu_mulai');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('posyandus')) {
            Schema::table('posyandus', function (Blueprint $table) {
                if (Schema::hasColumn('posyandus', 'waktu_mulai')) {
                    $table->dropColumn('waktu_mulai');
                }
                if (Schema::hasColumn('posyandus', 'waktu_selesai')) {
                    $table->dropColumn('waktu_selesai');
                }
            });
        }
    }
};
