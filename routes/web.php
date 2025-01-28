<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProfessionsController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UsersController;
use App\Http\Middleware\NoBrowserCache;
use Illuminate\Support\Facades\Route;

Route::get('/', [RoomController::class, 'index'])->name('home');
Route::get('@{room:code}', [RoomController::class, 'showRoom'])->name('room.show')->middleware(NoBrowserCache::class);
Route::get('@{room:code}/mini', [RoomController::class, 'showRoomMini'])->name('room.show.mini')->middleware(NoBrowserCache::class);

Route::middleware('guest')->group(function () {
    if (app()->isLocal()) {
        Route::get('auth/local', [LoginController::class, 'local'])->name('login.local');
    }
    Route::get('auth/telegram', [LoginController::class, 'telegramCallback'])->name('login.telegram');

    Route::get('auth/slack/redirect', [LoginController::class, 'slackRedirect'])->name('login.slack.redirect');
    Route::get('auth/slack/callback', [LoginController::class, 'slackCallback'])->name('login.slack.callback');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::post('room', [RoomController::class, 'storeRoom'])->name('room.store');

    Route::delete('@{room:code}', [RoomController::class, 'deleteRoom'])->name('room.delete');
    Route::delete('@{room:code}/reset', [RoomController::class, 'reset'])->name('room.reset');
    Route::patch('@{room:code}/members/online', [RoomController::class, 'setMembersOnline'])->name('room.members.online');
    Route::patch('@{room:code}/members/pending', [RoomController::class, 'setMembersPending'])->name('room.members.pending');
    Route::post('@{room:code}/members/end-time', [RoomController::class, 'endOtherMembersTime'])->name('room.members.time.end');
    Route::post('@{room:code}/start', [RoomController::class, 'start'])->name('room.time.start');
    Route::post('@{room:code}/stop', [RoomController::class, 'stop'])->name('room.time.stop');
    Route::patch('@{room:code}/professions', [RoomController::class, 'updateProfessionsStatus'])->name('room.professions.status');

    Route::post('member/{member}/status', [MemberController::class, 'setMemberStatus'])->name('member.status.update');
    Route::post('member/{member}/type', [MemberController::class, 'setMemberType'])->name('member.type.update');
    Route::post('member/{member}/time/reset', [MemberController::class, 'resetTime'])->name('member.time.reset');
    Route::post('member/{member}/time/start', [MemberController::class, 'startTime'])->name('member.time.start');
    Route::post('member/{member}/time/end', [MemberController::class, 'endTime'])->name('member.time.end');
    Route::post('member/{member}/count/reset', [MemberController::class, 'resetCount'])->name('member.count.reset');
    Route::post('member/{member}/count/increment', [MemberController::class, 'incrementCount'])->name('member.count.increment');
    Route::post('member/{member}/count/decrement', [MemberController::class, 'decrementCount'])->name('member.count.decrement');
    Route::post('member/{member}/edit', [MemberController::class, 'editMember'])->name('member.user.edit');

    Route::get('users', [UsersController::class, 'search'])->name('users.search');
    Route::get('professions', [ProfessionsController::class, 'search'])->name('professions.search');
});

Route::get('locale/{locale}', function(string $locale){
    session()->put('locale', $locale);
    return back();
})->name('locale.set');

Route::get('version', fn() => response()->json([
    'version' => config('app.version'),
]))->name('version');
