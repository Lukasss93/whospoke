<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AuthenticateGuest
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            $user = User::make([
                'id' => hrtime(true),
                'telegram_id' => 123456,
                'first_name' => 'Guest',
            ]);

            Auth::login($user);

            Inertia::share('auth.user', $user);
        }

        return $next($request);
    }
}
