<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ChatController extends Controller
{
    use AuthorizesRequests;

    public function send(Request $req)
    {
        $req->validate([
            'seller_id' => 'required',
            'product_id' => 'required',
            'message' => 'required|string'
        ]);

        $buyerId = auth()->id();

        // Cek apakah sudah pernah ada obrolan untuk produk & penjual ini
        $chat = Chat::where('buyer_id', $buyerId)
                    ->where('seller_id', $req->seller_id)
                    ->where('product_id', $req->product_id)
                    ->first();

        // Jika belum ada, buat room baru otomatis di tabel chats
        if (!$chat) {
            $chat = Chat::create([
                'buyer_id' => $buyerId,
                'seller_id' => $req->seller_id,
                'product_id' => $req->product_id
            ]);
        }

        // Simpan pesan ke tabel chat_messages
        $msg = ChatMessage::create([
            'chat_id' => $chat->id,
            'sender_id' => $buyerId,
            'message' => $req->message
        ]);

        return response()->json([
            'message' => 'Pesan terkirim',
            'chat_id' => $chat->id,
            'data' => $msg
        ], 201);
    }

    public function room(Chat $chat)
    {
        $this->authorize('view', $chat);

        // Ambil semua pesan untuk room ini
        $messages = ChatMessage::where('chat_id', $chat->id)->get();

        return response()->json([
            'room_info' => $chat,
            'messages' => $messages
        ]);
    }

    public function updateMessage(Request $req, ChatMessage $message)
    {
        $this->authorize('update', $message);
        $req->validate(['message' => 'required|string']);
        
        $message->update(['message' => $req->message]);
        return response()->json(['message' => 'Pesan diperbarui']);
    }

    public function deleteMessage(ChatMessage $message)
    {
        $this->authorize('delete', $message);
        $message->delete();
        return response()->json(['message' => 'Pesan dihapus']);
    }
}