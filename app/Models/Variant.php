<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    protected $fillable = ['name'];

    public function options()
    {
        return $this->hasMany(VariantOption::class);
    }
}
