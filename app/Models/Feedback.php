<?php

namespace App\Models;

// app/Models/Feedback.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedbacks'; // Tên bảng trong cơ sở dữ liệu

    protected $fillable = [
        'user_id',
        'product_id',
        'image',
        'content',
        'rating',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    // Quan hệ với User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Quan hệ với Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Scope cho đánh giá cao (4-5 sao)
    public function scopeHighRating($query)
    {
        return $query->where('rating', '>=', 4);
    }

    // Scope tìm theo sản phẩm
    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }
}
