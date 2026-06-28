<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
    Schema::create('rt_details', function (Blueprint $table) {
        $table->id();
        $table->string('nomor_rt');
        $table->string('nomor_rw');
        $table->string('nama_wilayah');
        $table->text('alamat_lengkap');
        $table->timestamps();
    });
    }
    public function down() {
        Schema::dropIfExists('rt_details');
    }
};
