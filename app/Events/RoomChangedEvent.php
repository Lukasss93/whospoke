<?php

namespace App\Events;

use App\Models\Room;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoomChangedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Room $room)
    {
    }

    public function broadcastOn(): Channel
    {
        return new Channel('room.' . $this->room->id);
    }

    /*public function broadcastWith(): array
    {
        return ['members' => $this->room->members];
    }*/
}
