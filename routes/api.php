<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\CheckoutController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/products', [HomeController::class, 'getAllDataProduk']);
Route::get('/products/{id}', [HomeController::class, 'getProdukById']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Cart
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add', [CartController::class, 'addToCart']);
    Route::put('/cart/update', [CartController::class, 'updateQty']);
    Route::post('/cart/voucher', [CartController::class, 'applyVoucher']);
    Route::delete('/cart/remove', [CartController::class, 'remove']);

    // Checkout
    Route::post('/checkout/save', [CheckoutController::class, 'saveOrder']);
    Route::post('/checkout/upload/{order}', [CheckoutController::class, 'uploadBukti']);

    // Chat
    Route::get('/chat/{chat}', [ChatController::class, 'room']);
    Route::post('/chat/send', [ChatController::class, 'send']);
    Route::put('/chat/message/{message}', [ChatController::class, 'updateMessage']);
    Route::delete('/chat/message/{message}', [ChatController::class, 'deleteMessage']);
});