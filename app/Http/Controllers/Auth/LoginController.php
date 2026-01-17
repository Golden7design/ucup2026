<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // 1. Afficher le formulaire de connexion
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // 2. Traiter la soumission du formulaire
    public function login(Request $request)
    {
        // Validation des données
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Tentative de connexion
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirection vers le dashboard admin
            return redirect()->intended(route('admin.dashboard'));
        }

        // En cas d'échec
        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
    }

    // 3. Déconnexion
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}