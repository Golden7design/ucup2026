<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

// Imports nécessaires pour les relations
use App\Models\MatchLineup;
use App\Models\MatchEvent;
use App\Models\Team;

class MatchModel extends Model
{
    use HasFactory;

    protected $table = 'matches';

    protected $fillable = [
        'home_team_id', 'away_team_id', 'match_date', 'venue', 'match_type',
        'status', 'start_time', 'timer_paused_at', 'home_score', 'away_score',
        'home_coach', 'away_coach', 'home_formation', 'away_formation', 'round',
        'group', 'attendance', 'home_fouls', 'home_corners', 'home_offsides',
        'away_fouls', 'away_corners', 'away_offsides',
        'home_yellow_cards', 'home_red_cards', 'away_yellow_cards', 'away_red_cards',
        'home_possession', 'away_possession', 'home_shots', 'away_shots',
        'home_shots_on_target', 'away_shots_on_target', 'home_saves', 'away_saves',
        'home_free_kicks', 'away_free_kicks', 'home_throw_ins', 'away_throw_ins',
        'home_goalkicks', 'away_goalkicks', 'home_penalties', 'away_penalties',
        'referee', 'weather', 'temperature', 'humidity',
        'elapsed_time', 'additional_time_first_half', 'additional_time_second_half',
        'is_extra_time', 'is_penalty_shootout',
    ];

    protected $casts = [
        'match_date' => 'datetime',
        'start_time' => 'datetime',
        'timer_paused_at' => 'datetime',
        'match_type' => 'string',
    ];

    /**
     * Relation vers les compositions d'équipe (MatchLineups).
     */
    public function lineups()
    {
        return $this->hasMany(MatchLineup::class, 'match_id');
    }

    /**
     * Relation vers les événements du match (buts, cartons, etc.)
     */
    public function matchEvents()
    {
        return $this->hasMany(MatchEvent::class, 'match_id')->orderBy('minute', 'asc');
    }

    /**
     * Relation vers l'équipe à domicile.
     */
    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    /**
     * Relation vers l'équipe à l'extérieur.
     */
    public function awayTeam()
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    /**
     * Vérifie si le match est en cours (live ou mi-temps).
     */
    public function isLive()
    {
        return $this->status === 'live';
    }

    /**
     * Vérifie si le match est en mi-temps.
     */
    public function isHalftime()
    {
        return $this->status === 'halftime';
    }

    /**
     * Vérifie si le match est terminé.
     */
    public function isFinished()
    {
        return $this->status === 'finished';
    }

    /**
     * Démarre le minuteur du match
     */
    public function startMatchTimer()
    {
        $now = Carbon::now();

        if ($this->elapsed_time === null) {
            $this->elapsed_time = 0;
        }

        if ($this->start_time) {
            $start = Carbon::parse($this->start_time);
            if ($start->gt($now->copy()->addSeconds(5))) {
                $this->start_time = $now;
            }
            $this->timer_paused_at = null;
            $this->save();
            return;
        }

        $this->timer_paused_at = null;
        $this->start_time = $now;
        $this->save();
    }

    /**
     * Met en pause le minuteur du match
     */
    public function pauseMatchTimer()
    {
        \Log::debug("=== pauseMatchTimer() appelé ===");
        \Log::debug("start_time: " . ($this->start_time ? $this->start_time->toIso8601String() : 'null'));
        \Log::debug("elapsed_time actuel: " . $this->elapsed_time);
        
        if (!$this->start_time) {
            \Log::warning("pauseMatchTimer: pas de start_time, return");
            return;
        }

        $now = Carbon::now();
        $base = (int) ($this->elapsed_time ?? 0);
        
        // Calcul du segment avec timezone UTC
        $startParsed = Carbon::parse($this->start_time)->timezone('UTC');
        $segment = $startParsed->diffInSeconds($now->timezone('UTC'), false);
        
        \Log::debug("now: " . $now->toIso8601String());
        \Log::debug("start parsed: " . $startParsed->toIso8601String());
        \Log::debug("segment calculé: " . $segment);
        
        $this->elapsed_time = (int) max(0, (int) $base + (int) $segment);
        $this->timer_paused_at = $now;
        $this->start_time = null;
        $this->save();
        
        \Log::debug("SAUVÉ: elapsed_time = " . $this->elapsed_time);
    }

