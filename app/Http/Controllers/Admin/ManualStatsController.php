<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MatchModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ManualStatsController extends Controller
{
    /**
     * Affiche le formulaire pour saisir les statistiques manuelles d'un match
     */
    public function edit(MatchModel $match)
    {
        // Vérifier que le match est terminé
        if ($match->status !== 'finished') {
            return redirect()->back()->with('error', 'Les statistiques ne peuvent être saisies que pour les matchs terminés.');
        }

        return view('admin.matches.manual_stats', compact('match'));
    }

    /**
     * Met à jour les statistiques manuelles d'un match
     */
    public function update(Request $request, MatchModel $match)
    {
        // Validation des données
        $validated = $request->validate([
            'home_possession' => 'nullable|integer|min:0|max:100',
            'away_possession' => 'nullable|integer|min:0|max:100',
            'home_shots' => 'nullable|integer|min:0',
            'home_shots_on_target' => 'nullable|integer|min:0|lte:home_shots',
            'away_shots' => 'nullable|integer|min:0',
            'away_shots_on_target' => 'nullable|integer|min:0|lte:away_shots',
            'home_corners' => 'nullable|integer|min:0',
            'away_corners' => 'nullable|integer|min:0',
            'home_fouls' => 'nullable|integer|min:0',
            'away_fouls' => 'nullable|integer|min:0',
            'home_yellow_cards' => 'nullable|integer|min:0',
            'away_yellow_cards' => 'nullable|integer|min:0',
            'home_red_cards' => 'nullable|integer|min:0',
            'away_red_cards' => 'nullable|integer|min:0',
            'home_offsides' => 'nullable|integer|min:0',
            'away_offsides' => 'nullable|integer|min:0',
            'admin_notes' => 'nullable|string',
            'match_report' => 'nullable|string',
        ]);

        try {
            // Mettre à jour les statistiques
            $match->update($validated);

            // Vérifier que la somme de la possession fait 100%
            $homePossession = $validated['home_possession'] ?? 0;
            $awayPossession = $validated['away_possession'] ?? 0;
            
            if ($homePossession + $awayPossession !== 100) {
                // Corriger automatiquement si ce n'est pas 100%
                $awayPossession = 100 - $homePossession;
                $match->update(['away_possession' => $awayPossession]);
            }

            return redirect()->route('admin.matches.index')
                ->with('success', 'Statistiques manuelles mises à jour avec succès !');

        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour des statistiques manuelles: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors de la mise à jour des statistiques.');
        }
    }

    /**
     * Affiche un résumé des statistiques pour un match
     */
    public function show(MatchModel $match)
    {
        // Charger les relations nécessaires
        $match->load(['homeTeam.university', 'awayTeam.university']);
        
        return view('admin.matches.stats_summary', compact('match'));
    }
}