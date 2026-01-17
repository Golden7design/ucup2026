<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MatchModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class RealTimeMatchController extends Controller
{
    // Durée de cache courte pour les données en temps réel
    const CACHE_DURATION = 1;

    /**
     * Récupère les données en temps réel d'un match
     *
     * @param Request $request
     * @param int $matchId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLiveData(Request $request, $matchId)
    {
        try {
            // Désactiver le cache pour cette requête
            $this->disableCache();

            // Récupérer le match avec toutes les relations nécessaires
            $match = MatchModel::with([
                'homeTeam.university',
                'awayTeam.university',
                'matchEvents.player',
                'matchEvents.assistPlayer',
                'matchEvents.outPlayer',
                'matchEvents.team.university'
            ])->findOrFail($matchId);

            // Forcer le rechargement des données
            $match->refresh();

            // Récupérer les timestamps depuis la requête
            $lastEventTime = $request->input('last_event_time');
            $lastUpdateTime = $request->input('last_update_time', 0);

            // Vérifier si le match a été mis à jour depuis la dernière requête
            $matchUpdated = strtotime($match->updated_at) > $lastUpdateTime;

            // Préparer les données de réponse
            $response = [
                'success' => true,
                'timestamp' => strtotime($match->updated_at),
                'has_updates' => $matchUpdated,
                'match' => [
                    'id' => $match->id,
                    'home_score' => $match->home_score ?? 0,
                    'away_score' => $match->away_score ?? 0,
                    'status' => $match->status,
                    'match_time' => $this->calculateMatchTime($match),
                    'updated_at' => strtotime($match->updated_at),
                    'stats' => $this->getMatchStats($match)
                ]
            ];

            // Si le match a été mis à jour, inclure les événements récents
            if ($matchUpdated) {
                $response['match']['events'] = $this->getMatchEvents($match, $lastEventTime);
            }

            return response()->json($response)
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0')
                ->header('Pragma', 'no-cache')
                ->header('Expires', 'Sat, 26 Jul 1997 05:00:00 GMT');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des données',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calcule le temps de jeu actuel
     *
     * @param MatchModel $match
     * @return int|null
     */
    protected function calculateMatchTime($match)
    {
        if ($match->status !== 'live' || !$match->start_time) {
            return null;
        }

        $startTime = Carbon::parse($match->start_time);
        $now = Carbon::now();

        return $startTime->diffInMinutes($now);
    }

    /**
     * Récupère les statistiques du match
     *
     * @param MatchModel $match
     * @return array
     */
    protected function getMatchStats($match)
    {
        return [
            'home_fouls' => $match->home_fouls ?? 0,
            'away_fouls' => $match->away_fouls ?? 0,
            'home_corners' => $match->home_corners ?? 0,
            'away_corners' => $match->away_corners ?? 0,
            'home_yellow_cards' => $match->home_yellow_cards ?? 0,
            'home_red_cards' => $match->home_red_cards ?? 0,
            'away_yellow_cards' => $match->away_yellow_cards ?? 0,
            'away_red_cards' => $match->away_red_cards ?? 0,
        ];
    }

    /**
     * Récupère les événements du match depuis un certain timestamp
     *
     * @param MatchModel $match
     * @param string|null $lastEventTime
     * @return array
     */
    protected function getMatchEvents($match, $lastEventTime = null)
    {
        $query = $match->matchEvents()
            ->with(['player', 'assistPlayer', 'outPlayer', 'team.university'])
            ->orderBy('created_at', 'desc');

        if ($lastEventTime) {
            $query->where('created_at', '>', Carbon::parse($lastEventTime));
        }

        return $query->limit(10)->get()->map(function($event) {
            return [
                'id' => $event->id,
                'minute' => $event->minute,
                'event_type' => $event->event_type,
                'player' => $event->player ? [
                    'id' => $event->player->id,
                    'name' => $event->player->full_name,
                    'number' => $event->player->jersey_number
                ] : null,
                'assist_player' => $event->assistPlayer ? [
                    'id' => $event->assistPlayer->id,
                    'name' => $event->assistPlayer->full_name,
                ] : null,
                'out_player' => $event->outPlayer ? [
                    'id' => $event->outPlayer->id,
                    'name' => $event->outPlayer->full_name,
                ] : null,
                'team_id' => $event->team_id,
                'created_at' => $event->created_at->timestamp
            ];
        })->toArray();
    }

    /**
     * Désactive le cache pour les requêtes
     */
    protected function disableCache()
    {
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
    }
}
