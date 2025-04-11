<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariantOption extends Model
{
    protected $fillable = ['variant_id', 'value'];

    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }
}
