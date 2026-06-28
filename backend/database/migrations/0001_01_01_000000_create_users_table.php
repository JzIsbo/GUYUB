<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // --- TAMBAHKAN BARIS INI UNTUK AVATAR ---
            $table->string('photo')->nullable();

            // (Pastikan kolom role dan status yang Anda buat sebelumnya tetap ada di sini)
            $table->enum('role', ['Super Admin', 'RT', 'Bendahara', 'Warga'])->default('Warga');
            $table->enum('status', ['Aktif', 'Nonaktif'])->default('Aktif');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
