<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'variant_id',
        'quantity',
        'price',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // Trong Cart.php
public function variant()
{
    return $this->belongsTo(ProductVariant::class, 'variant_id');
}

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


}
