<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class LocalController extends Controller
{
    public function login(Request $request)
    {
        $redirectUrl = $request->input('redirect') ?: route('home');

        $user = User::firstOrCreate([
            'telegram_id' => 0,
        ], [
            'username' => 'LocalUser',
            'first_name' => 'Local',
            'last_name' => 'User',
        ]);

        auth()->login($user, true);

        return redirect()->to($redirectUrl);
    }
}
