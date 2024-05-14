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
            $user = User::factory()->make();

            Auth::login($user);

            Inertia::share('auth.user', $user);
        }

        return $next($request);
    }
}
