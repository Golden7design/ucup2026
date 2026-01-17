<?php

namespace App\Events;

use App\Models\MatchModel;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MatchEventsUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $matchId;
    public $matchScore; // Pour envoyer le score actuel avec l'événement

    /**
     * Crée une nouvelle instance de l'événement.
     *
     * @return void
     */
    public function __construct(MatchModel $match)
    {
        $this->matchId = $match->id;
        $this->matchScore = [
            'home_score' => $match->home_score,
            'away_score' => $match->away_score,
        ];
    }

    /**
     * Obtenez les canaux sur lesquels l'événement doit être diffusé.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // Canal public (tout le monde peut s'abonner pour ce match)
        return new Channel('match.' . $this->matchId);
    }
    
    /**
     * Le nom que l'événement aura en JavaScript.
     */
    public function broadcastAs()
    {
        return 'match.updated';
    }
}