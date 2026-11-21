<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RfidScanSession extends Model
{
    protected $fillable = [
        'user_id',
        'token',
        'scanned_uid',
        'status',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isWaiting(): bool
    {
        return $this->status === 'waiting' && !$this->isExpired();
    }
}
