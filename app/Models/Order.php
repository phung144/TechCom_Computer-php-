<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total',
        'full_name',
        'email',
        'address',
        'phone',
        'status',
        'total_after_discount',
        'payment_method',
    ];

    // Quan hệ chính với OrderDetail
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    // Quan hệ với User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Có thể thêm phương thức helper nếu cần
    public function getProductNamesAttribute()
    {
        return $this->orderDetails->map(function($detail) {
            return $detail->product->name;
        })->implode(', ');
    }
}
