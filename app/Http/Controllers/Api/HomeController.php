<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function getAllDataProduk()
    {
        return response()->json([
            'success' => true,
            'data' => \App\Models\Product::all()
        ]);
    }

    public function getProdukById($id)
    {
        $product = \App\Models\Product::find($id);
        if (!$product) return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        
        return response()->json(['success' => true, 'data' => $product]);
    }
}
