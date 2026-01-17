<?php
// app/Http/Controllers/Api/MatchController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MatchModel;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class MatchController extends Controller
{
    /**
     * Récupère les mises à jour en temps réel pour un match
     */
    public function liveUpdate(MatchModel $match, Request $request)
    {
        // Désactiver le cache pour cette requête
        $this->disableCacheForLiveUpdates();

        // Forcer le rechargement des données
        $match->refresh();

        // Charger les relations nécessaires
        $match->load([
            'homeTeam.university',
            'awayTeam.university',
            'matchEvents.player',
            'matchEvents.assistPlayer',
            'matchEvents.outPlayer',
            'matchEvents.team.university'
        ]);

        // Initialiser les collections si null
        $match->matchEvents = $match->matchEvents ?? collect();

        // Récupérer les timestamps
        $lastEventTime = $request->input('last_event_time', '1970-01-01 00:00:00');
        $lastUpdateTime = $request->input('last_update_time', '1970-01-01 00:00:00');

        // Vérifier si le match a été mis à jour depuis la dernière requête
        $matchUpdated = $match->updated_at > $lastUpdateTime;

        // Récupérer les nouveaux événements
        $newEvents = collect();
        if ($match->matchEvents->count() > 0) {
            $newEvents = $match->matchEvents
                ->where('created_at', '>', $lastEventTime)
                ->sortByDesc('created_at')
                ->take(10);
        }

        // Calculer le temps écoulé depuis le début du match
        $matchTime = null;
        if ($match->status === 'live' && $match->start_time) {
            $startTime = Carbon::parse($match->start_time);
            $now = Carbon::now();
            $matchTime = $startTime->diffInMinutes($now);
        }

        // Obtenir les statistiques détaillées
        $statistics = $match->getMatchStatistics();

        // Préparer la réponse avec ETag pour optimiser les requêtes
        $responseData = [
            'success' => true,
            'timestamp' => now()->toDateTimeString(),
            'match_updated' => $matchUpdated,
            'match' => [
                'id' => $match->id,
                'home_score' => $match->home_score ?? 0,
                'away_score' => $match->away_score ?? 0,
                'status' => $match->status,
                'match_time' => $matchTime,
                'home_fouls' => $match->home_fouls ?? 0,
                'away_fouls' => $match->away_fouls ?? 0,
                'home_corners' => $match->home_corners ?? 0,
                'away_corners' => $match->away_corners ?? 0,
                'home_yellow_cards' => $match->home_yellow_cards ?? 0,
                'home_red_cards' => $match->home_red_cards ?? 0,
                'away_yellow_cards' => $match->away_yellow_cards ?? 0,
                'away_red_cards' => $match->away_red_cards ?? 0,
                'updated_at' => $match->updated_at->toDateTimeString(),
            ],
            'statistics' => $statistics,
            'new_events' => $newEvents->map(function($event) {
                return [
                    'id' => $event->id,
                    'minute' => $event->minute,
                    'event_type' => $event->event_type,
                    'player' => $event->player ? [
                        'id' => $event->player->id,
                        'full_name' => $event->player->full_name,
                        'jersey_number' => $event->player->jersey_number,
                    ] : null,
                    'assist_player' => $event->assistPlayer ? [
                        'id' => $event->assistPlayer->id,
                        'full_name' => $event->assistPlayer->full_name,
                        'jersey_number' => $event->assistPlayer->jersey_number,
                    ] : null,
                    'out_player' => $event->outPlayer ? [
                        'id' => $event->outPlayer->id,
                        'full_name' => $event->outPlayer->full_name,
                        'jersey_number' => $event->outPlayer->jersey_number,
                    ] : null,
                    'team_id' => $event->team_id,
                    'created_at' => $event->created_at->toDateTimeString(),
                ];
            }),
            'last_event_time' => $newEvents->count() > 0 ?
                $newEvents->sortByDesc('created_at')->first()->created_at->toDateTimeString() :
                $lastEventTime
        ];

        // Générer un ETag basé sur les données
        $etag = md5(json_encode($responseData));
        $requestEtag = $request->header('If-None-Match');

        if ($requestEtag === $etag) {
            return response()->json(null, 304);
        }

        return response()->json($responseData, 200)
            ->header('ETag', $etag)
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0')
            ->header('Pragma', 'no-cache');
    }

    /**
     * Récupère la timeline complète d'un match
     */
    public function timeline(MatchModel $match)
    {
        $match->load([
            'homeTeam.university',
            'awayTeam.university',
            'matchEvents.player',
            'matchEvents.assistPlayer',
            'matchEvents.outPlayer',
            'matchEvents.team.university'
        ]);

        $eventsByType = $match->getEventsByType();
        $goals = $match->getGoals();
        $cards = $match->getCards();
        $substitutions = $match->getSubstitutions();

        return response()->json([
            'success' => true,
            'match_id' => $match->id,
            'home_team' => [
                'id' => $match->homeTeam->id,
                'name' => $match->homeTeam->name,
                'short_name' => $match->homeTeam->university->short_name ?? $match->homeTeam->name,
                'logo' => $match->homeTeam->university->logo ? asset('storage/' . $match->homeTeam->university->logo) : null
            ],
            'away_team' => [
                'id' => $match->awayTeam->id,
                'name' => $match->awayTeam->name,
                'short_name' => $match->awayTeam->university->short_name ?? $match->awayTeam->name,
                'logo' => $match->awayTeam->university->logo ? asset('storage/' . $match->awayTeam->university->logo) : null
            ],
            'score' => [
                'home' => $match->home_score ?? 0,
                'away' => $match->away_score ?? 0
            ],
            'status' => $match->status,
            'match_date' => $match->match_date->toDateTimeString(),
            'venue' => $match->venue,
            'events_by_type' => $eventsByType->map(function ($events, $type) {
                return [
                    'type' => $type,
                    'events' => $events->map(function ($event) {
                        return [
                            'id' => $event->id,
                            'minute' => $event->minute,
                            'player' => $event->player ? [
                                'id' => $event->player->id,
                                'full_name' => $event->player->full_name,
                                'jersey_number' => $event->player->jersey_number,
                            ] : null,
                            'assist_player' => $event->assistPlayer ? [
                                'id' => $event->assistPlayer->id,
                                'full_name' => $event->assistPlayer->full_name,
                                'jersey_number' => $event->assistPlayer->jersey_number,
                            ] : null,
                            'out_player' => $event->outPlayer ? [
                                'id' => $event->outPlayer->id,
                                'full_name' => $event->outPlayer->full_name,
                                'jersey_number' => $event->outPlayer->jersey_number,
                            ] : null,
                            'team_id' => $event->team_id,
                        ];
                    })
                ];
            }),
            'goals' => $goals->map(function ($goal) {
                return [
                    'id' => $goal->id,
                    'minute' => $goal->minute,
                    'player' => $goal->player ? [
                        'id' => $goal->player->id,
                        'full_name' => $goal->player->full_name,
                        'jersey_number' => $goal->player->jersey_number,
                    ] : null,
                    'assist_player' => $goal->assistPlayer ? [
                        'id' => $goal->assistPlayer->id,
                        'full_name' => $goal->assistPlayer->full_name,
                        'jersey_number' => $goal->assistPlayer->jersey_number,
                    ] : null,
                    'team_id' => $goal->team_id,
                ];
            }),
            'cards' => $cards->map(function ($card) {
                return [
                    'id' => $card->id,
                    'minute' => $card->minute,
                    'type' => $card->event_type,
                    'player' => $card->player ? [
                        'id' => $card->player->id,
                        'full_name' => $card->player->full_name,
                        'jersey_number' => $card->player->jersey_number,
                    ] : null,
                    'team_id' => $card->team_id,
                ];
            }),
            'substitutions' => $substitutions->map(function ($sub) {
                return [
                    'id' => $sub->id,
                    'minute' => $sub->minute,
                    'player_in' => $sub->player ? [
                        'id' => $sub->player->id,
                        'full_name' => $sub->player->full_name,
                        'jersey_number' => $sub->player->jersey_number,
                    ] : null,
                    'player_out' => $sub->outPlayer ? [
                        'id' => $sub->outPlayer->id,
                        'full_name' => $sub->outPlayer->full_name,
                        'jersey_number' => $sub->outPlayer->jersey_number,
                    ] : null,
                    'team_id' => $sub->team_id,
                ];
            }),
            'statistics' => $match->getMatchStatistics()
        ]);
    }

    /**
     * Récupère les données en temps réel pour un match
     */
    public function getLiveData(MatchModel $match)
    {
        $match->load([
            'homeTeam.university',
            'awayTeam.university',
            'matchEvents.player',
            'matchEvents.assistPlayer',
            'matchEvents.outPlayer',
            'matchEvents.team.university',
            'lineups.player'
        ]);

        $matchTime = null;
        if ($match->status === 'live' && $match->start_time) {
            $startTime = Carbon::parse($match->start_time);
            $now = Carbon::now();
            $matchTime = $startTime->diffInMinutes($now);
        }

        // Obtenir les compositions d'équipe
        $homeLineups = $match->lineups->where('team_id', $match->home_team_id)->values();
        $awayLineups = $match->lineups->where('team_id', $match->away_team_id)->values();

        $homeStarters = $homeLineups->where('is_starter', true)
            ->sortBy(function ($lineup) {
                return $lineup->order_key ?? ($lineup->player->jersey_number ?? 999);
            })->values();

        $awayStarters = $awayLineups->where('is_starter', true)
            ->sortBy(function ($lineup) {
                return $lineup->order_key ?? ($lineup->player->jersey_number ?? 999);
            })->values();

        return response()->json([
            'success' => true,
            'match' => [
                'id' => $match->id,
                'home_team' => [
                    'id' => $match->homeTeam->id,
                    'name' => $match->homeTeam->name,
                    'short_name' => $match->homeTeam->university->short_name ?? $match->homeTeam->name,
                    'logo' => $match->homeTeam->university->logo ? asset('storage/' . $match->homeTeam->university->logo) : null,
                    'score' => $match->home_score ?? 0,
                    'formation' => $match->home_formation ?? 'Unknown',
                    'coach' => $match->home_coach ?? 'Unknown',
                ],
                'away_team' => [
                    'id' => $match->awayTeam->id,
                    'name' => $match->awayTeam->name,
                    'short_name' => $match->awayTeam->university->short_name ?? $match->awayTeam->name,
                    'logo' => $match->awayTeam->university->logo ? asset('storage/' . $match->awayTeam->university->logo) : null,
                    'score' => $match->away_score ?? 0,
                    'formation' => $match->away_formation ?? 'Unknown',
                    'coach' => $match->away_coach ?? 'Unknown',
                ],
                'status' => $match->status,
                'match_time' => $matchTime,
                'match_date' => $match->match_date->toDateTimeString(),
                'venue' => $match->venue,
                'referee' => $match->referee ?? 'Unknown',
                'attendance' => $match->attendance ?? 0,
                'weather' => $match->weather ?? 'Unknown',
            ],
            'lineups' => [
                'home' => [
                    'starters' => $homeStarters->map(function ($lineup) {
                        return [
                            'player_id' => $lineup->player->id,
                            'full_name' => $lineup->player->full_name,
                            'jersey_number' => $lineup->player->jersey_number,
                            'position' => $lineup->player->position,
                        ];
                    }),
                    'substitutes' => $homeLineups->where('is_starter', false)->map(function ($lineup) {
                        return [
                            'player_id' => $lineup->player->id,
                            'full_name' => $lineup->player->full_name,
                            'jersey_number' => $lineup->player->jersey_number,
                            'position' => $lineup->player->position,
                        ];
                    })
                ],
                'away' => [
                    'starters' => $awayStarters->map(function ($lineup) {
                        return [
                            'player_id' => $lineup->player->id,
                            'full_name' => $lineup->player->full_name,
                            'jersey_number' => $lineup->player->jersey_number,
                            'position' => $lineup->player->position,
                        ];
                    }),
                    'substitutes' => $awayLineups->where('is_starter', false)->map(function ($lineup) {
                        return [
                            'player_id' => $lineup->player->id,
                            'full_name' => $lineup->player->full_name,
                            'jersey_number' => $lineup->player->jersey_number,
                            'position' => $lineup->player->position,
                        ];
                    })
                ]
            ],
            'events' => $match->matchEvents->map(function ($event) {
                return [
                    'id' => $event->id,
                    'minute' => $event->minute,
                    'event_type' => $event->event_type,
                    'player' => $event->player ? [
                        'id' => $event->player->id,
                        'full_name' => $event->player->full_name,
                        'jersey_number' => $event->player->jersey_number,
                    ] : null,
                    'assist_player' => $event->assistPlayer ? [
                        'id' => $event->assistPlayer->id,
                        'full_name' => $event->assistPlayer->full_name,
                        'jersey_number' => $event->assistPlayer->jersey_number,
                    ] : null,
                    'out_player' => $event->outPlayer ? [
                        'id' => $event->outPlayer->id,
                        'full_name' => $event->outPlayer->full_name,
                        'jersey_number' => $event->outPlayer->jersey_number,
                    ] : null,
                    'team_id' => $event->team_id,
                ];
            }),
            'statistics' => $match->getMatchStatistics()
        ]);
    }

    /**
     * Désactive le cache pour les requêtes live-update
     */
    protected function disableCacheForLiveUpdates()
    {
        header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        header('Pragma: no-cache');
        header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
    }
}
