<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MatchEventOccurred implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $matchId;
    public $eventData;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($matchId, $eventData)
    {
        $this->matchId = $matchId;
        $this->eventData = $eventData;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // On diffuse l'événement sur un canal Privé, spécifique au match.
        // Chaque utilisateur qui regarde ce match devra s'abonner à ce canal.
        return new Channel('match.' . $this->matchId);
    }

    /**
     * Données diffusées côté client.
     */
    public function broadcastWith()
    {
        return array_merge(['match_id' => $this->matchId], $this->eventData);
    }
}
