<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [

        'invoice_id',
        'nama',
        'email',
        'jenis',
        'jumlah',
        'payment_type',
        'transaction_id',
        'snap_token',
        'redirect_url',
        'status'

    ];
}
