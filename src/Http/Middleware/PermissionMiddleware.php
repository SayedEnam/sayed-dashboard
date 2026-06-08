<?php

namespace Sayed\SayedDashboard\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, $permission)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        // Check if user has the required permission
        // This will be expanded when you add proper RBAC
        if (!auth()->user()->is_admin && !auth()->user()->hasPermission($permission)) {
            abort(403, 'You do not have permission to access this page.');
        }
        
        return $next($request);
    }
}