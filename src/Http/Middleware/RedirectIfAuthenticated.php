<?php

namespace Sayed\SayedDashboard\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (auth()->check()) {
            return redirect()->route('sayed.dashboard.home');
        }
        
        return $next($request);
    }
}