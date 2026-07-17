<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    // Menghubungkan model ke tabel 'devices'
    protected $table = 'devices';

    protected $fillable = [
        'nama_perangkat',
        'jenis_perangkat',
        'kondisi',
        'nomor_serial',
        'keterangan',
        'jumlah',
    ];
}
