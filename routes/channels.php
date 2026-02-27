<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

Broadcast::channel('chat-room.{roomId}', function ($user, $roomId) {
    return \App\Models\ChatRoom::find($roomId)?->users()->where('id', $user->id)->exists();
});

