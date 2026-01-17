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

        if ($match->status !== 'live') {
            return response()->json([
                'success' => false,
                'message' => 'Le match n\'est pas en cours'
            ]);
        }

        return response()->json([
            'success' => true,
            'elapsed_time' => $match->getElapsedTime(),
            'formatted_time' => $match->getFormattedTime()
        ]);
    }
}
