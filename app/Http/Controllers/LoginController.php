<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;
use SergiX44\Nutgram\Exception\InvalidDataException;
use SergiX44\Nutgram\Nutgram;
use Throwable;

class LoginController extends Controller
{
    public function local(Request $request)
    {
        $redirectUrl = $request->input('redirect') ?: route('home');

        $user = User::firstOrCreate([
            'telegram_id' => 0,
        ], [
            'username' => 'LocalUser',
            'first_name' => 'Local',
            'last_name' => 'User',
        ]);

        auth()->login($user, true);

        return redirect()->to($redirectUrl);
    }

    public function telegramCallback(Request $request, Nutgram $bot)
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

    public function slackRedirect(Request $request)
    {
        session()->put('redirectTo', $request->input('redirect') ?: route('home'));

        return Socialite::driver('slack')->redirect();
    }

    public function slackCallback(Request $request)
    {
        // Redirect url
        $redirectUrl = session()->pull('redirectTo', route('home'));

        // Get the user from the Slack API
        $slackUser = Socialite::driver('slack')->user();

        // Get the user from the database or create a new one
        $user = User::firstOrCreate([
            'slack_id' => $slackUser->id,
        ], [
            'username' => $slackUser->nickname,
            'first_name' => $slackUser->name,
        ]);

        // Download the user's profile picture
        if (!empty($slackUser->getAvatar())) {
            try {
                $avatarPath = sprintf("avatars/%s.jpg", $user->id);
                $avatarContent = file_get_contents($slackUser->getAvatar());
                Storage::disk('public')->put($avatarPath, $avatarContent);
            } catch (Throwable) {
                // Do not save the avatar if an error occurs
            }
        }

        // Log the user in
        auth()->login($user, true);

        // Redirect to the home page
        return redirect()->to($redirectUrl);
    }
}
