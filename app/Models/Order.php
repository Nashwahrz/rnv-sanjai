<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'total_amount', 'status', 'refund_amount', 'bukti_admin', 'alamat', 'no_hp',
    ];

    public function payments(){
        return $this->hasMany(Payment::class);
    }
    public function items()
{
    return $this->hasMany(OrderItem::class, 'order_id');
}
public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}

}

