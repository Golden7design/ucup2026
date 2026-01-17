<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HandleCsrfAndSessions
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
        // Fix for session domain issues
        if (config('session.domain') === 'localhost') {
            // Allow session cookies to work on localhost and 127.0.0.1
            config(['session.domain' => null]);
        }

        // Fix for CSRF cookie domain
        if (config('session.domain') === 'localhost') {
            config(['session.domain' => null]);
        }

        // Ensure CSRF cookie is sent with the response
        $response = $next($request);

        // Add CSRF token to response headers for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            $response->headers->set('X-CSRF-TOKEN', csrf_token());
        }

        return $response;
    }
}