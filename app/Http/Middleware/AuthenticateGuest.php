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
            [$firstName, $lastName] = explode(' ', ucwords(Petname::Generate(2, ' ')));

            $user = User::make([
                'id' => -hrtime(true),
                'first_name' => $firstName,
                'last_name' => $lastName,
            ]);

            Auth::login($user);

            Inertia::share('auth.user', $user);
        }

        return $next($request);
    }
}
