<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    use AuthorizesRequests;

    // Fungsi pembantu untuk menghitung ulang total keranjang
    private function refreshCartTotal($cart)
    {
        $subtotalItems = CartItem::where('cart_id', $cart->id)->sum('subtotal');
        $adminFee = 2000;
        
        $discount = ($cart->voucher_code === 'DISKON10') ? ($subtotalItems * 0.10) : 0;
        
        $totalAkhir = $subtotalItems + $adminFee - $discount;

        $cart->update(['total' => $totalAkhir]);
    }

    public function index()
    {
        $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);
        
        // Pastikan total dihitung ulang saat melihat keranjang
        $this->refreshCartTotal($cart);

        $items = CartItem::where('cart_id', $cart->id)->with('product')->get();
        
        return response()->json([
            'cart_details' => $cart,
            'items' => $items,
            'summary' => [
                'admin_fee' => 2000,
                'discount_applied' => ($cart->voucher_code === 'DISKON10') ? '10%' : '0%'
            ]
        ]);
    }

    public function addToCart(Request $req)
    {
        $req->validate(['product_id' => 'required|exists:products,id']);
        
        $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);
        $product = Product::findOrFail($req->product_id);

        $item = CartItem::where('cart_id', $cart->id)
                         ->where('product_id', $product->id)
                         ->first();

        if ($item) {
            $newQty = $item->quantity + 1;
            $item->update([
                'quantity' => $newQty,
                'subtotal' => $newQty * $product->price
            ]);
        } else {
            $item = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => 1,
                'subtotal' => $product->price
            ]);
        }

        $this->refreshCartTotal($cart);

        return response()->json(['message' => 'Berhasil ditambah', 'data' => $item]);
    }

    public function updateQty(Request $req)
    {
        $req->validate([
            'item_id' => 'required|exists:cart_items,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $item = CartItem::findOrFail($req->item_id);
        
        // Policy: Pastikan user hanya bisa edit keranjang miliknya
        $this->authorize('update', $item->cart); 
        
        $item->update([
            'quantity' => $req->quantity,
            'subtotal' => $req->quantity * $item->product->price
        ]);

        $this->refreshCartTotal($item->cart);

        return response()->json(['message' => 'Quantity dan Total diupdate']);
    }

    public function applyVoucher(Request $req)
    {
        $req->validate(['voucher_code' => 'required|string']);
        
        $cart = Cart::where('user_id', auth()->id())->firstOrFail();
        
        $cart->update(['voucher_code' => $req->voucher_code]);
        
        $this->refreshCartTotal($cart);

        $cart->refresh(); 

        return response()->json([
            'message' => 'Voucher berhasil dipasang', 
            'voucher_terpasang' => $cart->voucher_code,
            'total_baru' => $cart->total 
        ]);
    }

    public function remove(Request $req)
    {
        $req->validate(['item_id' => 'required|exists:cart_items,id']);
        
        $item = CartItem::findOrFail($req->item_id);
        $cart = $item->cart;

        // Policy
        $this->authorize('delete', $cart); 
        
        $item->delete();

        $this->refreshCartTotal($cart);

        return response()->json(['message' => 'Item dihapus dan total diperbarui']);
    }
}