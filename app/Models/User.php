<?php

namespace App\Models;

// 1. TAMBAHKAN IMPORT INI
use Laravel\Sanctum\HasApiTokens; 
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    // 2. TAMBAHKAN HasApiTokens DI SINI
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'nim',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relasi yang sudah kamu buat sudah benar
    public function cart()
    {
        return $this->hasOne(\App\Models\Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(\App\Models\Order::class);
    }

    public function messagesSent()
    {
        return $this->hasMany(\App\Models\ChatMessage::class, 'sender_id');
    }

    public function messagesReceived()
    {
        return $this->hasMany(\App\Models\ChatMessage::class, 'receiver_id');
    }
}