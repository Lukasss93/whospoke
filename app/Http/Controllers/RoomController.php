<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRoomRequest;
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
            'appName' => config('app.name'),
            'appVersion' => config('app.version'),
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
        abort(404, "Room {$room->code} not found");
    }
}
