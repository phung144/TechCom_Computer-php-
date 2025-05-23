<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $table = "comment";
    protected $fillable = [
        'user_id',
        'product_id',
        'comment',
        'parent_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    // Bình luận cha
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    // Các bình luận con (rep comment)
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}
