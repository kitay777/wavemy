<?php

namespace App\Http\Controllers;

use App\Models\ChatRoom;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;

class ChatRoomController extends Controller
{
    /**
     * チャットルームのメッセージ一覧を取得
     */
    public function index(ChatRoom $room)
    {
        $this->authorizeUser($room);

        return $room->messages()
            ->with('sender:id,name') // sender の名前だけ取得
            ->orderBy('id')
            ->get();
    }

    /**
     * 新しいメッセージを送信
     */
    public function store(Request $request, ChatRoom $room)
    {
        $this->authorizeUser($room);

        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = $room->messages()->create([
            'sender_id' => Auth::id(),
            'message' => $validated['message'], // ← ✅ テーブルのカラムに合わせる
        ]);


        broadcast(new MessageSent($message->load('sender')))->toOthers();

        return $message;
    }

    /**
     * 現在のユーザーがルームに所属しているかを確認
     */
    protected function authorizeUser(ChatRoom $room)
    {
        if (!$room->users()->where('users.id', auth()->id())->exists()) {
            abort(403, 'Unauthorized');
        }
    }

}
