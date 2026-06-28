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
    Schema::create('payments', function (Blueprint $table) {

        $table->id();

        $table->string('invoice_id');

        $table->string('nama');

        $table->string('email')->nullable();

        $table->string('jenis');

        $table->bigInteger('jumlah');

        $table->string('payment_type')->nullable();

        $table->string('transaction_id')->nullable();

        $table->string('snap_token')->nullable();

        $table->string('redirect_url')->nullable();

        $table->enum('status', [
            'pending',
            'success',
            'failed'
        ])->default('pending');

        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
