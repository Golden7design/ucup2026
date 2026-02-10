<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\MatchModel;
use Illuminate\Http\Request;

class MatchTimerController extends Controller
{
    public function getTimer($matchId)
    {
        $match = MatchModel::findOrFail($matchId);

        // IMPORTANT: l'API ne doit pas démarrer ou modifier le chrono.
        // Le chrono est piloté côté admin uniquement.

        $label = null;
        if ($match->timer_paused_at && $match->status === 'live') {
            $label = 'PAUSE';
        } elseif ($match->status === 'halftime') {
            $label = 'MI-TEMPS';
        } elseif ($match->status === 'finished') {
            $label = 'FIN';
        } elseif ($match->status === 'scheduled') {
            $label = 'À VENIR';
        }

        $elapsedSeconds = (int) $match->getElapsedTime();
        $formatted = $match->getFormattedTime();

        return response()->json([
            'success' => true,
            'status' => $match->status,
            'label' => $label,
            'is_paused' => (bool) $match->timer_paused_at,
            'elapsed_time' => $elapsedSeconds,
            'formatted_time' => $formatted,
            'start_time' => $match->start_time ? $match->start_time->toIso8601String() : null,
            'server_time' => now()->toIso8601String()
        ]);
    }
}
