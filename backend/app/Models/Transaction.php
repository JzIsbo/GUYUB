<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    // Mengizinkan penyimpanan massal
    protected $fillable = [
        'tanggal',
        'keterangan',
        'kategori',
        'jenis',
        'nominal'
    ];
}
