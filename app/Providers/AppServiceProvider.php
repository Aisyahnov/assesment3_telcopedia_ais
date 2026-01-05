<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// 1. IMPORT Gate dan Model/Policy
use Illuminate\Support\Facades\Gate;
use App\Models\Cart;
use App\Policies\CartPolicy;
use App\Models\Chat;
use App\Policies\ChatPolicy;
use App\Models\ChatMessage;
use App\Policies\ChatMessagePolicy;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(\App\Models\Order::class, \App\Policies\OrderPolicy::class);
        Gate::policy(\App\Models\Cart::class, \App\Policies\CartPolicy::class);
        Gate::policy(\App\Models\Chat::class, \App\Policies\ChatPolicy::class);
        Gate::policy(\App\Models\ChatMessage::class, \App\Policies\ChatMessagePolicy::class);
    }
}