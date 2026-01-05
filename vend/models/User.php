<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'nim',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

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
    return $this->hasMany(\App\Models\Message::class, 'sender_id');
}

public function messagesReceived()
{
    return $this->hasMany(\App\Models\Message::class, 'receiver_id');
}

}  
