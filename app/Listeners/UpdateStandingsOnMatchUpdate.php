<?php

namespace App\Listeners;

use App\Events\MatchStatusUpdated;
use App\Events\MatchEventOccurred;
use App\Services\StandingService;
use App\Models\MatchModel;

class UpdateStandingsOnMatchUpdate
{
    /**
     * Instance du service de classement
     */
    protected $standingService;

    /**
     * Créer l'écouteur d'événement
     */
    public function __construct(StandingService $standingService)
    {
        $this->standingService = $standingService;
    }

    /**
     * Gérer l'événement de mise à jour de statut de match
     */
    public function handle(MatchStatusUpdated $event): void
    {
        $match = $event->match;
        
        // Mettre à jour les classements uniquement si le match est terminé et qu'il s'agit d'un match de tournoi
        if ($match->status === 'finished' && $match->match_type === 'tournament') {
            $this->standingService->updateStandingsForMatch($match);
        }
    }

    /**
     * Gérer l'événement d'événement de match (but, carton, etc.)
     */
    public function handleMatchEventOccurred(MatchEventOccurred $event): void
    {
        // Si l'événement concerne un match terminé, mettre à jour les classements
        $match = MatchModel::find($event->matchId);
        
        if ($match && $match->status === 'finished' && $match->match_type === 'tournament') {
            $this->standingService->updateStandingsForMatch($match);
        }
    }
}