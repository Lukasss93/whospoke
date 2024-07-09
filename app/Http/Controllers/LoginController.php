<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use SergiX44\Nutgram\Exception\InvalidDataException;
use SergiX44\Nutgram\Nutgram;

class LoginController extends Controller
{
    public function local(Request $request)
    {
        $redirectUrl = $request->input('redirect') ?: route('home');

        $user = User::firstOrCreate([
            'id' => 0,
        ], [
            'name' => 'Local User',
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
                'name' => trim($loginData->first_name . ' ' . $loginData->last_name),
            ]);

            // Save the user's profile picture
            $user->saveAvatar($loginData->photo_url);

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
            'name' => $slackUser->name,
        ]);

        // Save the user's profile picture
        $user->saveAvatar($slackUser->getAvatar());

        // Log the user in
        auth()->login($user, true);

        // Redirect to the home page
        return redirect()->to($redirectUrl);
    }
}
