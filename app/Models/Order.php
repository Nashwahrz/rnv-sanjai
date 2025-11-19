<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'total_amount', 'status', 'alamat', 'no_hp'
    ];

    public function payments(){
        return $this->hasMany(Payment::class);
    }
}

