<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preorder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'price_id',
        'tanggal_preorder',
        'deskripsi',
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke harga produk (product_prices)
    public function price()
    {
        return $this->belongsTo(ProductPrice::class, 'price_id');
    }
}
