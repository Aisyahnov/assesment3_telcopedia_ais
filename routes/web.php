<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ChatController;

    # Home / Landing Page
    Route::get('/', [HomeController::class, 'index'])->name('landing');

    # Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login.show');
    Route::post('/login', [AuthController::class, 'doLogin'])->name('login.do');

    # Register
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register.show');
    Route::post('/register', [AuthController::class, 'doRegister'])->name('register.do');

    # Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    #Page Product Detail
    Route::get('/product/{id}', [HomeController::class, 'showProduct'])->name('product.show');

Route::middleware('auth')->group(function () {

    # Cart
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('cart.index');
        Route::post('/add', [CartController::class, 'add'])->name('cart.add');
        Route::post('/update', [CartController::class, 'update'])->name('cart.update');
        Route::post('/remove', [CartController::class, 'remove'])->name('cart.remove');
        Route::post('/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.applyCoupon');
    });

    # Checkout
    Route::prefix('checkout')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/save', [CheckoutController::class, 'save'])->name('checkout.save');
    });

    # Upload bukti pembayaran
    Route::get('/checkout/upload/{order}', [CheckoutController::class, 'uploadForm'])->name('payment.upload');
    Route::post('/checkout/upload/{order}', [CheckoutController::class, 'uploadSave'])->name('payment.upload.save');

    # Chat
    Route::get('/chat/start/{product}', [ChatController::class, 'start'])->name('chat.start');
    Route::get('/chat/{chat}', [ChatController::class, 'room'])->name('chat.room');
    Route::post('/chat/{chat}/send', [ChatController::class, 'send'])->name('chat.send');
    Route::put('/chat/{chat}/message/{message}/update', [ChatController::class, 'updateMessage'])->name('chat.message.update');
    Route::delete('/chat/{chat}/message/{message}/delete', [ChatController::class, 'deleteMessage'])->name('chat.message.delete');

});

