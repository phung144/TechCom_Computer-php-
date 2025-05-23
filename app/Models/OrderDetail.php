<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'variant_id',
        'quantity',
        'price',
        'voucher_id',
        'address_id',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Quan hệ với Product (đơn giản)
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
        // Không thêm 'variants.options' ở đây
    }

    // Quan hệ với Variant
    public function variant()
{
    return $this->belongsTo(ProductVariant::class, 'variant_id')->with('options');
}
}
