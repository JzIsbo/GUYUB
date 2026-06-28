<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            // Gunakan user_id sebagai penghubung ke tabel users
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('action'); // Kolom untuk 'LOGIN SISTEM', dll
            $table->string('description')->nullable(); // Kolom untuk deskripsi aktivitas
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
