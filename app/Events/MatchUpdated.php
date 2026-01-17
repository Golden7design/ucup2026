<?php
// app/Events/MatchUpdated.php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MatchUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $matchId;
    public $homeScore;
    public $awayScore;
    public $status;
    public $updatedAt;
    public $startTime;

    public function __construct($matchId, $homeScore, $awayScore, $status, $updatedAt, $startTime = null)
    {
        $this->matchId = $matchId;
        $this->homeScore = $homeScore;
        $this->awayScore = $awayScore;
        $this->status = $status;
        $this->updatedAt = $updatedAt;
        $this->startTime = $startTime;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return ['match.' . $this->matchId];
    }
}
