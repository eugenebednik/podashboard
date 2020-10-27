<?php

namespace App\Http\Middleware;

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

        if ($this->discordService->isUserAllowedToLogin($user, $user->server) && $user->isAdminOfServer($user->server)) {
            return $next($request);
        }

        return redirect('/')->withErrors(['message' => __('Login unauthorized.')]);
    }
}
