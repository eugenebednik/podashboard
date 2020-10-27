<?php

namespace App\Http\Middleware;

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
        $server = Server::findOrFail($request->session()->get('server_id'));

        if (($this->discordService->isUserAllowedToLogin($user, $server)
            || $user->isAdminOfServer($server)) && $server->is_active)
        {
            return $next($request);
        }

        return redirect()->route('main')->withErrors(['message' => __('Login unauthorized.')]);
    }
}
