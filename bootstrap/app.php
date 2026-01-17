<?php

use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\HandleCsrfAndSessions;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;

// Assurez-vous d'importer la classe de votre middleware admin
use App\Http\Middleware\IsAdmin; // <<< Importation de la classe IsAdmin

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->encryptCookies(except: ['appearance', 'sidebar_state']);

        $middleware->web(append: [
            HandleAppearance::class,
            HandleInertiaRequests::class,
            HandleCsrfAndSessions::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);
        
        // --- AJOUT DE L'ALIAS ADMIN ---
        $middleware->alias([
            'admin' => IsAdmin::class, // <<< LIGNE ESSENTIELLE AJOUTÉE
            // Ajoutez ici d'autres alias de route si vous en avez (ex: 'auth', 'guest')
        ]);
        // ------------------------------
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();