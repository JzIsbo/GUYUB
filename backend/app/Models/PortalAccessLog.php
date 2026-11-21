<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortalAccessLog extends Model
{
    protected $fillable = [
        'user_id',
        'rfid_uid',
        'status',
        'device_id',
        'message',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
