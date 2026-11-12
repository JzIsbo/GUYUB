<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',    // Tambahkan ini
        'status',  // Tambahkan ini
    ];

    // Membuka semua proteksi kolom agar input via array massal berjalan mulus
    protected $guarded = [];
}
