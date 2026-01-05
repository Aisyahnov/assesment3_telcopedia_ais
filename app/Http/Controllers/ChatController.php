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

    /**
     * 1. LIHAT PESAN (GET /api/chat/{chat})
     * Menampilkan data dari tabel 'chats' dan list dari 'chat_messages'
     */
    public function room(Chat $chat)
    {
        // Policy mengecek buyer_id atau seller_id di tabel 'chats'
        $this->authorize('view', $chat);

        // Mencari pesan berdasarkan field 'chat_id'
        $messages = ChatMessage::where('chat_id', $chat->id)->get();

        return response()->json([
            'info_room' => $chat,   // field: id, buyer_id, seller_id, product_id
            'isi_pesan' => $messages // field: id, chat_id, sender_id, message
        ]);
    }

    /**
     * 2. KIRIM PESAN (POST /api/chat/{chat}/send)
     * Mengisi tabel 'chat_messages' yang kosong
     */
    public function send(Request $req, Chat $chat)
    {
        $this->authorize('view', $chat);

        $req->validate(['message' => 'required|string']);

        // Insert sesuai field: chat_id, sender_id, message
        $newMessage = ChatMessage::create([
            'chat_id'   => $chat->id,
            'sender_id' => auth()->id(), 
            'message'   => $req->message
        ]);

        return response()->json([
            'message' => 'Pesan terkirim!',
            'data'    => $newMessage
        ], 201);
    }

    /**
     * 3. EDIT PESAN (PUT /api/chat/message/{message})
     */
    public function updateMessage(Request $req, ChatMessage $message)
    {
        // Policy mengecek field 'sender_id'
        $this->authorize('update', $message);

        $req->validate(['message' => 'required|string']);
        $message->update(['message' => $req->message]);

        return response()->json(['message' => 'Pesan berhasil diubah']);
    }

    /**
     * 4. HAPUS PESAN (DELETE /api/chat/message/{message})
     */
    public function deleteMessage(ChatMessage $message)
    {
        $this->authorize('delete', $message);

        $message->delete();
        return response()->json(['message' => 'Pesan telah dihapus']);
    }
}