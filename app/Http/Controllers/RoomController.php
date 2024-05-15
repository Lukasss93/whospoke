<?php

namespace App\Http\Controllers;

use App\Events\MemberStatusChangedEvent;
use App\Http\Requests\CreateRoomRequest;
use App\Http\Requests\UpdateMemberStatusRequest;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use SergiX44\Nutgram\Exception\InvalidDataException;
use SergiX44\Nutgram\Nutgram;

class RoomController extends Controller
{
    public function index()
    {
        return Inertia::render('Welcome', [
            'rooms' => auth()->user()?->rooms ?? [],
            'canCreateRooms' => auth()->user()?->can('create', Room::class),
            'canCreateRooms' => auth()->user()?->can('create', Room::class) ?? false,
        ]);
    }

    public function access(Request $request, Nutgram $bot)
    {
        try {
            // Validate the login data
            $loginData = $bot->validateLoginData($request->getQueryString());

            // Get the user from the database or create a new one
            $user = User::firstOrCreate([
                'telegram_id' => $loginData->id,
            ], [
                'username' => $loginData->username,
                'first_name' => $loginData->first_name,
                'last_name' => $loginData->last_name,
            ]);

            // Log the user in
            auth()->login($user, true);

            // Redirect to the home page
            return redirect()->route('home');
        } catch (InvalidDataException) {
            abort(422, 'Invalid Telegram Data');
        }
    }

    public function storeRoom(CreateRoomRequest $request)
    {
        $this->authorize('create', Room::class);

        $room = Room::create([
            'user_id' => auth()->id(),
            'code' => $request->input('code'),
            'members' => collect($request->input('members', []))->map(fn(string $x) => [
                'name' => $x,
                'status' => false,
            ]),
        ]);

        return redirect()->route('room.show', $room->code);
    }

    public function showRoom(Room $room)
    {
        return Inertia::render('Room', [
            'room' => $room,
            'isMyRoom' => auth()->id() === $room->user_id,
            'roomUrl' => route('room.show', $room->code),
        ]);
    }

    public function deleteRoom(Room $room)
    {
        $this->authorize('delete', $room);

        $room->delete();

        return redirect()->route('home');
    }

    public function setMemberStatus(UpdateMemberStatusRequest $request, Room $room)
    {
        $this->authorize('updateMemberStatus', $room);

        $memberIndex = $request->integer('member');
        $status = $request->boolean('status');

        $members = $room->members;
        $members[$memberIndex]['status'] = $status;
        $room->members = $members;
        $room->save();

        MemberStatusChangedEvent::dispatch($room, $memberIndex, $status);
    }

    public function resetMembersStatus(Room $room)
    {
        $this->authorize('updateMemberStatus', $room);

        $members = $room->members;
        foreach ($members as $key => $member) {
            $members[$key]['status'] = false;
        }
        $room->members = $members;
        $room->save();

        MemberStatusChangedEvent::dispatch($room);
    }
}
