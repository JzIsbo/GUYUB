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
    Schema::create('contributions_payment', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('warga_id');
        $table->unsignedBigInteger('iuran_id');
        $table->decimal('nominal_bayar', 15, 2);
        $table->date('tanggal_bayar');
        $table->timestamps();

        // Menambahkan foreign key (opsional, tapi disarankan)
        $table->foreign('warga_id')->references('id')->on('wargas');
        $table->foreign('iuran_id')->references('id')->on('contributions');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contributions_payment');
    }
};
