<?php

namespace App\Http\Middleware;

use App\Exceptions\DiscordServiceException;
use App\Server;
use App\Services\DiscordService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InGuildMiddleware
{
    protected $discordService;

    public function __construct(DiscordService $discordService)
    {
        $this->discordService = $discordService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $host = $request->getHttpHost();
        $parts = explode('.', $host);

        if (count($parts) > 2) {
            array_shift($parts);
        }

        $domain = implode('.', $parts);
        $server = Server::findOrFail($request->session()->get('server_id'));

        $url = ($request->secure() ? 'https://' : 'http://') . $server->name . '.' . $domain;
        $allowed = true;
        $userAllowedToLogin = false;

        try {
            $userAllowedToLogin = $this->discordService->isUserAllowedToLogin($user, $server);
        } catch (DiscordServiceException $e) {
            $allowed = false;
        }

        if ($allowed && $server->is_active && ($userAllowedToLogin || $user->isAdminOfServer($server)))
        {
            return $next($request);
        }

        Auth::logout();
        return redirect()
            ->intended($url)
            ->withErrors(['error' => __('Unauthorized.')]);
    }
}
