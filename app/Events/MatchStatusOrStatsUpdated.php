<?php

namespace App\Events;

use App\Models\MatchModel;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MatchStatusOrStatsUpdated implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $matchId;
    public $updateData;

    public function __construct(MatchModel $match, array $updateData)
    {
        $this->matchId = $match->id;
        $this->updateData = array_merge([
            'status' => $match->status,
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
    
    public function broadcastAs()
    {
        return 'status.stats.updated';
    }
}