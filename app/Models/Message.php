<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    //
    protected $fillable = [
        'sender_id',
        'room_id',
        'message', // または 'message'：カラム名に合わせてください
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function room() {
        return $this->belongsTo(ChatRoom::class, 'room_id');
    }
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
