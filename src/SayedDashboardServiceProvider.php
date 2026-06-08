<?php

namespace Sayed\SayedDashboard;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Sayed\SayedDashboard\Console\Commands\InstallCommand;

class SayedDashboardServiceProvider extends ServiceProvider
{
    /**
     * Register any package services.
     */
    public function register(): void
    {
        // Merge default config
        $this->mergeConfigFrom(
            __DIR__.'/Config/dashboard.php', 'sayed-dashboard'
        );

        // Register facade
        $this->app->singleton('sayed-dashboard', function ($app) {
            return new SayedDashboard();
        });
    }

    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__.'/Routes/web.php');
        
        // Load views
        $this->loadViewsFrom(__DIR__.'/Resources/views', 'sayed-dashboard');
        
        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');
        
        // Publish configuration
        $this->publishes([
            __DIR__.'/Config/dashboard.php' => config_path('sayed-dashboard.php'),
        ], 'sayed-dashboard-config');
        
        // Publish assets (CSS, JS, images)
        $this->publishes([
            __DIR__.'/Resources/assets' => public_path('vendor/sayed-dashboard'),
        ], 'sayed-dashboard-assets');
        
        // Publish views for customization
        $this->publishes([
            __DIR__.'/Resources/views' => resource_path('views/vendor/sayed-dashboard'),
        ], 'sayed-dashboard-views');
        
        // Publish migrations for customization
        $this->publishes([
            __DIR__.'/Database/Migrations' => database_path('migrations'),
        ], 'sayed-dashboard-migrations');
        
        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
            ]);
        }
        
        // Register middleware
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('sayed.admin', \Sayed\SayedDashboard\Http\Middleware\AdminMiddleware::class);
        $router->aliasMiddleware('sayed.permission', \Sayed\SayedDashboard\Http\Middleware\PermissionMiddleware::class);
        $router->aliasMiddleware('sayed.guest', \Sayed\SayedDashboard\Http\Middleware\RedirectIfAuthenticated::class);
        
        // Register view composers (optional)
        $this->loadViewComposers();
    }
    
    /**
     * Load view composers for dashboard.
     */
    protected function loadViewComposers(): void
    {
        view()->composer('sayed-dashboard::layouts.master', function ($view) {
            $view->with('navigation', config('sayed-dashboard.navigation', []));
            $view->with('appName', config('app.name', 'Laravel'));
        });
    }
}