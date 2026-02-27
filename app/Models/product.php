<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/Product.php

class Product extends Model
{
    protected $fillable = [
        'post_id',
        'category_id',
        'name',
        'brand',
        'price',
        'image',
        'status'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}