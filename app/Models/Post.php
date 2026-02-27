<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // app/Models/Post.php

    protected $fillable = [
        'user_id',
        'category_id',
        'content',
        'goal',
        'status',
        'image'
    ];

    protected $appends = ['is_echoed'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // ⭐ Echoユーザー一覧
    public function echoedUsers()
    {
        return $this->belongsToMany(User::class, 'echoes')
            ->withTimestamps();
    }

    // ⭐ Echo数（countキャッシュ不要）
    public function getEchoCountAttribute()
    {
        return $this->echoedUsers()->count();
    }

    // ⭐ 自分がEcho済みか
    public function getIsEchoedAttribute()
    {
        if (!auth()->check()) return false;

        return $this->echoedUsers()
            ->where('user_id', auth()->id())
            ->exists();
    }
    public function product()
    {
        return $this->hasOne(Product::class);
    }
}
