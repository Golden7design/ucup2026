<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MatchModel;
use App\Events\MatchStatusOrStatsUpdated;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MatchTimerDebugController extends Controller
{
    /**
     * Debug endpoint pour vérifier l'état du timer
     */
    public function debug($matchId)
    {
        $match = MatchModel::findOrFail($matchId);
        
        // Valeurs brutes
        $startTime = $match->start_time;
        $timerPausedAt = $match->timer_paused_at;
        $status = $match->status;
        $elapsedTimeDb = $match->elapsed_time;
        
        // Vérifications
        $hasStartTime = !empty($startTime);
        $isPaused = !empty($timerPausedAt);
        $statusIsLive = ($status === 'live');
        
        // Calcul du temps
        $now = Carbon::now();
        $getElapsedTime = $match->getElapsedTime();
        
        // Test de calcul manuel (en UTC)
        $manualElapsed = 0;
        if ($hasStartTime && !$isPaused && $statusIsLive) {
            $start = Carbon::parse($startTime)->timezone('UTC');
            $nowUtc = Carbon::now()->timezone('UTC');
            $manualElapsed = $start->diffInSeconds($nowUtc, false);
        }
        
        $result = [
            'match_id' => $match->id,
            'status' => $status,
            'status_is_live' => $statusIsLive,
            'start_time' => $startTime ? $startTime->toIso8601String() : null,
            'timer_paused_at' => $timerPausedAt ? $timerPausedAt->toIso8601String() : null,
            'has_start_time' => $hasStartTime,
            'is_paused' => $isPaused,
            'elapsed_time_db' => $elapsedTimeDb,
            'now' => $now->toIso8601String(),
            'get_elapsed_time' => $getElapsedTime,
            'get_formatted_time' => $match->getFormattedTime(),
            'manual_elapsed_calculation' => $manualElapsed,
            'is_live' => $match->isLive(),
            'is_timer_running' => $match->isTimerRunning(),
        ];
        
        // Log pour debug
        \Log::debug("Timer Debug Match #{$matchId}", $result);
        
        return response()->json($result);
    }
    
    /**
     * Tester le broadcast du timer
     */
    public function testBroadcast($matchId)
    {
        $match = MatchModel::findOrFail($matchId);
        
        \Log::debug("=== TEST BROADCAST ===");
        \Log::debug("Status: " . $match->status);
        \Log::debug("Start time: " . ($match->start_time ? $match->start_time->toIso8601String() : 'null'));
        \Log::debug("Timer paused at: " . ($match->timer_paused_at ? $match->timer_paused_at->toIso8601String() : 'null'));
        \Log::debug("Elapsed time (db): " . $match->elapsed_time);
        \Log::debug("getElapsedTime(): " . $match->getElapsedTime());
        
        // Effectuer le broadcast
        event(new MatchStatusOrStatsUpdated($match, [
            'type' => 'test_broadcast',
            'elapsed_time' => $match->getElapsedTime(),
            'formatted_time' => $match->getFormattedTime(),
            'broadcasted_at' => Carbon::now()->toIso8601String(),
        ]));
        
        return response()->json([
            'message' => 'Broadcast effectué',
            'match_id' => $match->id,
            'status' => $match->status,
            'elapsed_time_broadcasted' => $match->getElapsedTime(),
            'formatted_time' => $match->getFormattedTime(),
            'timestamp' => Carbon::now()->toIso8601String(),
        ]);
    }
}
