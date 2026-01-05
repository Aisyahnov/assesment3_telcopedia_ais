<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Product::select('category')->distinct()->pluck('category');
        $products   = Product::all();

        return view('home.landingpage', compact('categories', 'products'));
    }

    public function showProduct($id)
    {
        $product = Product::findOrFail($id);
        $seller  = $product->seller ?? null; // kalau sudah ada seller_id

        return view('home.product_detail', compact('product', 'seller'));
    }
}
