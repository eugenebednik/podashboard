<?php

namespace App\Http\Middleware;

use App\Server;
use Closure;
use Illuminate\Http\Request;
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
        $server = Server::findOrFail($request->session()->get('server_id'));

        if (!Auth::user()->isAdminOfServer($server)) {
            return redirect()->route('main')->withErrors(['error' => __('Login unauthorized.')]);
        }

        return $next($request);
    }
}
