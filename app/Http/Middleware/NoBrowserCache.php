<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class NoBrowserCache
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $response->withHeaders([
            'Cache-Control' => 'no-store, no-cache, max-age=0, must-revalidate, private',
            'Expires' => now()->format('D, d M Y H:i:s T'),
        ]);

        return $response;
    }
}
