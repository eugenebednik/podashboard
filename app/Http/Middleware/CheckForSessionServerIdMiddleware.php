<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckForSessionServerIdMiddleware
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
        if (!$request->session()->get('server_id')) {
            return redirect()->route('login.logout');
        }

        return $next($request);
    }
}
