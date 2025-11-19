<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id', 'metode', 'amount', 'status', 'bukti'
    ];

    public function order(){
        return $this->belongsTo(Order::class);
    }
}

