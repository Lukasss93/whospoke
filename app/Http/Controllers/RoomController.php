<?php

namespace App\Http\Controllers;

use App\Enums\MemberType;
use App\Enums\RoomType;
use App\Events\RoomChangedEvent;
use App\Http\Requests\CreateRoomRequest;
use App\Http\Resources\RoomResource;
use App\Models\Room;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class RoomController extends Controller
{
    public function index()
    {
        return Inertia::render('Welcome', [
            'rooms' => auth()->user()?->rooms ?? [],
            'canCreateRooms' => auth()->user()?->can('create', Room::class) ?? false,
        ]);
    }

    public function storeRoom(CreateRoomRequest $request)
    {
        $this->authorize('create', Room::class);

        $type = $request->enum('type', RoomType::class);
        $members = $request->collect('members');

        $room = Room::create([
            'user_id' => auth()->id(),
            'type' => $type,
            'title' => $request->input('title'),
            'code' => $request->input('code'),
        ]);

        $room->members()->createMany($members->map(fn(string $x) => [
            'name' => $x,
        ]));

        return redirect()->route('room.show', $room->code);
    }

    public function showRoom(Room $room)
    {
        return Inertia::render('Room', [
            'baseRoom' => new RoomResource($room),
            'baseIsMyRoom' => $room->canManage(auth()->user()),
            'baseUserRole' => $room->getUserRole(auth()->user()),
            'roomUrl' => route('room.show', $room->code),
        ]);
    }

    public function deleteRoom(Room $room)
    {
        $this->authorize('delete', $room);

        DB::transaction(function () use ($room) {
            $room->members()->delete();
            $room->delete();
        });

        return redirect()->route('home');
    }

    public function reset(Room $room)
    {
        $this->authorize('update', $room);

        $room->members()->update([
            'type' => MemberType::DEFAULT,
            'status' => false,
            'count' => 0,
            'started_at' => null,
            'ended_at' => null,
        ]);

        $room->started_at = null;
        $room->ended_at = null;
        $room->save();

        $room->refresh();

        RoomChangedEvent::dispatch($room);
    }

    public function start(Room $room)
    {
        $this->authorize('update', $room);

        $room->started_at = now();
        $room->save();

        RoomChangedEvent::dispatch($room);
    }

    public function stop(Room $room)
    {
        $this->authorize('update', $room);

        $room->ended_at = now();
        $room->save();

        RoomChangedEvent::dispatch($room);
    }

    public function setMembersOnline(Room $room)
    {
        $this->authorize('update', $room);

        $room->members()
            ->whereNotIn('type', [MemberType::OFFLINE, MemberType::GUEST])
            ->update([
                'type' => MemberType::DEFAULT,
            ]);

        $room->refresh();

        RoomChangedEvent::dispatch($room);
    }

    public function setMembersPending(Room $room)
    {
        $this->authorize('update', $room);

        $room->members()
            ->whereNotIn('type', [MemberType::OFFLINE, MemberType::GUEST])
            ->update([
                'type' => MemberType::PENDING,
            ]);

        $room->refresh();

        RoomChangedEvent::dispatch($room);
    }
}
