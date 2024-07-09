<?php

use App\Enums\MemberType;
use App\Events\RoomChangedEvent;
use App\Http\Middleware\AuthenticateGuest;
use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int)$user->id === (int)$id;
});

Broadcast::routes(['middleware' => ['web', AuthenticateGuest::class]]);

Broadcast::channel('room.{id}.online', function (User $user, $roomId) {
    $room = Room::find($roomId);

    $linkedMember = $room->members()
        ->where('user_id', $user->id)
        ->where('type', MemberType::PENDING)
        ->first();

    if ($linkedMember !== null) {
        $linkedMember->type = MemberType::DEFAULT;
        $linkedMember->save();

        $room->refresh();
        RoomChangedEvent::dispatch($room);
    }

    return $user;
});
