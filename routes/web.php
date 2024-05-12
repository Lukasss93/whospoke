<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RoomController::class, 'index'])->name('home');
Route::get('/@{room:code}', [RoomController::class, 'showRoom'])->name('room.show');

Route::middleware('guest')->group(function () {
    Route::get('access', [RoomController::class, 'access'])->name('access');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::post('/room', [RoomController::class, 'storeRoom'])->name('room.store');
});
