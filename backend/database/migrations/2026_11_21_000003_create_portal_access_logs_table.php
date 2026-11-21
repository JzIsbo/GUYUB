<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portal_access_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('rfid_uid')->comment('UID kartu yang di-scan saat akses portal');
            $table->enum('status', ['granted', 'denied'])->default('denied');
            $table->string('device_id')->nullable()->comment('ID perangkat ESP32 (misal: gate-01)');
            $table->string('message')->nullable()->comment('Pesan hasil akses');
            $table->timestamps();

            $table->index('rfid_uid');
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portal_access_logs');
    }
};
