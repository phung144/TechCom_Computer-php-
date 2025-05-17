<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'discount_type',
        'discount_value',
        'min_order_value',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $dates = [
        'start_date',
        'end_date'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function isValid()
    {
        return $this->is_active &&
               now()->between($this->start_date, $this->end_date);
    }
}
