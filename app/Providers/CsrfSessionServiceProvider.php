<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Routing\Router;
use Illuminate\Foundation\Http\Events\RequestHandled;

class CsrfSessionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register our custom middleware
        $this->app['router']->aliasMiddleware('handle.csrf.sessions', \App\Http\Middleware\HandleCsrfAndSessions::class);
        
        // Merge our custom configuration
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/csrf-sessions-fix.php', 
            'csrf-sessions-fix'
        );
        
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/filament-csrf-fix.php',
            'filament-csrf-fix'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Add CSRF meta tag to all pages
        Blade::directive('csrfMeta', function () {
            return '<meta name="csrf-token" content="' . csrf_token() . '">';
        });

        // Fix session domain for localhost
        if (config('session.domain') === 'localhost') {
            config(['session.domain' => null]);
        }

        // Fix CSRF cookie domain for localhost
        if (config('session.domain') === 'localhost') {
            config(['session.domain' => null]);
        }

        // Add CSRF token to all AJAX responses
        $this->app['events']->listen(RequestHandled::class, function (RequestHandled $event) {
            if ($event->request->ajax() || $event->request->wantsJson()) {
                $event->response->headers->set('X-CSRF-TOKEN', csrf_token());
            }
        });
    }
}
