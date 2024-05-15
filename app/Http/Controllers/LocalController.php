<?php

namespace App\Http\Controllers;

use App\Models\User;

class LocalController extends Controller
{
    public function login()
    {
        $user = User::firstOrCreate([
            'telegram_id' => 0,
        ], [
            'username' => 'LocalUser',
            'first_name' => 'Local',
            'last_name' => 'User',
        ]);

        auth()->login($user, true);

        return redirect()->route('home');
    }
}
