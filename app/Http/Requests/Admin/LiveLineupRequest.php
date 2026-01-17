<?php
// app/Http/Requests/Admin/LiveLineupRequest.php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class LiveLineupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Assurez-vous que seul un utilisateur authentifié (admin) peut soumettre ce formulaire
        return auth()->check();
    }
    
    /**
     * Suppression de la méthode all(). 
     * Elle est inutile car le formulaire envoie toutes les données Domicile/Extérieur en une seule fois.
     * Le contrôleur devra identifier les IDs des équipes via l'objet $match.
     */

    /**
     * Get the validation rules that apply to the request.
     * * Les règles valident TOUS les champs envoyés pour les deux équipes (home_*, away_*).
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            // ===================================
            // RÈGLES DE VALIDATION : ÉQUIPE À DOMICILE (HOME)
            // ===================================
            'home_coach' => ['nullable', 'string', 'max:100'],
            // La formation est nécessaire pour l'affichage, donc 'required'
            'home_formation' => ['required', 'string', 'max:10', 'regex:/^(\d-){2,3}\d$/'],
            
            // Titulaires (IDs des joueurs)
            'home_starters' => ['required', 'array', 'size:11'],
            'home_starters.*' => ['integer', 'exists:players,id'],
            
            // ✅ NOUVEAU : Postes Spécifiques Domicile
            'home_starter_positions' => ['required', 'array', 'size:11'], // Doit avoir 11 postes
            'home_starter_positions.*' => ['required', 'string', 'max:5'], // DC, DG, MOC, etc.
            
            // Remplaçants
            'home_substitutes' => ['nullable', 'array', 'max:12'],
            'home_substitutes.*' => ['integer', 'exists:players,id'],
            
            // ===================================
            // RÈGLES DE VALIDATION : ÉQUIPE À L'EXTÉRIEUR (AWAY)
            // ===================================
            'away_coach' => ['nullable', 'string', 'max:100'],
            // La formation est nécessaire pour l'affichage, donc 'required'
            'away_formation' => ['required', 'string', 'max:10', 'regex:/^(\d-){2,3}\d$/'],
            
            // Titulaires (IDs des joueurs)
            'away_starters' => ['required', 'array', 'size:11'],
            'away_starters.*' => ['integer', 'exists:players,id'],
            
            // ✅ NOUVEAU : Postes Spécifiques Extérieur
            'away_starter_positions' => ['required', 'array', 'size:11'], 
            'away_starter_positions.*' => ['required', 'string', 'max:5'], 

            // Remplaçants
            'away_substitutes' => ['nullable', 'array', 'max:12'], 
            'away_substitutes.*' => ['integer', 'exists:players,id'],
        ];
    }
    
    /**
     * Custom message for validation
     */
    public function messages(): array
    {
        return [
            // Messages Domicile
            'home_formation.required' => 'La formation tactique (Domicile) est obligatoire.',
            'home_formation.regex' => 'Le format de la formation (Domicile) est invalide (ex: 4-4-2).',
            'home_starters.size' => 'L\'équipe Domicile doit avoir exactement 11 titulaires.',
            'home_starter_positions.required' => 'Les postes spécifiques (Domicile) sont obligatoires.',
            'home_starter_positions.size' => 'L\'équipe Domicile doit avoir exactement 11 postes spécifiques.',
            
            // Messages Extérieur
            'away_formation.required' => 'La formation tactique (Extérieur) est obligatoire.',
            'away_formation.regex' => 'Le format de la formation (Extérieur) est invalide (ex: 4-4-2).',
            'away_starters.size' => 'L\'équipe Extérieur doit avoir exactement 11 titulaires.',
            'away_starter_positions.required' => 'Les postes spécifiques (Extérieur) sont obligatoires.',
            'away_starter_positions.size' => 'L\'équipe Extérieur doit avoir exactement 11 postes spécifiques.',
            
            // Messages Génériques
            '*.exists' => 'Un ou plusieurs joueurs sélectionnés n\'existent pas dans la base de données.',
            '*.max' => 'Le nombre maximum de remplaçants est de :max.',
            '*.required' => 'Ce champ est obligatoire.',
        ];
    }
}