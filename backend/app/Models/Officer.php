<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Officer extends Model
{
    use HasFactory;

    protected $table = 'officers';

    // Pastikan kolom ini sama persis dengan input name di file blade
    protected $fillable = [
        'warga_id',
        'jabatan',
        'tanggal_mulai',
        'tanggal_selesai',
        'status_aktif'
    ];

    // Relasi ke Model Warga
    public function warga()
    {
        return $this->belongsTo(Warga::class, 'warga_id');
    }
}
