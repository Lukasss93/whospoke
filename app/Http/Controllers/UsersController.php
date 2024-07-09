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
            ->where('name', 'like', "%$search%")
            ->limit(10)
            ->get();
    }
}
