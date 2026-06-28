<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('contributions', function (Blueprint $table) {
            $table->id();
            $table->string('nama_iuran');
            $table->string('periode_penagihan'); // Misal: Per Bulan, Kondisional, Per Tahun
            $table->enum('sifat', ['Wajib', 'Sukarela']);
            $table->decimal('nominal', 15, 2);
            $table->string('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('contributions');
    }
};
