<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    use HasFactory;

    protected $table = 'tagihans';

    protected $fillable = [
        'warga_id',
        'nama_warga',
        'jenis_tagihan',
        'periode',
        'jumlah',
        'status',       // belum_bayar | menunggu_verifikasi | lunas
        'metode_bayar', // manual | midtrans
        'bukti_bayar',
        'catatan',
        'tanggal_lunas',
        'batas_bayar',
    ];

    protected $casts = [
        'batas_bayar'   => 'date',
        'tanggal_lunas' => 'date',
        'jumlah'        => 'decimal:2',
    ];

    /** Relasi ke tabel wargas */
    public function warga()
    {
        return $this->belongsTo(\App\Models\Warga::class, 'warga_id');
    }

    /** Relasi ke tabel online_payments */
    public function onlinePayments()
    {
        return $this->hasMany(\Illuminate\Support\Facades\DB::table('online_payments'), 'tagihan_id');
    }

    /* ─── Helpers ─── */

    public function isLunas(): bool
    {
        return $this->status === 'lunas';
    }

    public function isMenungguVerifikasi(): bool
    {
        return $this->status === 'menunggu_verifikasi';
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'lunas'                => 'Lunas',
            'menunggu_verifikasi'  => 'Menunggu Verifikasi',
            default                => 'Belum Bayar',
        };
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'lunas'               => 'bg-green-100 text-green-700 border-green-200',
            'menunggu_verifikasi' => 'bg-amber-100 text-amber-700 border-amber-200',
            default               => 'bg-red-50 text-red-600 border-red-200',
        };
    }
}
