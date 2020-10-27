<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\DiscordServiceException;
use App\Http\Controllers\Controller;
use App\Server;
use App\Services\DiscordService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\User;

class LoginController extends Controller
{
    protected $discordService;

    /**
     * LoginController constructor.
     *
     * @param DiscordService $discordService
     */
    public function __construct(DiscordService $discordService)
    {
        $this->discordService = $discordService;
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToDiscord(Request $request)
    {
        return Socialite::driver('discord')->redirect();
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     *
     * @throws DiscordServiceException
     */
    public function handleDiscordCallback()
    {
        $user = Socialite::driver('discord')->user();
        $serverId = Session::get('server_id');
        $isNewSetup = Session::get('is_new_setup');
        Session::forget(['server_id', 'is_new_setup']);

        $server = Server::findOrFail($serverId);

        if (!$this->discordService->isUserAllowedToLogin($user, $server)) {
            return redirect()
                ->route('main')
                ->with('server_id', $server->snowflake)
                ->withErrors(['message' => __('Login unauthorized.')]);
        }

        /** @var User $foundUser */
        $foundUser = User::where('discord_id', $user->id)->first();

        if ($foundUser) {
            $foundUser->name = $user->getName();
            $foundUser->email = $user->getEmail();
            $foundUser->server()->associate($server);
            $foundUser->save();

            Auth::login($foundUser);
            return redirect('/dashboard');
        } else {
            $newUser = new User();
            $newUser->name = $user->getName();
            $newUser->email = $user->getEmail();
            $newUser->id = $user->getId();
            $newUser->api_token = Str::random(60);
            $newUser->server()->associate($server);

            if ($isNewSetup) {
                $newUser->administratedServers()->attach($server);
            }

            $newUser->save();

            Auth::login($newUser);

            return redirect()->route('dashboard');
        }
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout()
    {
        Auth::logout();

        return redirect()->route('main');
    }
}
