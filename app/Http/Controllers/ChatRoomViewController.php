<?php

namespace App\Http\Controllers;

use App\Models\ChatRoom;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ChatRoomViewController extends Controller
{
    public function show(ChatRoom $room)
    {
        if (!$room->users()->where('users.id', Auth::id())->exists()) {
            abort(403);
        }

        return Inertia::render('ChatRoomPage', [
            'room' => $room,
            'currentUser' => Auth::user(),
        ]);
}

}