<?php

namespace App\Events;

use App\Models\MatchModel;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MatchStatusOrStatsUpdated implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public $matchId;
    public $updateData;

    public function __construct(MatchModel $match, array $updateData)
    {
        $this->matchId = $match->id;
        $this->updateData = array_merge([
            'status' => $match->status,
            'start_time' => $match->start_time ? $match->start_time->timestamp * 1000 : null,
            'timer_paused_at' => $match->timer_paused_at ? $match->timer_paused_at->timestamp * 1000 : null,
            'elapsed_time' => $match->getElapsedTime(),
            'formatted_time' => $match->getFormattedTime(),
            'home_fouls' => $match->home_fouls,
            'away_fouls' => $match->away_fouls,
            'home_corners' => $match->home_corners,
            'away_corners' => $match->away_corners,
            // ... autres stats ...
        ], $updateData);
    }

    public function broadcastOn()
    {
        return new Channel('match.' . $this->matchId);
    }

    /**
     * Données diffusées côté client.
     */
    public function broadcastWith()
    {
        return array_merge(['match_id' => $this->matchId], $this->updateData);
    }
}
