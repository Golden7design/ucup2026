<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThemeController extends Controller
{
    /**
     * Sauvegarder la préférence de thème de l'utilisateur
     */
    public function store(Request $request)
    {
        $request->validate([
            'theme' => 'required|in:light,dark,system',
        ]);

        $theme = $request->input('theme');
        
        // Sauvegarder dans la session
        session(['user_theme' => $theme]);
        
        // Si l'utilisateur est connecté, sauvegarder dans la base de données
        if (Auth::check()) {
            $user = Auth::user();
            $user->theme_preference = $theme;
            $user->save();
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Préférence de thème sauvegardée',
            'theme' => $theme
        ]);
    }
    
    /**
     * Récupérer la préférence de thème de l'utilisateur
     */
    public function show()
    {
        // Vérifier d'abord dans la session
        $theme = session('user_theme');
        
        // Si pas dans la session et utilisateur connecté, vérifier la base de données
        if (!$theme && Auth::check()) {
            $theme = Auth::user()->theme_preference;
        }
        
        // Par défaut, utiliser le système
        $theme = $theme ?? 'system';
        
        return response()->json([
            'theme' => $theme
        ]);
    }
}