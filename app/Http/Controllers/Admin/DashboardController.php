<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\University;
use App\Models\Team;
use App\Models\Player;
use App\Models\MatchModel;
use App\Models\MatchEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ... (Statistiques globales inchangées)
        $stats = [
            'total_universities' => University::count(),
            'total_teams' => Team::count(),
            'total_players' => Player::count(),
            'total_matches' => MatchModel::count(),
            'matches_played' => MatchModel::where('status', 'finished')->count(),
            'matches_scheduled' => MatchModel::where('status', 'scheduled')->count(),
            'matches_live' => MatchModel::whereIn('status', ['live', 'halftime'])->count(),
            'total_goals' => MatchEvent::where('event_type', 'goal')->count(),
        ];

        // Matchs à venir ou en attente de finalisation
        // CORRECTION : On retire le filtre temporel (>= now()) pour afficher tous les matchs
        // qui n'ont pas encore le statut 'live' ou 'finished'.
        $upcomingMatches = MatchModel::with(['homeTeam.university', 'awayTeam.university'])
            ->whereIn('status', ['scheduled', 'pending']) // Inclut les matchs planifiés et en attente
            ->orderBy('match_date', 'asc') // Tri par date, les plus anciens non finalisés apparaissent en haut
            ->take(5)
            ->get();

        // Matchs en direct (inchangé)
        $liveMatches = MatchModel::with(['homeTeam.university', 'awayTeam.university'])
            ->whereIn('status', ['live', 'halftime'])
            ->get();

        // Classement des buteurs (inchangé)
        $topScorers = Player::withCount(['matchEvents as goals_count' => function ($query) {
                // IMPORTANT : Si vous avez ajouté 'penalty_goal', il faut l'inclure ici si vous voulez le compter comme un but.
                $query->whereIn('event_type', ['goal', 'penalty_goal']); 
            }])
            ->with('team.university')
            ->orderByDesc('goals_count')
            ->take(5)
            ->get();

        // Dernières activités (inchangé)
        $recentEvents = MatchEvent::with([
            'match.homeTeam.university',
            'match.awayTeam.university',
            'player.team.university' 
        ])
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'upcomingMatches',
            'liveMatches',
            'topScorers',
            'recentEvents'
        ));
    }

    public function statistics()
    {
        // Statistiques par équipe (inchangé)
        $teamStats = Team::withCount([
            'players',
            'homeMatches',
            'awayMatches'
        ])->get();

        // Statistiques des matchs par jour (inchangé)
        $matchesByDay = MatchModel::selectRaw('DATE(match_date) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Buts par équipe
        // IMPORTANT : Si vous avez ajouté 'penalty_goal', il faut l'inclure ici.
        $goalsByTeam = MatchEvent::select('team_id', DB::raw('COUNT(*) as goals'))
            ->whereIn('event_type', ['goal', 'penalty_goal'])
            ->groupBy('team_id')
            ->with('team.university')
            ->get();

        return view('admin.statistics', compact('teamStats', 'matchesByDay', 'goalsByTeam'));
    }

    public function reports()
    {
        return view('admin.reports');
    }
}