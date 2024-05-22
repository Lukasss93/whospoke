<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Nubs\RandomNameGenerator\Alliteration;

class AuthenticateGuest
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            $user = User::make([
                'id' => -hrtime(true),
                'first_name' => (new Alliteration())->getName(),
            ]);

            Auth::login($user);

            Inertia::share('auth.user', $user);
        }

        return $next($request);
    }
}
