<?php

namespace Sayed\SayedDashboard;

class SayedDashboard
{
    /**
     * Get the package version.
     */
    public function version(): string
    {
        return '1.0.0';
    }
    
    /**
     * Check if user is admin.
     */
    public function isAdmin($user = null): bool
    {
        $user = $user ?? auth()->user();
        return $user && $user->is_admin === true;
    }
    
    /**
     * Get dashboard configuration.
     */
    public function config(string $key = null, $default = null)
    {
        if ($key === null) {
            return config('sayed-dashboard');
        }
        
        return config('sayed-dashboard.' . $key, $default);
    }
}