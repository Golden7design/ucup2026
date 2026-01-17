<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MatchModel;
use App\Models\Team;
use Illuminate\Http\Request;
use App\Services\StandingService;
use App\Models\Player;
use App\Models\MatchLineup;
use Illuminate\Support\Facades\DB;
use App\Events\MatchUpdated;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class MatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(\Illuminate\Http\Request $request)
    {
        $statusFilter = $request->get('status');
        $query = MatchModel::with(['homeTeam.university', 'awayTeam.university'])
            ->orderBy('match_date', 'desc');

        if ($statusFilter && $statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        $matches = $query->paginate(15)->withQueryString();
        return view('admin.matches.index', compact('matches', 'statusFilter'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teams = Team::with('university')->orderBy('name')->get();
        $groups = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
        return view('admin.matches.create', compact('teams', 'groups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'home_team_id' => 'required|exists:teams,id|different:away_team_id',
            'away_team_id' => 'required|exists:teams,id',
            'match_date' => 'required|date',
            'location' => 'nullable|string|max:255',
            'group' => 'nullable|string|max:1',
            'match_type' => 'required|in:tournament,friendly',
        ], [
            'home_team_id.different' => 'L\'équipe à domicile et l\'équipe à l\'extérieur doivent être différentes.',
        ]);

        MatchModel::create($validated);
        return redirect()->route('admin.matches.index')->with('success', 'Match créé avec succès.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MatchModel $match)
    {
        $teams = Team::with('university')->orderBy('name')->get();
        $groups = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
        return view('admin.matches.edit', compact('match', 'teams', 'groups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MatchModel $match, StandingService $standingService)
    {
        
        
        // Sauvegarder les anciens scores et statut
        $oldHomeScore = $match->home_score;
        $oldAwayScore = $match->away_score;
        $oldStatus = $match->status;
        $oldUpdatedAt = $match->updated_at;

        // Validation des données
        $validated = $request->validate([
            'home_team_id' => 'required|exists:teams,id|different:away_team_id',
            'away_team_id' => 'required|exists:teams,id',
            'match_date' => 'required|date',
            'venue' => 'nullable|string|max:255',
            'group' => 'nullable|string|max:1',
            'status' => 'required|string|in:scheduled,live,halftime,finished,postponed,cancelled',
            'home_score' => 'nullable|integer|min:0',
            'away_score' => 'nullable|integer|min:0',
            'match_type' => 'required|in:tournament,friendly',
            'home_coach' => 'nullable|string|max:255',
            'away_coach' => 'nullable|string|max:255',
            'home_formation' => 'nullable|string|max:10',
            'away_formation' => 'nullable|string|max:10',
        ], [
            'home_team_id.different' => 'L\'équipe à domicile et l\'équipe à l\'extérieur doivent être différentes.',
        ]);

        // Mettre à jour le match
        $match->update($validated);

        // Gérer le temps de début si le match commence
        $newStatusIsPlaying = in_array($validated['status'], ['live', 'halftime']);
        $isStartTimeMissing = is_null($match->start_time);

        if ($newStatusIsPlaying && $isStartTimeMissing) {
            $match->start_time = now();
            $match->save();
        }

        // Vérifier si le score ou le statut a changé
        $scoreChanged = ($oldHomeScore != ($validated['home_score'] ?? $oldHomeScore) ||
                        $oldAwayScore != ($validated['away_score'] ?? $oldAwayScore));
        $statusChanged = ($oldStatus != $validated['status']);

        // Déclencher un événement de mise à jour si nécessaire
        if ($scoreChanged || $statusChanged || ($newStatusIsPlaying && $isStartTimeMissing)) {
            $match->refresh();

            // Mettre à jour le cache pour forcer le rafraîchissement
            Cache::forget("match_{$match->id}_last_update");

            // Créer un événement personnalisé avec les données pertinentes
            event(new MatchUpdated(
                $match->id,
                $match->home_score,
                $match->away_score,
                $match->status,
                $match->updated_at,
                $match->start_time
            ));
        }

        // Mettre à jour les classements si le match est terminé
        if (isset($validated['status']) && $validated['status'] === 'finished') {
            $standingService->updateStandingsForMatch($match);
        }

        Cache::forget("match_{$match->id}_realtime_data");

        

        return redirect()->route('admin.matches.index')->with('success', 'Match mis à jour!');
    }

    /**
     * Méthode pour mettre à jour les statistiques en direct
     */
    public function updateLiveStats(Request $request, MatchModel $match)
    {
        $validated = $request->validate([
            'home_score' => 'sometimes|integer|min:0',
            'away_score' => 'sometimes|integer|min:0',
            'home_fouls' => 'sometimes|integer|min:0',
            'away_fouls' => 'sometimes|integer|min:0',
            'home_corners' => 'sometimes|integer|min:0',
            'away_corners' => 'sometimes|integer|min:0',
            'home_yellow_cards' => 'sometimes|integer|min:0',
            'home_red_cards' => 'sometimes|integer|min:0',
            'away_yellow_cards' => 'sometimes|integer|min:0',
            'away_red_cards' => 'sometimes|integer|min:0',
            'event_type' => 'sometimes|string|in:goal,yellow_card,red_card,substitution,foul,corner',
            'player_id' => 'sometimes|exists:players,id',
            'minute' => 'sometimes|integer|min:1',
            'team_id' => 'sometimes|in:'.$match->home_team_id.','.$match->away_team_id,
        ]);

        // Mettre à jour les statistiques du match
        if (isset($validated['home_score'])) {
            $match->home_score = $validated['home_score'];
        }
        if (isset($validated['away_score'])) {
            $match->away_score = $validated['away_score'];
        }
        if (isset($validated['home_fouls'])) {
            $match->home_fouls = $validated['home_fouls'];
        }
        if (isset($validated['away_fouls'])) {
            $match->away_fouls = $validated['away_fouls'];
        }
        if (isset($validated['home_corners'])) {
            $match->home_corners = $validated['home_corners'];
        }
        if (isset($validated['away_corners'])) {
            $match->away_corners = $validated['away_corners'];
        }
        if (isset($validated['home_yellow_cards'])) {
            $match->home_yellow_cards = $validated['home_yellow_cards'];
        }
        if (isset($validated['home_red_cards'])) {
            $match->home_red_cards = $validated['home_red_cards'];
        }
        if (isset($validated['away_yellow_cards'])) {
            $match->away_yellow_cards = $validated['away_yellow_cards'];
        }
        if (isset($validated['away_red_cards'])) {
            $match->away_red_cards = $validated['away_red_cards'];
        }

        $match->save();

        // Créer un événement si c'est un but ou un carton
        if (isset($validated['event_type'])) {
            $match->matchEvents()->create([
                'event_type' => $validated['event_type'],
                'player_id' => $validated['player_id'] ?? null,
                'team_id' => $validated['team_id'] ?? null,
                'minute' => $validated['minute'] ?? now()->diffInMinutes($match->start_time),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Mettre à jour le cache
        Cache::forget("match_{$match->id}_last_update");

        // Déclencher un événement de mise à jour
        event(new MatchUpdated(
            $match->id,
            $match->home_score,
            $match->away_score,
            $match->status,
            $match->updated_at,
            $match->start_time
        ));

        return response()->json([
            'success' => true,
            'message' => 'Statistiques mises à jour avec succès',
            'match' => $match->fresh()
        ]);
    }

    // Méthodes pour la gestion des compositions (inchangées)
    public function lineup(MatchModel $match)
    {
        $match->load('homeTeam.university', 'awayTeam.university');

        $homePlayers = Player::where('team_id', $match->home_team_id)
            ->orderBy('jersey_number', 'asc')
            ->get();

        $awayPlayers = Player::where('team_id', $match->away_team_id)
            ->orderBy('jersey_number', 'asc')
            ->get();

        $currentLineup = MatchLineup::where('match_id', $match->id)->with('player')->get();

        $homeStarters = $currentLineup->where('team_id', $match->home_team_id)
                                     ->where('is_starter', true)
                                     ->sortBy('order_key');

        $homeStartersIds = $homeStarters->pluck('player_id')->toArray();
        $homeStarterPositions = $homeStarters->pluck('position')->toArray();

        $awayStarters = $currentLineup->where('team_id', $match->away_team_id)
                                     ->where('is_starter', true)
                                     ->sortBy('order_key');

        $awayStartersIds = $awayStarters->pluck('player_id')->toArray();
        $awayStarterPositions = $awayStarters->pluck('position')->toArray();

        $homeSubs = $currentLineup->where('team_id', $match->home_team_id)
                                 ->where('is_starter', false);
        $homeSubsIds = $homeSubs->pluck('player_id')->toArray();

        $awaySubs = $currentLineup->where('team_id', $match->away_team_id)
                                 ->where('is_starter', false);
        $awaySubsIds = $awaySubs->pluck('player_id')->toArray();

        return view('admin.matches.lineup', [
            'match' => $match,
            'homePlayers' => $homePlayers,
            'awayPlayers' => $awayPlayers,
            'homeStartersIds' => $homeStartersIds,
            'awayStartersIds' => $awayStartersIds,
            'homeSubsIds' => $homeSubsIds,
            'awaySubsIds' => $awaySubsIds,
            'homeStarterPositions' => $homeStarterPositions,
            'awayStarterPositions' => $awayStarterPositions,
        ]);
    }

    public function storeLineup(Request $request, MatchModel $match)
    {
        $request->validate([
            'home_coach' => 'nullable|string|max:255',
            'away_coach' => 'nullable|string|max:255',
            'home_formation' => 'nullable|string|max:10',
            'away_formation' => 'nullable|string|max:10',
            'home_starters' => 'required|array|size:11',
            'home_starters.*' => 'required|distinct|exists:players,id',
            'home_starter_positions' => 'required|array|size:11',
            'home_starter_positions.*' => 'required|string|max:5',
            'away_starters' => 'required|array|size:11',
            'away_starters.*' => 'required|distinct|exists:players,id',
            'away_starter_positions' => 'required|array|size:11',
            'away_starter_positions.*' => 'required|string|max:5',
            'home_substitutes' => 'nullable|array',
            'away_substitutes' => 'nullable|array',
            'home_substitutes.*' => 'nullable|exists:players,id',
            'away_substitutes.*' => 'nullable|exists:players,id',
        ], [
             'home_starters.size' => 'L\'équipe domicile doit avoir exactement 11 titulaires.',
             'away_starters.size' => 'L\'équipe extérieure doit avoir exactement 11 titulaires.',
             'home_starter_positions.size' => 'L\'équipe domicile doit avoir 11 postes spécifiques.',
             'away_starter_positions.size' => 'L\'équipe extérieure doit avoir 11 postes spécifiques.',
             'home_starters.*.distinct' => 'Un joueur ne peut pas être sélectionné deux fois dans les titulaires domicile.',
             'away_starters.*.distinct' => 'Un joueur ne peut pas être sélectionné deux fois dans les titulaires extérieur.',
        ]);

        $homeStarters = collect($request->input('home_starters', []));
        $homeSubs = collect($request->input('home_substitutes', []))->filter();
        $awayStarters = collect($request->input('away_starters', []));
        $awaySubs = collect($request->input('away_substitutes', []))->filter();

        $homeStarterPositions = $request->input('home_starter_positions', []);
        $awayStarterPositions = $request->input('away_starter_positions', []);

        if ($homeStarters->intersect($homeSubs)->isNotEmpty()) {
             return back()->withErrors(['home_lineup_error' => 'Un joueur domicile ne peut être titulaire et remplaçant en même temps.'])->withInput();
        }
        if ($awayStarters->intersect($awaySubs)->isNotEmpty()) {
             return back()->withErrors(['away_lineup_error' => 'Un joueur extérieur ne peut être titulaire et remplaçant en même temps.'])->withInput();
        }

        DB::transaction(function () use ($request, $match, $homeStarters, $homeSubs, $awayStarters, $awaySubs, $homeStarterPositions, $awayStarterPositions) {
            $match->home_coach = $request->input('home_coach');
            $match->away_coach = $request->input('away_coach');
            $match->home_formation = $request->input('home_formation');
            $match->away_formation = $request->input('away_formation');
            $match->save();

            MatchLineup::where('match_id', $match->id)->delete();

            $lineups = [];

            $lineups = array_merge($lineups, $this->buildLineupArray(
                $match,
                $match->home_team_id,
                $homeStarters->toArray(),
                $homeSubs->toArray(),
                $homeStarterPositions
            ));

            $lineups = array_merge($lineups, $this->buildLineupArray(
                $match,
                $match->away_team_id,
                $awayStarters->toArray(),
                $awaySubs->toArray(),
                $awayStarterPositions
            ));

            if (!empty($lineups)) {
                MatchLineup::insert($lineups);
            }
        });

        return redirect()->route('admin.matches.lineup', $match)->with('success', 'Composition d\'équipe enregistrée avec succès.');
    }

    protected function buildLineupArray(MatchModel $match, $teamId, array $starters, array $substitutes, array $starterPositions = []): array
    {
        $lineups = [];
        $order = 1;

        foreach ($starters as $index => $playerId) {
            $lineups[] = [
                'match_id' => $match->id,
                'team_id' => $teamId,
                'player_id' => $playerId,
                'is_starter' => (int)true,
                'position' => $starterPositions[$index] ?? 'N/A',
                'role' => 'starter',
                'order_key' => $order++,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        foreach ($substitutes as $playerId) {
            $lineups[] = [
                'match_id' => $match->id,
                'team_id' => $teamId,
                'player_id' => $playerId,
                'is_starter' => (int)false,
                'position' => 'SUB',
                'role' => 'substitute',
                'order_key' => $order++,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        return $lineups;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MatchModel $match)
    {
        $match->delete();
        return redirect()->route('admin.matches.index')->with('success', 'Match supprimé avec succès.');
    }
}
