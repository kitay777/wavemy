<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatController extends Controller
{
    //
    public function send(Request $request, Room $room)
    {
        $request->validate([
            'message' => 'required|string|max:2048',
        ]);

        // ルーム所属チェック
        if (!auth()->user()->rooms->contains($room)) {
            abort(403);
        }

        $message = $room->messages()->create([
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json(['status' => 'ok']);
    }
    public function history(Room $room)
    {
        if (!auth()->user()->rooms->contains($room)) {
            abort(403);
        }

        $messages = $room->messages()->with('user')->latest()->take(50)->get()->reverse()->values();

        return response()->json([
            'messages' => $messages,
        ]);
    }

    public function store(Request $request, ChatRoom $chatRoom)
    {
        $message = $chatRoom->messages()->create([
            'chat_room_id' => $chatRoom->id,
            'sender_id' => auth()->id(),
            'text' => $request->message,
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json($message);
    }



}
