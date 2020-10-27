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
     * @param Request $request
     *
     * @throws DiscordServiceException
     */
    public function handleDiscordCallback(Request $request)
    {
        $user = Socialite::driver('discord')->user();
        $serverId = $request->session()->get('server_id');
        $isNewSetup = $request->session()->pull('is_new_setup');
        $server = Server::find($serverId);

        /** @var User $foundUser */
        $foundUser = User::where('discord_id', $user->id)->first();

        if ($foundUser) {
            if (!$this->discordService->isUserAllowedToLogin($foundUser, $server)
                && !$foundUser->isAdminOfServer($server))
            {
                Session::forget('server_id');
                return redirect()
                    ->route('main', ['server_id' => $server->snowflake])
                    ->withErrors(['message' => __('Login unauthorized.')]);
            }

            $foundUser->name = $user->getName();
            $foundUser->email = $user->getEmail();
            $foundUser->server()->associate($server);
            $foundUser->save();

            Auth::login($foundUser);
            return redirect()->route('dashboard');
        } else {
            $newUser = new User();
            $newUser->name = $user->getName();
            $newUser->email = $user->getEmail();
            $newUser->discord_id = $user->getId();
            $newUser->api_token = Str::random(60);
            $newUser->server()->associate($server);
            $newUser->save();

            if ($isNewSetup) {
                $newUser->administratedServers()->attach($server);
                $newUser->save();
            } else {
                if (!$this->discordService->isUserAllowedToLogin($foundUser, $server)) {
                    return redirect()
                        ->route('main', ['server_id' => $server->snowflake])
                        ->withErrors(['message' => __('Login unauthorized.')]);
                }
            }

            Auth::login($newUser);
            return redirect()->route('dashboard');
        }
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout(Request $request)
    {
        $serverId = $request->session()->get('server_id');
        $request->session()->flush();
        Auth::logout();

        return redirect()->route('main', ['server_id' => $serverId]);
    }
}
