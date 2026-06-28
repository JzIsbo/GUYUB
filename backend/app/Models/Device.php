<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    // Menghubungkan model ke tabel 'devices'
    protected $table = 'devices';

    // Kolom yang diizinkan untuk diisi data dari form
    protected $fillable = [
        'nama_perangkat',
        'jenis_perangkat', // <-- Wajib sama dengan input name di Blade
        'kondisi',
    ];
}
