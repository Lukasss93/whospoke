<?php

use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use SergiX44\Nutgram\Exception\InvalidDataException;
use SergiX44\Nutgram\Nutgram;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/access', function (Request $request, Nutgram $bot) {
    try {
        // Validate the login data
        $loginData = $bot->validateLoginData($request->getQueryString());

        // Get the user from the database or create a new one
        $user = User::firstOrCreate([
            'telegram_id' => $loginData->id,
        ], [
            'username' => $loginData->username,
            'first_name' => $loginData->first_name,
            'last_name' => $loginData->last_name,
        ]);

        // Log the user in
        auth()->login($user, true);

        // Redirect to the home page
        return redirect()->route('home');
    } catch (InvalidDataException) {
        abort(422, 'Invalid Telegram Data');
    }
})->name('access');

require __DIR__.'/auth.php';
