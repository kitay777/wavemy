<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\CategoryTheme;
use App\Models\Like;

class Tweet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_theme_id',
        'text',
    ];

    // 投稿者とのリレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // カテゴリーとのリレーション（任意）
    public function category()
    {
        return $this->belongsTo(CategoryTheme::class, 'category_theme_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function likeCount()
    {
        return $this->likes()->where('type', 'like')->count();
    }

    public function dislikeCount()
    {
        return $this->likes()->where('type', 'dislike')->count();
    }

}
