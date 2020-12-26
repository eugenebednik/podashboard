<?php

namespace App\Http\Middleware;

use App\Server;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ServerIdMiddleware
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
        $parts = explode('.', $request->getHttpHost());
        $subdomain = $parts[0];
        $server = Server::where('name', $subdomain)->firstOrFail();

        if (!$server) {
            return response()
                ->json(['message' => __('Unauthorized')])
                ->setStatusCode(Response::HTTP_UNAUTHORIZED);
        }

        session()->put('server', $server);

        return $next($request);
    }
}
