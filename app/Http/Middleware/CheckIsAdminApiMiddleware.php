<?php

namespace App\Http\Middleware;

use App\Server;
use App\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CheckIsAdminApiMiddleware
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
        $token = $request->bearerToken();

        /** @var User $user */
        $user = User::where('api_token', $token)->firstOrFail();
        $server = Server::findOrFail($request->query('server_id'));

        if (!$user || !$server || !$user->isAdminOfServer($server)) {
            return response()
                ->json(['message' => __('Unauthorized')])
                ->setStatusCode(Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
