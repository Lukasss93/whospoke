<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use LCherone\PHPPetname as Petname;

class AuthenticateGuest
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            $user = User::make([
                'id' => -hrtime(true),
                'name' => ucwords(Petname::Generate(2, ' ')),
            ]);

            Auth::login($user);

            Inertia::share('auth.user', $user);
        }

        return $next($request);
    }
}
