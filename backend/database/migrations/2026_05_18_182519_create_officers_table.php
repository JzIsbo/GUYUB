<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('officers', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel wargas (foreign key)
            $table->foreignId('warga_id')->constrained('wargas')->onDelete('cascade');
            $table->string('jabatan'); // Misal: Ketua RT, Sekretaris, Bendahara
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->enum('status_aktif', ['Aktif', 'Demisioner'])->default('Aktif');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('officers');
    }
};
