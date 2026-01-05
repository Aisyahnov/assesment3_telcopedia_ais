<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Untuk Policy

class CheckoutController extends Controller
{
    use AuthorizesRequests;

    public function saveOrder(Request $req)
    {
        $cart = auth()->user()->cart;

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['message' => 'Keranjang kosong'], 400);
        }

        $order = DB::transaction(function () use ($req, $cart) {
            // 1. Buat Header Order
            $order = \App\Models\Order::create([
                'user_id' => auth()->id(),
                'address' => $req->address,
                'payment_method' => $req->payment_method,
                'total' => $cart->total,
                'status' => 'pending'
            ]);

            // 2. Pindahkan item dari Cart ke OrderItem (WAJIB ADA)
            foreach ($cart->items as $item) {
                \App\Models\OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->subtotal,
                ]);
            }

            // 3. Kosongkan keranjang
            $cart->items()->delete();
            $cart->update(['total' => 0, 'voucher_code' => null]);

            return $order;
        });

        return response()->json(['message' => 'Checkout berhasil', 'order' => $order], 201);
    }

    public function uploadBukti(Request $req, $orderId)
    {
        $req->validate(['proof' => 'required|image|max:2048']); 

        $order = \App\Models\Order::findOrFail($orderId);
        $this->authorize('update', $order); // Pastikan Policy sudah didaftarkan

        $filename = time() . '.' . $req->file('proof')->getClientOriginalExtension();
        $req->file('proof')->move(public_path('uploads'), $filename);
        
        $order->update(['proof' => $filename, 'status' => 'waiting_approval']);
        
        return response()->json(['message' => 'Bukti diupload, menunggu persetujuan']);
    }
}