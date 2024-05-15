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
            $id = hrtime(true);

            $user = User::make([
                'id' => -$id,
                'first_name' => 'Guest ' . $id,
            ]);

            Auth::login($user);

            Inertia::share('auth.user', $user);
        }

        return $next($request);
    }
}
