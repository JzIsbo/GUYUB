<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warga extends Model
{
    use HasFactory;

    // Menentukan nama tabel (opsional jika sudah jamak 'wargas', tapi untuk memastikan)
    protected $table = 'wargas';

    // Menentukan kolom yang boleh diisi (mass assignable)
    protected $fillable = [
        'nomor_kk',
        'nik',
        'nama_lengkap',
        'jenis_kelamin',
        'umur',
        'agama',
        'no_telepon',
        'blok_rumah',
        'status_keluarga',
        'status_domisili',
    ];
}
