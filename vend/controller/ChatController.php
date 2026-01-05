<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function start($productId)
    {
        $product = Product::findOrFail($productId);

        $buyerId = Auth::id();
        $sellerId = $product->user_id;

        // Cek apakah chat sudah ada
        $chat = Chat::where('buyer_id', $buyerId)
                    ->where('seller_id', $sellerId)
                    ->where('product_id', $productId)
                    ->first();

        if (!$chat) {
            $chat = Chat::create([
                'buyer_id' => $buyerId,
                'seller_id' => $sellerId,
                'product_id' => $productId,
            ]);
        }

        return redirect()->route('chat.room', $chat->id);
    }

    public function room($chatId)
    {
        $chat = Chat::with(['messages.sender', 'product'])->findOrFail($chatId);
        return view('chat.room', compact('chat'));
    }

    public function send(Request $req, $chatId)
    {
        $req->validate([
            'message' => 'required'
        ]);

        ChatMessage::create([
            'chat_id' => $chatId,
            'sender_id' => Auth::id(),
            'message' => $req->message,
        ]);

        return back();
    }
    
    public function updateMessage(Request $req, $chatId, $msgId)
    {
        $msg = ChatMessage::findOrFail($msgId);

        if ($msg->sender_id !== Auth::id()) {
            return back()->with('error', 'You cannot edit this message.');
        }

        $req->validate([
            'message' => 'required'
        ]);

        $msg->update([
            'message' => $req->message
        ]);

        return back()->with('success', 'Message updated.');
    }

    public function deleteMessage($chatId, $msgId)
        {
            $msg = ChatMessage::findOrFail($msgId);

            if ($msg->sender_id !== Auth::id()) {
                return back()->with('error', 'You cannot delete this message.');
            }

            $msg->delete();

            return back()->with('success', 'Message deleted.');
        }

}

