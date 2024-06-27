<?php

namespace App\Events;

use App\Http\Resources\RoomResource;
use App\Models\RoomEditor;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EditorChangedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public RoomEditor $roomEditor)
    {
    }

    public function broadcastOn(): Channel
    {
        return new Channel(sprintf("room.%s.editor.%s", $this->roomEditor->room_id, $this->roomEditor->user_id));
    }

    public function broadcastWith(): array
    {
        $room = $this->roomEditor->room;
        $user = $this->roomEditor->editor;

        return [
            'room' => new RoomResource($room),
            'isMyRoom' => $room->canManage($user),
            'userRole' => $room->getUserRole($user),
        ];
    }
}
