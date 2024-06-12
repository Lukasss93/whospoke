<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function search(Request $request)
    {
        $search = $request->input('search');

        return User::query()
            ->where('username', 'like', "%$search%")
            ->orWhere('first_name', 'like', "%$search%")
            ->orWhere('last_name', 'like', "%$search%")
            ->limit(10)
            ->get();
    }
}
