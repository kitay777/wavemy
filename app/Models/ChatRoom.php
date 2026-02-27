<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    protected $fillable = ['name'];

    /**
     * このチャットルームに属するユーザーたち（中間テーブル: chat_room_users）
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'chat_room_users', 'chat_room_id', 'user_id');
    }

    /**
     * このチャットルームに属するメッセージたち
     */
    public function messages()
    {
        return $this->hasMany(Message::class, 'room_id'); // ←ここも 'room_id' に合わせる
    }
}
