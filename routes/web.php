<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\LocalController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RoomController::class, 'index'])->name('home');
Route::get('@{room:code}', [RoomController::class, 'showRoom'])->name('room.show');

Route::middleware('guest')->group(function () {
    Route::get('access', [RoomController::class, 'access'])->name('access');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::post('room', [RoomController::class, 'storeRoom'])->name('room.store');
    Route::delete('@{room:code}', [RoomController::class, 'deleteRoom'])->name('room.delete');
    Route::post('@{room:code}/member', [RoomController::class, 'setMemberStatus'])->name('room.member.update');
    Route::delete('@{room:code}/members', [RoomController::class, 'reset'])->name('room.reset');
    Route::post('@{room:code}/start', [RoomController::class, 'start'])->name('room.time.start');
    Route::post('@{room:code}/stop', [RoomController::class, 'stop'])->name('room.time.stop');
});

Route::get('locale/{locale}', function(string $locale){
    session()->put('locale', $locale);
    return back();
})->name('locale.set');

if (app()->isLocal()) {
    Route::get('local/login', [LocalController::class, 'login'])->name('local.login');
}
