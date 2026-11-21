<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rfid_scan_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('token')->unique()->comment('UUID token sesi registrasi');
            $table->string('scanned_uid')->nullable()->comment('UID kartu yang terbaca ESP32');
            $table->enum('status', ['waiting', 'completed', 'expired'])->default('waiting');
            $table->timestamp('expires_at')->comment('Sesi expired otomatis setelah 5 menit');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rfid_scan_sessions');
    }
};
