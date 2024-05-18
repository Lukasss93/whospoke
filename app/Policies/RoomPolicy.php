<?php

namespace App\Policies;

use App\Models\Room;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RoomPolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->rooms()->count() < config('room.create.limit');
    }

    public function update(User $user, Room $room): bool
    {
        return $user->id === $room->user_id;
    }

    public function delete(User $user, Room $room): bool
    {
        return $user->id === $room->user_id;
    }
}
