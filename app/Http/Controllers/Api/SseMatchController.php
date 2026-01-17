<?php
// app/Http/Controllers/Api/SseMatchController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MatchModel;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SseMatchController extends Controller
{
    /**
     * Flux SSE pour les mises à jour en temps réel
     */
    public function stream(Request $request, $matchId)
    {
        // Désactiver la limite de temps d'exécution
        set_time_limit(0);
        ini_set('max_execution_time', 0);

        // Désactiver la compression de sortie
        if (ini_get('zlib.output_compression')) {
            ini_set('zlib.output_compression', false);
        }

        // Nettoyer les buffers de sortie
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        // Configurer les headers pour SSE
        $response = new StreamedResponse(function() use ($matchId) {
            $lastUpdateTime = 0;
            $lastPingTime = time();

            while (true) {
                try {
                    // Envoyer un ping toutes les 30 secondes pour maintenir la connexion
                    if (time() - $lastPingTime >= 30) {
                        echo ": ping\n\n";
                        $lastPingTime = time();
                        ob_flush();
                        flush();
                    }

                    // Récupérer le match avec les dernières données
                    $match = MatchModel::with([
                        'homeTeam.university',
                        'awayTeam.university'
                    ])->find($matchId);

                    if (!$match) {
                        echo "event: error\ndata: {\"message\":\"Match non trouvé\"}\n\n";
                        ob_flush();
                        flush();
                        break;
                    }

                    // Vérifier si le match a été mis à jour
                    $currentUpdateTime = strtotime($match->updated_at);

                    if ($currentUpdateTime > $lastUpdateTime) {
                        $lastUpdateTime = $currentUpdateTime;

                        // Calculer le temps de jeu
                        $matchTime = null;
                        if ($match->status === 'live' && $match->start_time) {
                            $startTime = new \DateTime($match->start_time);
                            $now = new \DateTime();
                            $matchTime = $startTime->diff($now)->i; // Minutes écoulées
                        }

                        // Envoyer les données
                        echo "data: " . json_encode([
                            'id' => $match->id,
                            'home_score' => $match->home_score ?? 0,
                            'away_score' => $match->away_score ?? 0,
                            'status' => $match->status,
                            'match_time' => $matchTime,
                            'updated_at' => $match->updated_at->toDateTimeString(),
                            'home_team' => $match->homeTeam->university->short_name ?? 'Équipe',
                            'away_team' => $match->awayTeam->university->short_name ?? 'Équipe',
                            'home_formation' => $match->home_formation ?? '4-3-3',
                            'away_formation' => $match->away_formation ?? '4-3-3'
                        ]) . "\n\n";

                        ob_flush();
                        flush();
                    }

                    sleep(1); // Attendre 1 seconde avant de vérifier à nouveau

                } catch (\Exception $e) {
                    // En cas d'erreur, envoyer un message d'erreur et continuer
                    echo "event: error\ndata: {\"message\":\"".addslashes($e->getMessage())."\"}\n\n";
                    ob_flush();
                    flush();
                    sleep(2); // Attendre plus longtemps après une erreur
                }
            }
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');
        $response->headers->set('X-Accel-Buffering', 'no');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }
}
