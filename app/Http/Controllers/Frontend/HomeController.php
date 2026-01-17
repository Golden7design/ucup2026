<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\MatchModel; 
use App\Models\Player;
use App\Models\Standing; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $today = Carbon::today(); // Début de la journée actuelle (00:00:00)

        // 1. Matchs en direct (LIVE)
        $liveMatches = MatchModel::with(['homeTeam.university', 'awayTeam.university'])
            ->whereIn('status', ['live', 'halftime'])
            ->get();

        // 2. Matchs terminés (RÉSULTATS PASSÉS/RÉCENTS)
        // Les 5 derniers matchs dont le statut est 'finished'
        $recentMatches = MatchModel::with(['homeTeam.university', 'awayTeam.university'])
            ->where('status', 'finished')
            ->orderBy('match_date', 'desc')
            ->take(5)
            ->get();

        // 3. & 4. Prochains matchs (UPCOMING) et Matchs du jour (TODAY UPCOMING)
        // Nous récupérons tous les matchs à venir en premier
        $allUpcomingMatches = MatchModel::with(['homeTeam.university', 'awayTeam.university'])
            ->where('status', 'scheduled')
            ->where('match_date', '>', $now) // Doit être strictement dans le futur (date et heure)
            ->orderBy('match_date')
            ->get();
            
        // Filtrage pour les Matchs du Jour (ceux dont la date est aujourd'hui)
        $todayUpcomingMatches = $allUpcomingMatches->filter(function ($match) use ($today) {
            // Assurez-vous que match_date est un objet Carbon (sauf si votre modèle le cast déjà)
            return Carbon::parse($match->match_date)->isSameDay($today);
        });

        // Filtrage pour les Prochains Matchs (ceux des jours suivants, limités à 5)
        $upcomingMatches = $allUpcomingMatches->filter(function ($match) use ($today) {
            return !Carbon::parse($match->match_date)->isSameDay($today);
        })->take(5);
            
        // 5. Classement (STANDINGS)
        $standings = Standing::with('team.university') 
             ->orderBy('points', 'desc')
             ->take(5)
             ->get();

        // 6. Top Buteurs (TOP SCORERS) - CORRECTION DU TRI
        $scorerStats = DB::table('match_events')
            ->select('player_id', DB::raw('COUNT(id) as goals'))
            ->where('event_type', 'goal')
            ->groupBy('player_id')
            ->orderByDesc('goals') // Tri selon le nombre de buts
            ->take(5)
            ->get();

        $playerIds = $scorerStats->pluck('player_id')->toArray();
        $goalsMap = $scorerStats->keyBy('player_id')->map(fn ($item) => $item->goals);

        $topScorers = Player::whereIn('id', $playerIds)
            ->with('team.university')
            // Correction : Vérification que $playerIds n'est pas vide avant d'utiliser un tri personnalisé
            ->when(!empty($playerIds), function ($query) use ($playerIds) {
                $caseStatement = 'CASE id ';
                foreach ($playerIds as $index => $id) {
                    $caseStatement .= "WHEN $id THEN $index ";
                }
                $caseStatement .= 'END';
                return $query->orderByRaw($caseStatement);
            })
            ->get()
            ->map(function ($player) use ($goalsMap) {
                $player->goals = $goalsMap->get($player->id, 0); 
                return $player;
            });

        return view('frontend.home', compact(
            'liveMatches',
            'upcomingMatches',
            'todayUpcomingMatches', 
            'standings',
            'topScorers',
            'recentMatches'
        ));
    }
}