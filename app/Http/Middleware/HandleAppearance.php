<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class HandleAppearance
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier d'abord dans le cookie
        $appearance = $request->cookie('appearance');
        
        // Si pas dans le cookie, vérifier la session
        if (!$appearance) {
            $appearance = $request->session()->get('user_theme');
        }
        
        // Si pas dans la session et utilisateur connecté, vérifier la base de données
        if (!$appearance && $request->user()) {
            $appearance = $request->user()->theme_preference;
        }
        
        // Par défaut, utiliser le système
        $appearance = $appearance ?? 'system';
        
        View::share('appearance', $appearance);

        return $next($request);
    }
}
