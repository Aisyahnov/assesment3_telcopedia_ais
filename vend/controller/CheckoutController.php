<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = auth()->user()->cart; // ambil cart user
        $items = $cart->items;        // ambil item cart user

        $subtotal = $items->sum('subtotal');
        $adminFee = 2000;
        $discount = 0; 
        $total = $subtotal + $adminFee - $discount;

        return view('checkout.checkout', compact('items', 'subtotal', 'adminFee', 'discount', 'total'));
    }


    public function save(Request $req)
    {
        $req->validate([
            'address' => 'required',
            'payment_method' => 'required',
        ]);

        $cart = auth()->user()->cart;
        $items = $cart->items;

        // HITUNG ULANG TOTAL
        $subtotal = $items->sum('subtotal');
        $adminFee = 2000;
        $discount = 0; 
        $total = $subtotal + $adminFee - $discount;

        // SIMPAN ORDER
        $order = \App\Models\Order::create([
            'user_id' => auth()->id(),
            'address' => $req->address,
            'payment_method' => $req->payment_method,
            'voucher_code' => null,
            'total' => $total,
            'status' => 'pending',
        ]);

        // SIMPAN ORDER ITEM
        foreach ($items as $item) {
            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'subtotal' => $item->subtotal,
            ]);
        }

        // KOSONGKAN CART
        $cart->items()->delete();

        return redirect()->route('payment.upload', $order->id)
                        ->with('success', 'Order berhasil dibuat, silakan upload bukti pembayaran.');
    }

    public function uploadForm($orderId)
        {
            $order = \App\Models\Order::findOrFail($orderId);
            return view('checkout.upload', compact('order'));
        }

        public function uploadSave(Request $req, $orderId)
        {
            $req->validate([
                'proof' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
            ]);

            $order = \App\Models\Order::findOrFail($orderId);

            // SIMPAN FILE
            $filename = time() . '_' . $req->file('proof')->getClientOriginalName();
            $req->file('proof')->move(public_path('uploads/payments'), $filename);

            // UPDATE ORDER
            $order->update([
                'proof' => $filename,
                'status' => 'waiting_approval',
            ]);

            return redirect()
                    ->route('landing')
                    ->with('success', 'Bukti pembayaran berhasil diupload!');
        }

}