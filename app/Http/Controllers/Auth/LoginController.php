<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\DiscordServiceException;
use App\Http\Controllers\Controller;
use App\Server;
use App\Services\DiscordService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\User;
use Laravel\Socialite\Two\InvalidStateException;

class LoginController extends Controller
{
    /**
     * @var DiscordService
     */
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
        $domain = $request->getHttpHost();
        $parts = explode('.', $domain);
        $subdomain = array_shift($parts);
        $domain = implode('.', $parts);

        return Socialite::driver('discord')
            ->with([
                'redirect_uri' => ($request->secure() ? 'https://' : 'http://') . $domain . '/login/callback',
                'state' => base64_encode('subdomain=' . $subdomain),
            ])
            ->redirect();
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @param Request $request
     *
     * @throws DiscordServiceException
     */
    public function handleDiscordCallback(Request $request)
    {
        $state = $request->input('state');
        $host = $request->getHttpHost();
        $parts = explode('.', $host);

        if (count($parts) > 2) {
            array_shift($parts);
        }

        $domain = implode('.', $parts);
        $user = Socialite::driver('discord')->stateless()->user();

        parse_str(base64_decode($state), $result);
        $subdomain = $result['subdomain'];

        /** @var Server $server */
        $server = Server::where('name', $subdomain)->firstOrFail();

        /** @var User $foundUser */
        $foundUser = User::where('discord_id', $user->id)->first();

        if ($foundUser) {
            if ($server->administrators->count() === 0) {
                $foundUser->administratedServers()->attach($server);
                $foundUser->save();
            }

            if (!$this->discordService->isUserAllowedToLogin($foundUser, $server)
                && !$foundUser->isAdminOfServer($server))
            {
                $request->session()->forget('server_id');
                return redirect()
                    ->route('main', ['server_id' => $server->snowflake])
                    ->withErrors(['message' => __('Login unauthorized.')]);
            }

            $foundUser->name = $user->getName();
            $foundUser->email = $user->getEmail();
            $foundUser->server()->associate($server);
            $foundUser->save();

            $request->session()->put('server_id', $server->id);
            Auth::login($foundUser);
        } else {
            $newUser = new User();
            $newUser->name = $user->getName();
            $newUser->email = $user->getEmail();
            $newUser->discord_id = $user->getId();
            $newUser->api_token = Str::random(60);
            $newUser->server()->associate($server);
            $newUser->save();

            if ($server->administrators->count() === 0) {
                $newUser->administratedServers()->attach($server);
                $newUser->save();
            } else {
                if (!$this->discordService->isUserAllowedToLogin($newUser, $server)) {
                    return redirect()
                        ->route('main', ['server_id' => $server->snowflake])
                        ->withErrors(['message' => __('Login unauthorized.')]);
                }
            }

            $request->session()->put('server_id', $server->id);
            Auth::login($newUser);
        }

        $url = ($request->secure() ? 'https://' : 'http://') . $subdomain . '.' . $domain . '/dashboard';

        return redirect()->intended($url);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout(Request $request)
    {
        $serverId = $request->query('server_id');
        $request->session()->flush();
        Auth::logout();

        return redirect()->route('main', ['server_id' => $serverId]);
    }
}
