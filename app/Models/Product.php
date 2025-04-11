<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Chỉ định bảng tương ứng
    protected $table = 'products';

    // Các cột có thể được gán giá trị hàng loạt
    protected $fillable = [
        'name',
        'category_id',
        'quantity',
        'sales',
        'description',
        'image',
        'price',
        'discount_start',
        'discount_end',
        'discount_type',
        'discount_value',
    ];

    // Định nghĩa mối quan hệ với bảng categories
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Định nghĩa mối quan hệ với bảng orders
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_product')
                    ->withPivot('quantity', 'price');
    }

    public function variants()
{
    return $this->hasMany(ProductVariant::class);
}
    // Chuyển đổi các trường thành datetime
    protected $casts = [
        'discount_start' => 'datetime',
        'discount_end' => 'datetime',
    ];
}
