<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MatchModel; 
use App\Models\MatchEvent;
use App\Models\Player; 
use App\Models\MatchLineup; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Admin\LiveLineupRequest; 
use Carbon\Carbon;
use App\Events\MatchEventOccurred; // Événement pour les BUTS/CARTONS/SUBS
use App\Events\MatchStatusOrStatsUpdated; // ✨ NOUVEL Événement pour STATS et STATUS

class LiveMatchController extends Controller
{
    /**
     * Affiche la liste des matchs pour le Live Center, triés par statut et date.
     */
    public function index()
    {
        $matches = MatchModel::with(['homeTeam.university', 'awayTeam.university'])
            // 1. Trier par Statut : Live (1), Mi-temps (2), À venir (3), Autres (4)
            ->orderByRaw("
                CASE status
                    WHEN 'live' THEN 1
                    WHEN 'halftime' THEN 2
                    WHEN 'scheduled' THEN 3
                    ELSE 4
                END
            ")
            // 2. Pour les matchs de même statut, trier par date/heure du match ASC
            ->orderBy('match_date', 'asc')
            ->paginate(10); 

        return view('admin.live.index', compact('matches'));
    }

    /**
     * Charge les joueurs d'une équipe pour un match.
     */
    public function getPlayers(MatchModel $match, $teamId)
    {
        $players = Player::where('team_id', $teamId)
            ->orderBy('jersey_number')
            ->get();

        return response()->json($players);
    }

    //----------------------------------------------------------------------

    /**
     * Affiche la vue de gestion du match en direct.
     */
    public function show(MatchModel $match)
    {
        // ... (La logique de la méthode show reste inchangée) ...
        // Le code de cette méthode est correct pour la récupération des données
        $match->load(['homeTeam.university', 'awayTeam.university']); 

        $allHomePlayers = Player::where('team_id', $match->home_team_id)
                             ->orderBy('jersey_number')
                             ->get();
                             
        $allAwayPlayers = Player::where('team_id', $match->away_team_id)
                             ->orderBy('jersey_number')
                             ->get();

        $allLineups = MatchLineup::where('match_id', $match->id)->with('player')->get(); 

        $homeStarters = $allLineups->where('team_id', $match->home_team_id)->where('role', 'starter')->sortBy(fn($item) => $item->player->jersey_number);
        $homeBench = $allLineups->where('team_id', $match->home_team_id)->where('role', 'substitute')->sortBy(fn($item) => $item->player->jersey_number);
        
        $awayStarters = $allLineups->where('team_id', $match->away_team_id)->where('role', 'starter')->sortBy(fn($item) => $item->player->jersey_number);
        $awayBench = $allLineups->where('team_id', $match->away_team_id)->where('role', 'substitute')->sortBy(fn($item) => $item->player->jersey_number);

        $currentLineups = $allLineups->keyBy('player_id');
        $currentHomeLineup = $currentLineups->where('team_id', $match->home_team_id);
        $currentAwayLineup = $currentLineups->where('team_id', $match->away_team_id);
        
        $homePlayersData = $allHomePlayers->mapWithKeys(function ($p) use ($match) {
            return [
                $p->id => [ 
                    'id' => $p->id,
                    'name' => $p->full_name ?? ($p->first_name . ' ' . $p->last_name), 
                    'jersey' => $p->jersey_number, 
                    'team_id' => $match->home_team_id,
                    'position' => $p->position,
                ]
            ];
        })->all();

        $awayPlayersData = $allAwayPlayers->mapWithKeys(function ($p) use ($match) {
            return [
                $p->id => [ 
                    'id' => $p->id,
                    'name' => $p->full_name ?? ($p->first_name . ' ' . $p->last_name),
                    'jersey' => $p->jersey_number,
                    'team_id' => $match->away_team_id,
                    'position' => $p->position,
                ]
            ];
        })->all();

        $events = $match->matchEvents()
                     ->with(['player', 'assistPlayer', 'outPlayer', 'team.university'])
                     ->orderBy('minute', 'desc')
                     ->get();
                     
        $liveStartTime = $match->status === 'live' && $match->start_time 
                             ? $match->start_time->timestamp * 1000 
                             : null;

        return view('admin.live.show', [ 
            'match' => $match,
            'allHomePlayers' => $allHomePlayers, 
            'allAwayPlayers' => $allAwayPlayers, 
            'currentHomeLineup' => $currentHomeLineup, 
            'currentAwayLineup' => $currentAwayLineup, 
            'homePlayersData' => $homePlayersData, // Données pour JS
            'awayPlayersData' => $awayPlayersData, // Données pour JS
            'events' => $events,
            'homeStarters' => $homeStarters,
            'homeBench' => $homeBench,
            'awayStarters' => $awayStarters,
            'awayBench' => $awayBench,
            'liveStartTime' => $liveStartTime, 
        ]);
    }

    //----------------------------------------------------------------------
    // --- GESTION DES STATUTS ET COMPOSITIONS ---
    //----------------------------------------------------------------------

    /**
     * Met à jour le statut du match.
     */
    public function updateStatus(Request $request, MatchModel $match)
    {
        $validated = $request->validate([
            'status' => 'required|in:scheduled,live,halftime,finished',
        ]);
        
        $newStatus = $validated['status'];
        $previousStatus = $match->status;

        if ($newStatus === 'live' && $match->status !== 'live') {
            $allLineups = MatchLineup::where('match_id', $match->id)->get(); 

            $homeStartersCount = $allLineups->where('team_id', $match->home_team_id)->where('role', 'starter')->count();
            $awayStartersCount = $allLineups->where('team_id', $match->away_team_id)->where('role', 'starter')->count();

            if ($homeStartersCount < 11 || $awayStartersCount < 11) {
                return redirect()->back()->with('error', 'Impossible de passer en LIVE : les compositions de départ (11 titulaires) n\'ont pas été confirmées pour les deux équipes (Minimum 11 requis).');
            }
            
            if (empty($match->home_formation) || empty($match->away_formation)) {
                return redirect()->back()->with('error', 'Impossible de passer en LIVE : la formation tactique doit être enregistrée pour les deux équipes.');
            }
        }
        
        $now = now();
        if ($newStatus === 'live') {
            \Log::debug("=== updateStatus: Passage en LIVE ===");
            if (in_array($previousStatus, ['scheduled', 'finished'], true)) {
                $match->elapsed_time = 0;
            }
            if (!$match->start_time || $match->start_time->gt($now->copy()->addSeconds(5))) {
                $match->start_time = $now;
            }
            $match->timer_paused_at = null;
        } elseif ($newStatus === 'halftime') {
            \Log::debug("=== updateStatus: Passage en HALFTIME ===");
            \Log::debug("Avant pauseMatchTimer: elapsed_time = " . $match->elapsed_time . ", start_time = " . ($match->start_time ? $match->start_time->toIso8601String() : 'null'));
            $match->pauseMatchTimer();
            \Log::debug("Après pauseMatchTimer: elapsed_time = " . $match->elapsed_time);
        } elseif ($newStatus === 'finished') {
            $match->stopMatchTimer();
        } elseif ($newStatus === 'scheduled') {
            $match->elapsed_time = 0;
            $match->start_time = null;
            $match->timer_paused_at = null;
        }
        
        $match->status = $newStatus;
        
        if ($match->status === 'finished') {
            // (TODO: Recalculer le classement ici)
        }
        
        $match->save();

        // ✨ DIFFUSION EN TEMPS RÉEL (STATUT)
        event(new MatchStatusOrStatsUpdated($match, [
            'status' => $match->status,
            'start_time' => $match->start_time ? $match->start_time->timestamp * 1000 : null,
            'elapsed_time' => $match->getElapsedTime(),
        ]));

        return redirect()->back()->with('success', 'Statut du match mis à jour à : ' . ucfirst($match->status) . '.');
    }
    
    /**
     * Met à jour le score ou une statistique agrégée (fouls, corners, offsides) via AJAX.
     */
    public function updateStats(Request $request, MatchModel $match)
    {
        // 1. Validation de la requête
        $request->validate([
            // CORRECTION: AJOUT DE 'score' DANS LA RÈGLE DE VALIDATION pour les boutons +/- du score
            'stat_name' => 'required|in:score,fouls,corners,offsides,shots,shots_on_target,saves,free_kicks,throw_ins', 
            'team_side' => 'required|in:home,away',
            'action'    => 'required|in:increment,decrement',
        ]);

        $statName = $request->input('stat_name'); // 'score', 'fouls', 'corners', 'offsides', 'shots', 'shots_on_target', 'saves', 'free_kicks', 'throw_ins'
        $teamSide = $request->input('team_side'); // 'home', 'away'
        $action   = $request->input('action');    // 'increment', 'decrement'

        // 2. Définir le nom de la colonne (Ex: 'home_score' ou 'away_fouls')
        $columnName = "{$teamSide}_{$statName}"; 

        // 3. Vérifier la stat existante
        $currentValue = $match->{$columnName};

        if ($action === 'decrement' && $currentValue <= 0) {
            return response()->json([
                'success' => false,
                'message' => "Impossible de décrémenter une statistique déjà à zéro.",
            ], 400); // Bad Request
        }

        // 4. Mettre à jour la valeur dans la base de données
        $newValue = ($action === 'increment') ? $currentValue + 1 : $currentValue - 1;
        
        $match->{$columnName} = $newValue;
        $match->save();

        // ✨ DIFFUSION EN TEMPS RÉEL
        event(new MatchStatusOrStatsUpdated($match, [
            'stat_name' => $statName,
            'team_side' => $teamSide,
            "{$teamSide}_{$statName}" => $newValue, // Envoi de la stat mise à jour
        ]));

        // 5. Retourner une réponse JSON pour la mise à jour du frontend
        return response()->json([
            'success' => true,
            'stat' => $statName,
            'team' => $teamSide,
            'new_value' => $newValue,
            'message' => "Statistique {$columnName} mise à jour avec succès.",
        ]);
    }
    

    //----------------------------------------------------------------------
    // --- GESTION DES ÉVÉNEMENTS ---
    //----------------------------------------------------------------------

    /**
     * Ajoute un événement au match et met à jour le score si nécessaire.
     */
    public function addEvent(Request $request, MatchModel $match)
    {
        // NETTOYAGE DES INPUTS : Assurez-vous que les champs optionnels vides sont NULL.
        $request->merge([
            'assist_player_id' => $request->assist_player_id ?: null,
            'out_player_id' => $request->out_player_id ?: null,
        ]);
        
        $validated = $request->validate([
            'event_type' => 'required|string|in:goal,penalty_goal,own_goal,yellow_card,red_card,substitution_in',
            'team_id' => 'required|exists:teams,id',
            'player_id' => 'required|exists:players,id',
            'assist_player_id' => 'nullable|exists:players,id',
            'out_player_id' => 'nullable|exists:players,id', 
            'minute' => 'required|integer|min:1|max:120', 
        ]);
        
        if ($validated['event_type'] === 'substitution_in' && is_null($validated['out_player_id'])) {
            return redirect()->back()->withErrors(['out_player_id' => 'Le joueur sortant est obligatoire pour un remplacement.'])->withInput();
        }
        
        try {
            DB::transaction(function () use ($match, $validated) {
                $eventType = $validated['event_type'];
                $isGoal = in_array($eventType, ['goal', 'penalty_goal', 'own_goal']);
                $isSubstitution = $eventType === 'substitution_in';

                // 1. Création de l'événement principal
                $eventData = [
                    'match_id' => $match->id,
                    'team_id' => $validated['team_id'],
                    'player_id' => $validated['player_id'],
                    'event_type' => $eventType,
                    'minute' => $validated['minute'],
                    'assist_player_id' => $validated['assist_player_id'],
                    'out_player_id' => $validated['out_player_id'],
                ];

                $newEvent = MatchEvent::create($eventData);

                // 2. Gérer la substitution out (événement miroir)
                if ($isSubstitution) {
                    MatchEvent::create([
                        'match_id' => $match->id,
                        'team_id' => $validated['team_id'], 
                        'player_id' => $validated['out_player_id'], 
                        'event_type' => 'substitution_out',
                        'minute' => $validated['minute'],
                    ]);
                }

                // 3. Mettre à jour le score
                if ($isGoal) {
                    $teamId = $validated['team_id'];
                    $isHomeTeam = ($teamId == $match->home_team_id);
                    $isOwnGoal = $eventType === 'own_goal';

                    if ($isOwnGoal) {
                        if ($isHomeTeam) {
                            $match->away_score += 1; 
                        } else {
                            $match->home_score += 1; 
                        }
                    } else {
                        if ($isHomeTeam) {
                            $match->home_score += 1;
                        } else {
                            $match->away_score += 1;
                        }
                    }
                    $match->save();
                }

                // 4. Charger les relations avant la diffusion
                $newEvent->load(['player', 'assistPlayer', 'outPlayer', 'team.university']);
                
                $broadcastData = [
                    'match_id' => $match->id,
                    'event_id' => $newEvent->id,
                    'team_id' => $newEvent->team_id,
                    'event_type' => $newEvent->event_type,
                    'minute' => $newEvent->minute,
                    'home_score' => $match->home_score, 
                    'away_score' => $match->away_score, 
                    'is_home_team' => $newEvent->team_id === $match->home_team_id,
                    'team_name' => $newEvent->team->university->name ?? $newEvent->team->name,
                    'player_name' => $newEvent->player->full_name ?? ($newEvent->player->first_name . ' ' . $newEvent->player->last_name),
                    'assist_name' => $newEvent->assistPlayer->full_name ?? null,
                    'out_player_name' => $newEvent->outPlayer->full_name ?? null,
                    // Inclure les relations pour l'affichage public
                    'new_event_data' => $newEvent->toArray(), 
                ];
                
                // 5. Diffusion de l'événement (Score et nouvel événement de la timeline)
                event(new MatchEventOccurred($match->id, $broadcastData)); 
            });

            return redirect()->back()->with('success', 'Événement enregistré et score mis à jour. Diffusé en direct.'); 

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Erreur lors de l'enregistrement de l'événement: " . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de l\'enregistrement de l\'événement. Vérifiez les logs.');
        }
    }

    /**
     * Supprime un événement et ajuste le score si nécessaire.
     */
    public function deleteEvent(MatchEvent $matchEvent)
    {
        $match = $matchEvent->match; 

        if ($matchEvent->event_type === 'substitution_out') {
             return redirect()->back()->with('error', 'Seul l\'événement de Remplacement (Entrée) peut être annulé.');
        }

        try {
            DB::transaction(function () use ($matchEvent, $match) {
                $isGoal = in_array($matchEvent->event_type, ['goal', 'penalty_goal', 'own_goal']);
                $isSubstitutionIn = $matchEvent->event_type === 'substitution_in';

                if ($isGoal) {
                    $teamId = $matchEvent->team_id;
                    $isHomeTeam = ($teamId == $match->home_team_id);
                    $isOwnGoal = $matchEvent->event_type === 'own_goal';

                    if ($isOwnGoal) {
                        if ($isHomeTeam) {
                            $match->away_score = max(0, $match->away_score - 1);
                        } else {
                            $match->home_score = max(0, $match->home_score - 1);
                        }
                    } else {
                        if ($isHomeTeam) {
                            $match->home_score = max(0, $match->home_score - 1);
                        } else {
                            $match->away_score = max(0, $match->away_score - 1);
                        }
                    }
                    $match->save();
                }

                if ($isSubstitutionIn) {
                    // Trouver et supprimer l'événement miroir 'substitution_out'
                    $outEvent = MatchEvent::where('match_id', $matchEvent->match_id)
                        ->where('event_type', 'substitution_out')
                        ->where('player_id', $matchEvent->out_player_id) 
                        ->where('minute', $matchEvent->minute)
                        ->first();
                    
                    if ($outEvent) {
                        $outEvent->delete();
                    }
                }

                $matchEvent->delete();
                
                // ✨ DIFFUSION EN TEMPS RÉEL (ANNULATION ÉVÉNEMENT/SCORE)
                // On diffuse l'ID de l'événement supprimé et le nouveau score
                event(new MatchEventOccurred($match->id, [
                    'action' => 'deleted',
                    'event_id' => $matchEvent->id,
                    'home_score' => $match->home_score, 
                    'away_score' => $match->away_score, 
                ]));
            });

            return redirect()->back()->with('success', 'Événement et ajustements de score annulés avec succès.');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Erreur lors de l'annulation de l'événement: " . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de l\'annulation de l\'événement. Vérifiez les logs.');
        }
    }
}