    /**
     * Reprend le minuteur du match
     */
    public function resumeMatchTimer()
    {
        $now = Carbon::now();

        if ($this->elapsed_time === null) {
            $this->elapsed_time = 0;
        }

        if ($this->start_time) {
            $start = Carbon::parse($this->start_time);
            if ($start->gt($now->copy()->addSeconds(5))) {
                $this->start_time = $now;
            }
            $this->timer_paused_at = null;
            $this->save();
            return;
        }

        $this->timer_paused_at = null;
        $this->start_time = $now;
        $this->save();
    }

    /**
     * Obtient le temps écoulé en secondes
     */
    public function getElapsedTime()
    {
        $base = (int) ($this->elapsed_time ?? 0);

        // Si pas de start_time, ou timer en pause, ou match pas en direct
        if (!$this->start_time || $this->timer_paused_at || $this->status !== 'live') {
            return max(0, $base);
        }

        $now = Carbon::now();
        
        // Parser le start_time en UTC
        $start = Carbon::parse($this->start_time)->timezone('UTC');

        // Auto-correction si la date de début est dans le futur (décalage timezone/serveur)
        if ($start->gt($now->copy()->timezone('UTC')->addSeconds(5))) {
            // Start time est dans le futur - le réinitialiser à maintenant
            $this->start_time = $now;
            $this->timer_paused_at = null;
            $this->save();
            $start = $now->timezone('UTC');
            return 0;
        }

        // Calcul du temps écoulé: maintenant - start
        // $now->diffInSeconds($start) donne le temps de $start à $now
        $running = $start->diffInSeconds($now, false);
        
        return (int) max(0, (int) $base + (int) $running);
    }

