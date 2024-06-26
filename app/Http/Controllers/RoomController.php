<?php

namespace App\Http\Controllers;

use App\Enums\MemberType;
use App\Enums\RoomType;
use App\Events\RoomChangedEvent;
use App\Http\Requests\CreateRoomRequest;
use App\Http\Resources\RoomResource;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use SergiX44\Nutgram\Exception\InvalidDataException;
use SergiX44\Nutgram\Nutgram;
use Throwable;

class RoomController extends Controller
{
    public function index()
    {
        return Inertia::render('Welcome', [
            'rooms' => auth()->user()?->rooms ?? [],
            'canCreateRooms' => auth()->user()?->can('create', Room::class) ?? false,
        ]);
    }

    public function access(Request $request, Nutgram $bot)
    {
        try {
            // Redirect url
            $redirectUrl = $request->input('redirect') ?: route('home');

            // Validate the login data
            $loginData = $bot->validateLoginData(http_build_query($request->except('redirect')));

            // Get the user from the database or create a new one
            $user = User::firstOrCreate([
                'telegram_id' => $loginData->id,
            ], [
                'username' => $loginData->username,
                'first_name' => $loginData->first_name,
                'last_name' => $loginData->last_name,
            ]);

            // Download the user's profile picture
            if ($loginData->photo_url !== null) {
                try {
                    $avatarPath = sprintf("avatars/%s.jpg", $loginData->id);
                    $avatarContent = file_get_contents($loginData->photo_url);
                    Storage::disk('public')->put($avatarPath, $avatarContent);
                } catch (Throwable $e) {
                    // Do not save the avatar if an error occurs
                }
            }

            // Log the user in
            auth()->login($user, true);

            // Redirect to the home page
            return redirect()->to($redirectUrl);
        } catch (InvalidDataException) {
            abort(422, 'Invalid Telegram Data');
        }
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
            'isMyRoom' => $room->canManage(auth()->user()),
            'userRole' => $room->getUserRole(auth()->user()),
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
}
