<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OptimizeApiRequests
{
    public function handle(Request $request, Closure $next)
    {
        // 1. Désactiver le debugbar pour les requêtes API
        if (class_exists('\Barryvdh\Debugbar\Facades\Debugbar') &&
            strpos($request->path(), 'api/') === 0) {
            \Barryvdh\Debugbar\Facades\Debugbar::disable();
        }

        // 2. Réduire la journalisation pour les requêtes API
        if (strpos($request->path(), 'api/') === 0) {
            // Désactiver temporairement la journalisation détaillée
            config(['logging.channels.stack.channels' => ['single']]);

            // 3. Ajouter des headers pour optimiser le cache
            $response = $next($request);

            // Désactiver la mise en cache pour les requêtes live-update
            if (strpos($request->path(), 'live-update') !== false) {
                $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
                $response->headers->set('Pragma', 'no-cache');
                $response->headers->set('Expires', 'Sat, 26 Jul 1997 05:00:00 GMT');
            }

            return $response;
        }

        return $next($request);
    }
}
