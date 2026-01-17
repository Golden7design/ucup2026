<?php

return [
    /**
     * Middleware configuration for Filament admin panel
     */
    'middleware' => [
        'web',
        'auth',
        'verified',
        'handle.csrf.sessions', // Add our custom middleware
    ],
    
    /**
     * Session configuration for Filament
     */
    'session' => [
        'domain' => env('SESSION_DOMAIN', null),
        'secure' => env('SESSION_SECURE_COOKIE', false),
        'same_site' => env('SESSION_SAME_SITE', 'lax'),
    ],
    
    /**
     * CSRF configuration
     */
    'csrf' => [
        'except' => [
            'admin/live/*',
            'api/*',
        ],
    ],
];