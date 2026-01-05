<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        $userId = Auth::id(); 

        $cart = Cart::firstOrCreate(['user_id' => $userId]);

        $items = CartItem::where('cart_id', $cart->id)
            ->with('product')
            ->get();

        $subtotal = $items->sum('subtotal');
        $adminFee = 2000;

        $discount = ($cart->voucher_code === 'DISKON10')
                    ? $subtotal * 0.10
                    : 0;

        $total = $subtotal + $adminFee - $discount;

        $cart->update(['total' => $total]);

        return view('cart.cart', compact('items', 'subtotal', 'adminFee', 'discount', 'total'));
    }

    // TAMBAH PRODUK KE KERANJANG
    public function add(Request $req)
    {
        $userId = Auth::id(); 
        $cart = Cart::firstOrCreate(['user_id' => $userId]);

        $product = Product::findOrFail($req->product_id);

        // cek apakah item sudah ada
        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        if (!$item) {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => 1,
                'subtotal' => $product->price,
            ]);
        } else {
            $item->update([
                'quantity' => $item->quantity + 1,
                'subtotal' => ($item->quantity + 1) * $product->price,
            ]);
        }

        // PERUBAHAN DI SINI
        return redirect()->route('cart.index')
                        ->with('success', 'Produk ditambahkan ke keranjang!');
    }

    // UPDATE QTY
    public function update(Request $req)
    {
        $item = CartItem::findOrFail($req->item_id);

        $item->update([
            'quantity' => $req->quantity,
            'subtotal' => $req->quantity * $item->product->price,
        ]);

        return back()->with('success', 'Quantity berhasil diperbarui.');
    }

    // HAPUS ITEM
    public function remove(Request $req)
    {
        CartItem::where('id', $req->item_id)->delete();
        return back()->with('success', 'Produk dihapus dari keranjang!');
    }

    // APPLY VOUCHER
    public function applyCoupon(Request $req)
    {
        $userId = Auth::id(); 
        $cart = Cart::firstOrCreate(['user_id' => $userId]);

        if ($req->coupon_code === 'DISKON10') {
            $cart->update(['voucher_code' => 'DISKON10']);
            return back()->with('success', 'Kupon berhasil diterapkan!');
        }

        return back()->with('error', 'Kupon tidak valid.');
    }
   
}