    /**
     * Obtient le temps écoulé formaté (MM:SS)
     */
    public function getFormattedTime()
    {
        $elapsed = $this->getElapsedTime();
        $minutes = floor($elapsed / 60);
        $seconds = $elapsed % 60;

        // Assurer que les minutes et les secondes sont positives
        $minutes = max(0, $minutes);
        $seconds = max(0, $seconds);

        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    /**
     * Arrête complètement le minuteur
     */
    public function stopMatchTimer()
    {
        // Enregistrer le temps total joué avant d'arrêter
        if ($this->start_time) {
            $now = Carbon::now();
            $base = (int) ($this->elapsed_time ?? 0);
            $segment = $now->diffInSeconds(Carbon::parse($this->start_time), false);
            $this->elapsed_time = max(0, $base + $segment);
        } else {
            $this->elapsed_time = max(0, (int) ($this->elapsed_time ?? 0));
        }
        $this->start_time = null;
        $this->timer_paused_at = null;
        $this->save();
    }

    /**
     * Ajoute du temps additionnel à la première mi-temps
     */
    public function addAdditionalTimeFirstHalf($minutes)
    {
        $this->additional_time_first_half = $minutes;
        $this->save();
    }

    /**
     * Ajoute du temps additionnel à la deuxième mi-temps
     */
    public function addAdditionalTimeSecondHalf($minutes)
    {
        $this->additional_time_second_half = $minutes;
        $this->save();
    }

    /**
     * Active les prolongations
     */
    public function enableExtraTime()
    {
        $this->is_extra_time = true;
        $this->save();
    }

    /**
     * Désactive les prolongations
     */
    public function disableExtraTime()
    {
        $this->is_extra_time = false;
        $this->save();
    }

    /**
     * Active les tirs au but
     */
    public function enablePenaltyShootout()
    {
        $this->is_penalty_shootout = true;
        $this->save();
    }

    /**
     * Désactive les tirs au but
     */
    public function disablePenaltyShootout()
    {
        $this->is_penalty_shootout = false;
        $this->save();
    }

    /**
     * Vérifie si le match est en prolongations
     */
    public function isExtraTime()
    {
        return $this->is_extra_time;
    }

    /**
     * Vérifie si le match est en tirs au but
     */
    public function isPenaltyShootout()
    {
        return $this->is_penalty_shootout;
    }

    /**
     * Obtient le temps additionnel pour la première mi-temps
     */
    public function getAdditionalTimeFirstHalf()
    {
        return $this->additional_time_first_half ?? 0;
    }

    /**
     * Obtient le temps additionnel pour la deuxième mi-temps
     */
    public function getAdditionalTimeSecondHalf()
    {
        return $this->additional_time_second_half ?? 0;
    }

    /**
     * Vérifie si le minuteur est en cours
     */
    public function isTimerRunning()
    {
        return $this->start_time && !$this->timer_paused_at && $this->isLive();
    }

    /**
     * Obtient les statistiques détaillées du match
     */
    public function getMatchStatistics()
    {
        return [
            'fouls' => [
                'home' => $this->home_fouls ?? 0,
                'away' => $this->away_fouls ?? 0
            ],
            'corners' => [
                'home' => $this->home_corners ?? 0,
                'away' => $this->away_corners ?? 0
            ],
            'offsides' => [
                'home' => $this->home_offsides ?? 0,
                'away' => $this->away_offsides ?? 0
            ],
            'yellow_cards' => [
                'home' => $this->home_yellow_cards ?? 0,
                'away' => $this->away_yellow_cards ?? 0
            ],
            'red_cards' => [
                'home' => $this->home_red_cards ?? 0,
                'away' => $this->away_red_cards ?? 0
            ],
            'possession' => [
                'home' => $this->home_possession ?? 50,
                'away' => $this->away_possession ?? 50
            ],
            'shots' => [
                'home' => $this->home_shots ?? 0,
                'away' => $this->away_shots ?? 0
            ],
            'shots_on_target' => [
                'home' => $this->home_shots_on_target ?? 0,
                'away' => $this->away_shots_on_target ?? 0
            ],
            'saves' => [
                'home' => $this->home_saves ?? 0,
                'away' => $this->away_saves ?? 0
            ],
            'free_kicks' => [
                'home' => $this->home_free_kicks ?? 0,
                'away' => $this->away_free_kicks ?? 0
            ],
            'throw_ins' => [
                'home' => $this->home_throw_ins ?? 0,
                'away' => $this->away_throw_ins ?? 0
            ],
            'goalkicks' => [
                'home' => $this->home_goalkicks ?? 0,
                'away' => $this->away_goalkicks ?? 0
            ],
            'penalties' => [
                'home' => $this->home_penalties ?? 0,
                'away' => $this->away_penalties ?? 0
            ]
        ];
    }

    /**
     * Obtient les événements du match par type
     */
    public function getEventsByType()
    {
        $events = $this->matchEvents()->with(['player', 'assistPlayer', 'outPlayer', 'team'])->get();
        
        return $events->groupBy('event_type')->map(function ($group) {
            return $group->sortBy('minute');
        });
    }

    /**
     * Obtient les buts du match
     */
    public function getGoals()
    {
        return $this->matchEvents()->where('event_type', 'goal')
            ->with(['player', 'assistPlayer', 'team'])
            ->orderBy('minute', 'asc')
            ->get();
    }

    /**
     * Obtient les cartons du match
     */
    public function getCards()
    {
        return $this->matchEvents()->whereIn('event_type', ['yellow_card', 'red_card'])
            ->with(['player', 'team'])
            ->orderBy('minute', 'asc')
            ->get();
    }

    /**
     * Obtient les substitutions du match
     */
    public function getSubstitutions()
    {
        return $this->matchEvents()->where('event_type', 'substitution_in')
            ->with(['player', 'outPlayer', 'team'])
            ->orderBy('minute', 'asc')
            ->get();
    }

    /**
     * Obtient le résumé du match
     */
    public function getMatchSummary()
    {
        return [
            'home_team' => $this->homeTeam->name ?? 'Équipe à domicile',
            'away_team' => $this->awayTeam->name ?? 'Équipe à l\'extérieur',
            'score' => ($this->home_score ?? 0) . ' - ' . ($this->away_score ?? 0),
            'status' => $this->status,
            'date' => $this->match_date->format('d/m/Y H:i'),
            'venue' => $this->venue ?? 'Stade inconnu',
            'referee' => $this->referee ?? 'Arbitre non spécifié',
            'attendance' => $this->attendance ?? 0,
            'weather' => $this->weather ?? 'Conditions inconnues',
            'home_formation' => $this->home_formation ?? 'Formation inconnue',
            'away_formation' => $this->away_formation ?? 'Formation inconnue',
            'home_coach' => $this->home_coach ?? 'Entraîneur inconnu',
            'away_coach' => $this->away_coach ?? 'Entraîneur inconnu'
        ];
    }

    /**
     * Met à jour les statistiques du match
     */
    public function updateStatistics(array $stats)
    {
        foreach ($stats as $key => $value) {
            if (in_array($key, $this->fillable)) {
                $this->$key = $value;
            }
        }
        $this->save();
        return $this;
    }
}
