<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('keterangan');
            $table->string('kategori')->default('Lain-lain');
            $table->enum('jenis', ['pemasukan', 'pengeluaran']); // Memisahkan uang masuk/keluar
            $table->decimal('nominal', 15, 2); // Mendukung angka hingga triliunan
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
