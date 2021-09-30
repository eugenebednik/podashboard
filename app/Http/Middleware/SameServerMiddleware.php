<?php

namespace App\Http\Middleware;

use Closure;
use App\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SameServerMiddleware
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
        if (count($parts) > 2) {
            $subdomain = array_shift($parts);
        }

        $domain = implode('.', $parts);
        $server = Server::findOrFail($request->session()->get('server_id'));

        if (Str::snake($subdomain) === $server->name) {
            return $next($request);
        }

        $url = ($request->secure() ? 'https://' : 'http://') . $subdomain . '.' . $domain;
        Auth::logout();

        return redirect()->intended($url)->withErrors(['message' => __('Unauthorized.')]);
    }
}