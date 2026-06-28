<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('wargas', function (Blueprint $table) {
    $table->id();
    $table->string('nik')->unique();
    $table->string('nama_lengkap');
    $table->string('no_telepon', 20)->nullable();
    $table->string('blok_rumah'); // <--- PASTIKAN TULISANNYA BLOK_RUMAH
    $table->enum('status_keluarga', ['Kepala Keluarga', 'Istri', 'Anak', 'Lainnya']);
    $table->enum('status_domisili', ['Tetap', 'Kontrak', 'Kos']);
    $table->timestamps();
    });
    }
    public function down() {
        Schema::dropIfExists('wargas');
    }
};
