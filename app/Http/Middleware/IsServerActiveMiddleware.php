<?php

namespace App\Http\Middleware;

use App\Server;
use Closure;
use Illuminate\Http\Request;

class IsServerActiveMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $httpHost = $request->getHttpHost();
        $parts = explode('.', $httpHost);
        $subdomain = $parts[0];
        $server = Server::where('name', $subdomain)->first();

        if (!$server) {
            return redirect()->route('welcome');
        }

        if (!$server->is_active) {
            return redirect()->route('inactive');
        }

        return $next($request);
    }
}
