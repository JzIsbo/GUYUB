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
        Schema::create('online_payments', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique(); // ID Transaksi unik dari sistem/Midtrans
            $table->string('nama_pembayar');      // Nama warga yang membayar
            $table->string('metode_pembayaran')->nullable(); // BCA VA, Mandiri VA, QRIS, dll
            $table->decimal('nominal', 15, 2);    // Jumlah nominal uang (mendukung pecahan desimal)
            $table->string('status')->default('pending'); // pending, settlement (berhasil), expire, cancel
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('online_payments');
    }
};
