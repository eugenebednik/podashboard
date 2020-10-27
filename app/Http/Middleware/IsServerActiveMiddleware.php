<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsServerActiveMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user()->guild->is_active) {
            return redirect()
                ->route('main')
                ->withErrors(['message' => __('Your server account is inactive.')]);
        }

        return $next($request);
    }
}
