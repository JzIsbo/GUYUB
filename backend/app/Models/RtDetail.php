<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RtDetail extends Model
{
    use HasFactory;

    // Pastikan nama tabelnya sesuai dengan yang ada di database Anda
    protected $table = 'rt_details';

    // PENTING: Semua kolom ini WAJIB didaftarkan agar bisa disimpan oleh Controller
    protected $fillable = [
        'nomor_rt',
        'nomor_rw',
        'nama_wilayah',
        'alamat_lengkap'
    ];
}
