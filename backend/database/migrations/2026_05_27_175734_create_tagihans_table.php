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
       Schema::create('tagihans', function (Blueprint $table) {
        $table->id();
        $table->string('nama_warga');
        $table->string('jenis_tagihan');
        $table->decimal('jumlah', 15, 2);
        $table->string('status')->default('pending'); // pending, berhasil, dll
        $table->date('batas_bayar')->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihans');
    }
};
