<?php

use App\Http\Middleware\AuthenticateGuest;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int)$user->id === (int)$id;
});

Broadcast::routes(['middleware' => ['web', AuthenticateGuest::class]]);

Broadcast::channel('room.{id}.online', function (User $user, $id) {
    return $user->id;
});
