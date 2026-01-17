<?php

namespace App\Services;

use App\Models\MatchModel;
use App\Models\Team;
use App\Models\Standing;

class StandingService
{
    /**
     * Calcule et met à jour les classements pour un match terminé.
     */
    public function updateStandingsForMatch(MatchModel $match): void
    {
        // 🚨 CORRECTION : Ignorer si ce n'est pas un match de tournoi
        if ($match->match_type !== 'tournament') {
            return; 
        }
        
        if ($match->status !== 'finished') {
            return;
        }

        $homeTeam = $match->homeTeam;
        $awayTeam = $match->awayTeam;

        $homeScore = $match->home_score ?? 0;
        $awayScore = $match->away_score ?? 0;

        // Mise à jour des classements pour l'équipe à domicile
        $this->updateTeamStanding($homeTeam, $homeScore, $awayScore, $match->group);

        // Mise à jour des classements pour l'équipe à l'extérieur
        $this->updateTeamStanding($awayTeam, $awayScore, $homeScore, $match->group);
        
        // Déclencher l'événement de mise à jour des classements
        event(new \App\Events\StandingsUpdated($match->group));
    }

    /**
     * Met à jour les statistiques de classement pour une équipe donnée.
     * @param Team $team
     * @param int $scoreFor (Buts marqués par l'équipe)
     * @param int $scoreAgainst (Buts encaissés par l'équipe)
     * @param string $group (Nom du groupe A, B, C, etc.)
     */
    protected function updateTeamStanding(Team $team, int $scoreFor, int $scoreAgainst, string $group): void
    {
        // 1. Définir les points, victoires, défaites
        $points = 0;
        $won = 0; 
        $drawn = 0;
        $lost = 0;

        if ($scoreFor > $scoreAgainst) {
            $points = 3;
            $won = 1; 
        } elseif ($scoreFor === $scoreAgainst) {
            $points = 1;
            $drawn = 1; 
        } else {
            $lost = 1; 
        }
        
        // 2. Trouver ou créer l'entrée Standing
        $standing = Standing::firstOrNew(['team_id' => $team->id, 'group' => $group]); 

        // Assurez-vous d'initialiser les valeurs si le modèle est nouveau
        if (!$standing->exists) {
            $standing->group = $group;
            $standing->played = 0;
            $standing->points = 0;
            $standing->won = 0; 
            $standing->drawn = 0; 
            $standing->lost = 0; 
            $standing->goals_for = 0;
            $standing->goals_against = 0;
            $standing->goal_difference = 0;
        }

        // 3. Mettre à jour les totaux cumulés
        $standing->played += 1;
        $standing->points += $points;
        $standing->won += $won; 
        $standing->drawn += $drawn; 
        $standing->lost += $lost; 
        $standing->goals_for += $scoreFor;
        $standing->goals_against += $scoreAgainst;
        $standing->goal_difference = $standing->goals_for - $standing->goals_against;

        $standing->save();
    }

    /**
     * Reconstruit tout le classement à partir de tous les matchs terminés.
     */
    public function recalculateAllStandings(): void
    {
        // 1. Réinitialiser tous les classements existants
        Standing::query()->delete();

        // 2. Récupérer tous les matchs terminés
        $finishedMatches = MatchModel::where('status', 'finished')
                             // 🚨 CORRECTION : Filtrer uniquement les matchs de tournoi
                             ->where('match_type', 'tournament') 
                             ->orderBy('match_date', 'asc')
                             ->get();

        // 3. Calculer les statistiques pour chaque match
        foreach ($finishedMatches as $match) {
            // Nous rechargeons les relations homeTeam et awayTeam si elles ne sont pas chargées (plus sûr)
            $match->load(['homeTeam', 'awayTeam']); 
            // updateStandingsForMatch gère déjà le filtre, mais on le fait ici aussi pour la requête initiale
            $this->updateStandingsForMatch($match); 
        }
        
        // Déclencher l'événement pour tous les groupes après la recalcul
        event(new \App\Events\StandingsUpdated());
    }
    
    /**
     * Obtenir les classements mis à jour en temps réel pour un groupe spécifique ou tous les groupes.
     *
     * @param string|null $group
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getLiveStandings($group = null)
    {
        $query = Standing::with('team.university')
                        ->orderByDesc('points')
                        ->orderByDesc('goal_difference')
                        ->orderByDesc('goals_for');
        
        if ($group) {
            $query->where('group', $group);
        }
        
        return $query->get();
    }
    
    /**
     * Obtenir les classements groupés par groupe pour l'affichage.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getGroupedStandings()
    {
        $allStandings = $this->getLiveStandings();
        
        if ($allStandings->isEmpty()) {
            return collect();
        }
        
        return $allStandings->groupBy('group')->sortKeys();
    }
}