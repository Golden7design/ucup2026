<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\MatchModel; // CORRECTION 1: Utiliser MatchModel si c'est le nom de votre modèle (comme dans MatchController)
use App\Models\MatchEvent;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        // CORRECTION 3: Utiliser la pagination pour que $teams->links() fonctionne dans la vue
        $teams = Team::with('university')->withCount('players')->paginate(20); 
        return view('frontend.teams.index', compact('teams'));
    }

    public function show(Team $team)
    {
        $team->load([
            'university',
            'players',
            'standing'
        ]);

        // CORRECTION 2: Utilisation de MatchModel (ou Match si c'est votre nom de modèle)
        // J'utilise MatchModel car c'était le modèle utilisé dans MatchController.php.
        // Si votre modèle de Match s'appelle Match, remplacez MatchModel par Match.
        $matches = MatchModel::where(function ($query) use ($team) {
            $query->where('home_team_id', $team->id)
                  ->orWhere('away_team_id', $team->id);
        })
        ->with(['homeTeam', 'awayTeam'])
        ->orderByDesc('match_date')
        ->get();

        return view('frontend.teams.show', compact('team', 'matches'));
    }
}