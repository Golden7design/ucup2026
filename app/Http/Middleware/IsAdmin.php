<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin // <-- Le nom de la classe DOIT correspondre au nom du fichier
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Vérifie si l'utilisateur est connecté
        if (! Auth::check()) {
            // Si non connecté, rediriger vers la page de connexion
            return redirect('/login');
        }

        // 2. Vérifie si l'utilisateur est un administrateur
        // REMPLACER cette ligne par VOTRE logique réelle (ex: role_id, une méthode, etc.)
        if (Auth::user()->is_admin) { 
            return $next($request);
        }

        // Si connecté mais n'est pas admin, retourner une erreur 403 (Accès interdit)
        abort(403, 'Accès non autorisé. Seuls les administrateurs peuvent accéder à cette zone.');
    }
}