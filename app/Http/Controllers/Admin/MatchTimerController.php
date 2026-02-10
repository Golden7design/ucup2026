<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MatchModel;
use Illuminate\Http\Request;
use App\Events\MatchStatusOrStatsUpdated;

class MatchTimerController extends Controller
{
    /**
     * Démarre le minuteur du match
     */
    public function startTimer(Request $request, $matchId)
    {
        $match = MatchModel::findOrFail($matchId);
        if (in_array($match->status, ['scheduled', 'finished'], true)) {
            $match->elapsed_time = 0;
        }
        $match->startMatchTimer();
        $match->status = 'live';
        $match->save();

        event(new MatchStatusOrStatsUpdated($match, ['status' => $match->status]));

        return response()->json(['success' => true, 'message' => 'Timer started']);
    }

    /**
     * Met en pause le minuteur du match
     */
    public function pauseTimer(Request $request, $matchId)
    {
        $match = MatchModel::findOrFail($matchId);
        $match->pauseMatchTimer();
        $match->status = 'live';
        $match->save();

        event(new MatchStatusOrStatsUpdated($match, ['status' => $match->status]));

        return response()->json(['success' => true, 'message' => 'Timer paused']);
    }

    /**
     * Reprend le minuteur du match
     */
    public function resumeTimer(Request $request, $matchId)
    {
        $match = MatchModel::findOrFail($matchId);
        $match->resumeMatchTimer();
        $match->status = 'live';
        $match->save();

        event(new MatchStatusOrStatsUpdated($match, ['status' => $match->status]));

        return response()->json(['success' => true, 'message' => 'Timer resumed']);
    }

    /**
     * Arrête le minuteur du match
     */
    public function stopTimer(Request $request, $matchId)
    {
        $match = MatchModel::findOrFail($matchId);
        $match->stopMatchTimer();
        $match->status = 'finished';
        $match->save();

        event(new MatchStatusOrStatsUpdated($match, ['status' => $match->status]));

        return response()->json(['success' => true, 'message' => 'Timer stopped']);
    }

    /**
     * Ajoute du temps additionnel à la première mi-temps
     */
    public function addAdditionalTimeFirstHalf(Request $request, $matchId)
    {
        $request->validate([
            'minutes' => 'required|integer|min:0'
        ]);

        $match = MatchModel::findOrFail($matchId);
        $match->addAdditionalTimeFirstHalf($request->minutes);

        return response()->json(['success' => true, 'message' => 'Additional time added to first half']);
    }

    /**
     * Ajoute du temps additionnel à la deuxième mi-temps
     */
    public function addAdditionalTimeSecondHalf(Request $request, $matchId)
    {
        $request->validate([
            'minutes' => 'required|integer|min:0'
        ]);

        $match = MatchModel::findOrFail($matchId);
        $match->addAdditionalTimeSecondHalf($request->minutes);

        return response()->json(['success' => true, 'message' => 'Additional time added to second half']);
    }

    /**
     * Active les prolongations
     */
    public function enableExtraTime(Request $request, $matchId)
    {
        $match = MatchModel::findOrFail($matchId);
        $match->enableExtraTime();

        return response()->json(['success' => true, 'message' => 'Extra time enabled']);
    }

    /**
     * Désactive les prolongations
     */
    public function disableExtraTime(Request $request, $matchId)
    {
        $match = MatchModel::findOrFail($matchId);
        $match->disableExtraTime();

        return response()->json(['success' => true, 'message' => 'Extra time disabled']);
    }

    /**
     * Active les tirs au but
     */
    public function enablePenaltyShootout(Request $request, $matchId)
    {
        $match = MatchModel::findOrFail($matchId);
        $match->enablePenaltyShootout();

        return response()->json(['success' => true, 'message' => 'Penalty shootout enabled']);
    }

    /**
     * Désactive les tirs au but
     */
    public function disablePenaltyShootout(Request $request, $matchId)
    {
        $match = MatchModel::findOrFail($matchId);
        $match->disablePenaltyShootout();

        return response()->json(['success' => true, 'message' => 'Penalty shootout disabled']);
    }

    /**
     * Obtient le temps écoulé du match
     */
    public function getElapsedTime(Request $request, $matchId)
    {
        $match = MatchModel::findOrFail($matchId);
        $elapsedTime = $match->getElapsedTime();
        $formattedTime = $match->getFormattedTime();

        return response()->json([
            'success' => true,
            'elapsed_time' => $elapsedTime,
            'formatted_time' => $formattedTime
        ]);
    }
}
