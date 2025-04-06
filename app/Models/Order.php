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
        'status', // Ensure this is included
        'payment_method',
    ];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function products()
    {
        return $this->hasManyThrough(
            Product::class,
            OrderDetail::class,
            'order_id', // Foreign key on OrderDetail table
            'id',       // Foreign key on Product table
            'id',       // Local key on Order table
            'product_id' // Local key on OrderDetail table
        );
    }
}
