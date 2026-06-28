<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    use HasFactory;

    // Menentukan nama tabel jika tidak mengikuti konvensi jamak (opsional)
    protected $table = 'tagihans';

    // Daftar field yang boleh diisi melalui request
    protected $fillable = [
        'nama_warga',
        'jenis_tagihan',
        'jumlah',
        'status',
        'batas_bayar'
    ];

}
