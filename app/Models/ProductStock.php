<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    use HasFactory;

    protected $table = 'product_stocks';
    protected $fillable = [
        'product_id', // ID sản phẩm
        'variant', // Tên biến thể
        'quantity', // Số lượng
        'price', // Giá
    ];

    public function product()
    {
        return $this->belongsTo(Product::class); // Quan hệ ngược với Product
    }
}
