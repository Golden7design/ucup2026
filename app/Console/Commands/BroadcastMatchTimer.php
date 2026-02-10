<?php

namespace App\Console\Commands;

use App\Models\MatchModel;
use App\Events\MatchStatusOrStatsUpdated;
use Illuminate\Console\Command;
use Carbon\Carbon;

class BroadcastMatchTimer extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'match:broadcast-timer {--match-id=}';

    /**
     * The console command description.
     */
    protected $description = 'Diffuse le chronomètre pour les matchs en direct';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $matchId = $this->option('match-id');

        if ($matchId) {
            // Diffusion pour un match spécifique
            $match = MatchModel::find($matchId);
            
            if (!$match) {
                $this->error("Match #{$matchId} non trouvé");
                return 1;
            }

            if ($match->status !== 'live' || $match->timer_paused_at) {
                $this->info("Match #{$matchId} n'est pas en direct ou est en pause");
                return 0;
            }

            $this->broadcastTimer($match);
            $this->info("Timer broadcasté pour match #{$matchId}: {$match->getFormattedTime()}");
        } else {
            // Diffusion pour tous les matchs en direct
            $liveMatches = MatchModel::where('status', 'live')
                ->whereNull('timer_paused_at')
                ->get();

            if ($liveMatches->isEmpty()) {
                $this->info('Aucun match en direct');
                return 0;
            }

            $count = 0;
            foreach ($liveMatches as $match) {
                $this->broadcastTimer($match);
                $count++;
            }

            $this->info("Timer broadcasté pour {$count} match(s) en direct");
        }

        return 0;
    }

    /**
     * Diffuse le timer pour un match
     */
    protected function broadcastTimer(MatchModel $match)
    {
        event(new MatchStatusOrStatsUpdated($match, [
            'type' => 'timer_tick',
            'elapsed_time' => $match->getElapsedTime(),
            'formatted_time' => $match->getFormattedTime(),
            'broadcasted_at' => Carbon::now()->toIso8601String(),
        ]));
    }
}
