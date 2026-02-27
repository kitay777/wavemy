<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'tweet_id',
        'user_id',
        'type', // 'like' または 'dislike'
    ];

    /**
     * 紐づくツイート
     */
    public function tweet()
    {
        return $this->belongsTo(Tweet::class);
    }

    /**
     * リアクションしたユーザー
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
