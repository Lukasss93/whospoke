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
        return $user->rooms()->count() <= 1;
    }

    public function delete(User $user, Room $room): bool
    {
        return $user->id === $room->user_id;
    }
}