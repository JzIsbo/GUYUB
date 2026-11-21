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
        // Add jumlah to devices
        if (Schema::hasTable('devices')) {
            Schema::table('devices', function (Blueprint $table) {
                if (!Schema::hasColumn('devices', 'jumlah')) {
                    $table->integer('jumlah')->default(1)->after('kondisi');
                }
            });
        }

        // Add satuan, deskripsi, status to koperasi_items
        if (Schema::hasTable('koperasi_items')) {
            Schema::table('koperasi_items', function (Blueprint $table) {
                if (!Schema::hasColumn('koperasi_items', 'satuan')) {
                    $table->string('satuan', 50)->default('pcs')->after('stok');
                }
                if (!Schema::hasColumn('koperasi_items', 'deskripsi')) {
                    $table->text('deskripsi')->nullable()->after('kategori');
                }
                if (!Schema::hasColumn('koperasi_items', 'status')) {
                    $table->string('status', 50)->default('Tersedia')->after('foto');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('devices')) {
            Schema::table('devices', function (Blueprint $table) {
                if (Schema::hasColumn('devices', 'jumlah')) {
                    $table->dropColumn('jumlah');
                }
            });
        }

        if (Schema::hasTable('koperasi_items')) {
            Schema::table('koperasi_items', function (Blueprint $table) {
                $cols = [];
                if (Schema::hasColumn('koperasi_items', 'satuan')) $cols[] = 'satuan';
                if (Schema::hasColumn('koperasi_items', 'deskripsi')) $cols[] = 'deskripsi';
                if (Schema::hasColumn('koperasi_items', 'status')) $cols[] = 'status';
                if (!empty($cols)) {
                    $table->dropColumn($cols);
                }
            });
        }
    }
};
