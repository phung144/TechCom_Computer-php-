<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Chỉ định bảng tương ứng
    protected $table = 'categories';

    // Các cột có thể được gán giá trị hàng loạt
    protected $fillable = [
        'name',
        'description',
        'image',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
