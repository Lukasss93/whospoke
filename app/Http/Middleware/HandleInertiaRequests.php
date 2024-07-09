<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
                'botUsername' => config('nutgram.config.bot_name'),
                'modes' => [
                    'telegram' => config('auth.modes.telegram'),
                    'slack' => config('auth.modes.slack'),
                ],
            ],
            'app' => [
                'name' => config('app.name'),
                'version' => config('app.version'),
                'isLocal' => app()->isLocal(),
            ],
            'developer' => [
                'name' => config('developer.name'),
                'github' => config('developer.github'),
            ],
        ];
    }
}
