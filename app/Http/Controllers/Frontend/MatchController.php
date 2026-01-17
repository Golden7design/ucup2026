<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\MatchModel;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MatchController extends Controller
{
    public function index(Request $request)
    {
        $query = MatchModel::query()->with(['homeTeam.university', 'awayTeam.university']);

        $statusFilter = $request->input('status', 'Tous');
        $phaseFilter = $request->input('phase', 'Toutes');

        if ($statusFilter !== 'Tous' && $statusFilter !== '') {
            $query->where('status', strtolower($statusFilter));
        }

        if ($phaseFilter !== 'Toutes' && $phaseFilter !== '') {
            $query->where('group', $phaseFilter);
        }

        $matches = $query->orderBy('match_date', 'asc')->paginate(15);

        $statusOrder = [
            'live' => 1,
            'halftime' => 1,
            'scheduled' => 2,
            'upcoming' => 2,
            'finished' => 3,
            'postponed' => 4,
            'cancelled' => 5,
        ];

        $sortedMatches = $matches->getCollection()->sortBy(function ($match) use ($statusOrder) {
            return $statusOrder[$match->status] ?? 99;
        })->values();

        $matches->setCollection($sortedMatches);

        return view('frontend.matches.index', [
            'matches' => $matches,
            'statusFilter' => $statusFilter,
            'phaseFilter' => $phaseFilter,
        ]);
    }

    public function show(MatchModel $match)
    {
        // Charger toutes les relations nécessaires pour l'affichage public
        $match->load([
            'homeTeam.university',
            'awayTeam.university',
            'matchEvents.player',
            'matchEvents.assistPlayer',
            'matchEvents.outPlayer',
            'matchEvents.team.university',
            'lineups.player',
        ]);

        // Initialiser les collections si elles n'existent pas
        $match->matchEvents = $match->matchEvents ?? collect();
        $match->lineups = $match->lineups ?? collect();

        // Récupérer la collection complète des compositions par équipe
        $homeLineups = $match->lineups->where('team_id', $match->home_team_id)->values();
        $awayLineups = $match->lineups->where('team_id', $match->away_team_id)->values();

        // Titulaires Domicile (is_starter = true)
        $homeStarters = $homeLineups->where('is_starter', true)
            ->sortBy(function ($lineup) {
                return $lineup->order_key ?? ($lineup->player->jersey_number ?? 999);
            })->values();

        // Remplaçants Domicile (is_starter = false)
        $homeSubstitutes = $homeLineups->where('is_starter', false)
            ->sortBy(function ($lineup) {
                return $lineup->player->jersey_number ?? 999;
            })->values();

        // Titulaires Extérieur (is_starter = true)
        $awayStarters = $awayLineups->where('is_starter', true)
            ->sortBy(function ($lineup) {
                return $lineup->order_key ?? ($lineup->player->jersey_number ?? 999);
            })->values();

        // Remplaçants Extérieur (is_starter = false)
        $awaySubstitutes = $awayLineups->where('is_starter', false)
            ->sortBy(function ($lineup) {
                return $lineup->player->jersey_number ?? 999;
            })->values();

        $timeDisplay = $this->getStaticTimeDisplay($match->status);

        // Obtenir les statistiques et événements
        $statistics = $match->getMatchStatistics();
        $eventsByType = $match->getEventsByType();
        $goals = $match->getGoals();
        $cards = $match->getCards();
        $substitutions = $match->getSubstitutions();
        $matchSummary = $match->getMatchSummary();

        return view('frontend.matches.show', [
            'match' => $match,
            'homeStarters' => $homeStarters,
            'homeSubstitutes' => $homeSubstitutes,
            'awayStarters' => $awayStarters,
            'awaySubstitutes' => $awaySubstitutes,
            'homeCoach' => $match->home_coach,
            'awayCoach' => $match->away_coach,
            'homeFormation' => $match->home_formation,
            'awayFormation' => $match->away_formation,
            'timeDisplay' => $timeDisplay,
            'startTimeIso' => $match->start_time ? $match->start_time->toIso8601String() : null,
            'statistics' => $statistics,
            'eventsByType' => $eventsByType,
            'goals' => $goals,
            'cards' => $cards,
            'substitutions' => $substitutions,
            'matchSummary' => $matchSummary,
            'elapsedTime' => $match->getFormattedTime(),
        ]);
    }

    /**
     * Affiche la vue Sofascore-like d'un match
     */
    public function showSofascore(MatchModel $match)
    {
        // Charger toutes les relations nécessaires pour l'affichage public
        $match->load([
            'homeTeam.university',
            'awayTeam.university',
            'matchEvents.player',
            'matchEvents.assistPlayer',
            'matchEvents.outPlayer',
            'matchEvents.team.university',
            'lineups.player',
        ]);

        // Initialiser les collections si elles n'existent pas
        $match->matchEvents = $match->matchEvents ?? collect();
        $match->lineups = $match->lineups ?? collect();

        // Récupérer la collection complète des compositions par équipe
        $homeLineups = $match->lineups->where('team_id', $match->home_team_id)->values();
        $awayLineups = $match->lineups->where('team_id', $match->away_team_id)->values();

        // Titulaires Domicile (is_starter = true)
        $homeStarters = $homeLineups->where('is_starter', true)
            ->sortBy(function ($lineup) {
                return $lineup->order_key ?? ($lineup->player->jersey_number ?? 999);
            })->values();

        // Remplaçants Domicile (is_starter = false)
        $homeSubstitutes = $homeLineups->where('is_starter', false)
            ->sortBy(function ($lineup) {
                return $lineup->player->jersey_number ?? 999;
            })->values();

        // Titulaires Extérieur (is_starter = true)
        $awayStarters = $awayLineups->where('is_starter', true)
            ->sortBy(function ($lineup) {
                return $lineup->order_key ?? ($lineup->player->jersey_number ?? 999);
            })->values();

        // Remplaçants Extérieur (is_starter = false)
        $awaySubstitutes = $awayLineups->where('is_starter', false)
            ->sortBy(function ($lineup) {
                return $lineup->player->jersey_number ?? 999;
            })->values();

        // Obtenir les statistiques et événements
        $statistics = $match->getMatchStatistics();
        $eventsByType = $match->getEventsByType();
        $goals = $match->getGoals();
        $cards = $match->getCards();
        $substitutions = $match->getSubstitutions();
        $matchSummary = $match->getMatchSummary();

        return view('frontend.matches.show_sofascore', [
            'match' => $match,
            'homeStarters' => $homeStarters,
            'homeSubstitutes' => $homeSubstitutes,
            'awayStarters' => $awayStarters,
            'awaySubstitutes' => $awaySubstitutes,
            'homeCoach' => $match->home_coach,
            'awayCoach' => $match->away_coach,
            'homeFormation' => $match->home_formation,
            'awayFormation' => $match->away_formation,
            'statistics' => $statistics,
            'eventsByType' => $eventsByType,
            'goals' => $goals,
            'cards' => $cards,
            'substitutions' => $substitutions,
            'matchSummary' => $matchSummary,
        ]);
    }

    public function live()
    {
        $liveMatches = MatchModel::whereIn('status', ['live', 'halftime'])
            ->with([
                'homeTeam.university',
                'awayTeam.university',
                'matchEvents.player',
                'matchEvents.outPlayer',
                'matchEvents.team.university',
                'lineups.player',
            ])
            ->orderBy('match_date', 'asc')
            ->get();

        $liveMatches->each(function ($match) {
            $match->homeLineups = $match->lineups->where('team_id', $match->home_team_id)->values();
            $match->awayLineups = $match->lineups->where('team_id', $match->away_team_id)->values();
            $match->matchEvents = $match->matchEvents ?? collect();
        });

        return view('frontend.matches.live', compact('liveMatches'));
    }

    protected function getStaticTimeDisplay(string $status): string
    {
        return match ($status) {
            'halftime' => 'MI-TEMPS',
            'finished' => 'FIN',
            'scheduled' => 'À VENIR',
            default => '--:--',
        };
    }
}
