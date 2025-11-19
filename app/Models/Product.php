<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_produk',
        'jenis_produk',
        'stok',
        'deskripsi',
        'foto',
    ];

    public function prices()
{
    return $this->hasMany(ProductPrice::class);
}

}
