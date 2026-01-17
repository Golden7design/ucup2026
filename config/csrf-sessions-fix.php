<?php

return [
    /**
     * Fix for CSRF and Session issues
     */
    
    // Session domain fix
    'session_domain_fix' => env('SESSION_DOMAIN_FIX', true),
    
    // CSRF domain fix  
    'csrf_domain_fix' => env('CSRF_DOMAIN_FIX', true),
    
    // Trusted hosts for CSRF
    'trusted_hosts' => [
        'localhost',
        '127.0.0.1',
        env('APP_URL', 'localhost'),
    ],
    
    // CSRF cookie settings
    'csrf_cookie' => [
        'domain' => env('CSRF_COOKIE_DOMAIN', null),
        'secure' => env('CSRF_COOKIE_SECURE', false),
        'same_site' => env('SESSION_SAME_SITE', 'lax'),
        'http_only' => false, // Allow JavaScript access for AJAX
    ],
    
    // Session cookie settings
    'session_cookie' => [
        'domain' => env('SESSION_DOMAIN', null),
        'secure' => env('SESSION_SECURE_COOKIE', false),
        'same_site' => env('SESSION_SAME_SITE', 'lax'),
        'http_only' => true,
    ],
];