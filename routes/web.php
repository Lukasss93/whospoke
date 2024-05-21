<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\LocalController;
use App\Http\Controllers\MemberController;
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
    Route::delete('@{room:code}/reset', [RoomController::class, 'reset'])->name('room.reset');
    Route::post('@{room:code}/start', [RoomController::class, 'start'])->name('room.time.start');
    Route::post('@{room:code}/stop', [RoomController::class, 'stop'])->name('room.time.stop');

    Route::post('member/{member}/status', [MemberController::class, 'setMemberStatus'])->name('member.status.update');
    Route::post('member/{member}/offline', [MemberController::class, 'setMemberOffline'])->name('member.offline.update');
    Route::post('member/{member}/time/reset', [MemberController::class, 'resetTime'])->name('member.time.reset');
    Route::post('member/{member}/time/start', [MemberController::class, 'startTime'])->name('member.time.start');
    Route::post('member/{member}/time/end', [MemberController::class, 'endTime'])->name('member.time.end');
    Route::post('member/{member}/count/reset', [MemberController::class, 'resetCount'])->name('member.count.reset');
    Route::post('member/{member}/count/increment', [MemberController::class, 'incrementCount'])->name('member.count.increment');
    Route::post('member/{member}/count/decrement', [MemberController::class, 'decrementCount'])->name('member.count.decrement');
});

Route::get('locale/{locale}', function(string $locale){
    session()->put('locale', $locale);
    return back();
})->name('locale.set');

if (app()->isLocal()) {
    Route::get('local/login', [LocalController::class, 'login'])->name('local.login');
}
