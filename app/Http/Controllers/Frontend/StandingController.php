<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Standing;
use App\Services\StandingService;
use Illuminate\Database\Eloquent\Builder;

class StandingController extends Controller
{
    /**
     * Instance du service de classement
     */
    protected $standingService;

    /**
     * Créer une nouvelle instance du contrôleur
     */
    public function __construct(StandingService $standingService)
    {
        $this->standingService = $standingService;
    }

    /**
     * Récupère et trie les classements généraux et par groupes.
     */
    public function index()
    {
        // Utiliser le service pour obtenir les classements mis à jour
        $standingsByGroup = $this->standingService->getGroupedStandings();
        $generalStandings = $this->standingService->getLiveStandings();

        if ($generalStandings->isEmpty()) {
            return view('frontend.standings.index', ['standingsByGroup' => collect(), 'generalStandings' => collect()]);
        }

        return view('frontend.standings.index', compact('standingsByGroup', 'generalStandings'));
    }

    /**
     * Affiche le classement d'un groupe spécifique. (Optionnel si géré par index)
     */
    public function group(string $group)
    {
        // Tri spécifique pour un groupe
        $standings = $this->standingService->getLiveStandings($group);
        
        // Récupérer tous les groupes pour la navigation
        $groups = Standing::distinct()->pluck('group')->sort()->all();

        return view('frontend.standings.group', compact('standings', 'groups', 'group'));
    }
}