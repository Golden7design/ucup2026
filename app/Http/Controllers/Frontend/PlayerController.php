<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Team; // NÉCESSAIRE pour récupérer les équipes pour le filtre
use App\Models\MatchModel; 
use App\Models\MatchEvent;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    /**
     * Affiche le classement des meilleurs joueurs (buteurs et passeurs).
     */
    public function leaderboard()
    {
        // --- 1. Top Buteurs ---
        // Utilisation d'une sous-requête pour compter les buts et filtrer
        $topScorers = Player::with('team.university')
            ->withCount([
                'matchEvents as goals_count' => function ($query) {
                    $query->whereIn('event_type', ['goal', 'penalty_goal']);
                }
            ])
            ->whereHas('matchEvents', function($query) {
                $query->whereIn('event_type', ['goal', 'penalty_goal']);
            })
            ->orderByDesc('goals_count')
            ->take(10) 
            ->get();

        // --- 2. Top Passeurs Décisives (Assists) ---
        // NÉCESSITE QUE LA RELATION 'assists' SOIT DÉFINIE DANS LE MODÈLE Player.php
        $topAssists = Player::with('team.university')
            ->withCount([
                'assists as assists_count' 
            ])
            ->whereHas('assists')
            ->orderByDesc('assists_count')
            ->take(10) 
            ->get();
            
        return view('frontend.players.leaderboard', compact('topScorers', 'topAssists'));
    }
    
    /**
     * Affiche la liste paginée des joueurs avec filtres et tri.
     */
    public function index(Request $request)
    {
        // Récupère toutes les équipes pour le filtre déroulant de la vue
        $teams = Team::with('university')->orderBy('name')->get(); 

        $query = Player::with(['team.university']);

        // Filtres (team_id et position)
        if ($request->filled('team_id')) {
            $query->where('team_id', $request->team_id);
        }

        if ($request->filled('position')) {
            $query->where('position', $request->position);
        }

        // Tri par statistiques ou par défaut
        if ($request->sort === 'goals') {
            $query->withCount(['matchEvents as stats_count' => function ($q) {
                $q->whereIn('event_type', ['goal', 'penalty_goal']);
            }])->orderByDesc('stats_count');

        } elseif ($request->sort === 'assists') {
            // Utilise la relation 'assists' pour le tri
            $query->withCount(['assists as stats_count'])->orderByDesc('stats_count');

        } else {
            // Tri par nom de famille (valeur par défaut pour 'sort' si non spécifié)
            $query->orderBy('last_name');
        }

        // Utiliser withQueryString() pour conserver les filtres lors de la pagination
        $players = $query->paginate(20)->withQueryString(); 

        // Assurez-vous que $teams est passé à la vue
        return view('frontend.players.index', compact('players', 'teams')); 
    }

    /**
     * Affiche les détails d'un joueur.
     */
    public function show(Player $player)
    {
        $player->load(['team.university']);

        // Statistiques du joueur
        $stats = [
            'goals' => $player->matchEvents()->whereIn('event_type', ['goal', 'penalty_goal'])->count(),
            'assists' => MatchEvent::where('assist_player_id', $player->id)
                ->whereIn('event_type', ['goal', 'penalty_goal'])
                ->count(),
            'yellow_cards' => $player->matchEvents()->where('event_type', 'yellow_card')->count(),
            'red_cards' => $player->matchEvents()->where('event_type', 'red_card')->count(),
            // Compte les matchs joués où le joueur a participé à un événement (but, carton, etc.)
            'matches_played' => MatchEvent::where('player_id', $player->id)->distinct('match_id')->count('match_id'),
        ];

        // Derniers événements
        $recentEvents = $player->matchEvents()
            ->with(['match.homeTeam.university', 'match.awayTeam.university']) // Chargement optimisé
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        return view('frontend.players.show', compact('player', 'stats', 'recentEvents'));
    }
}
