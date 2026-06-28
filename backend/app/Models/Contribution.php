<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contribution extends Model {
    use HasFactory;

    protected $table = 'contributions';
    protected $fillable = ['nama_iuran', 'periode_penagihan', 'sifat', 'nominal', 'deskripsi'];
}
