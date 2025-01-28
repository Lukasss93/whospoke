<?php

namespace App\Http\Controllers;

use App\Models\Profession;
use Illuminate\Http\Request;

class ProfessionsController extends Controller
{
    public function search(Request $request)
    {
        $search = $request->input('search');

        return Profession::query()
            ->where('name', 'like', "%$search%")
            ->orWhere('abbreviation', 'like', "%$search%")
            ->limit(10)
            ->get();
    }
}
