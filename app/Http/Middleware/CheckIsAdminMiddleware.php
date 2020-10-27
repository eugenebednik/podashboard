<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CheckIsAdminMiddleware
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->isJson()) {
            if (!Auth::user()->isAdminOfServer(Auth::user()->server)) {
                return redirect()->route('dashboard')->withErrors(['error' => __('Unauthorized.')]);
            }
        } else {
            $token = $request->bearerToken();

            /** @var User $user */
            $user = User::where('api_token', $token)->first();

            if (!$user || !$user->isAdminOfServer($user->server)) {
                return response()
                    ->json(['errors' => __('Unauthorized')])
                    ->setStatusCode(Response::HTTP_UNAUTHORIZED);
            }
        }

        return $next($request);
    }
}
