<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\University;
use App\Models\Team;
use App\Models\Player;
use App\Models\MatchModel; // 🚨 CORRECTION : Importation directe de MatchModel
use App\Models\Standing;
use App\Models\MatchEvent;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    
    
    public function run() : void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'emoukouanga@gmail.com',
            'password' => Hash::make('AdminUCup2026'),
            'email_verified_at' => now(),
            'remember_token' => \Illuminate\Support\Str::random(5),
            
            // Ajoutez ici un rôle si vous utilisez un système de rôles (ex: 'role' => 'admin')
        ]);

        // 1. Création des Universités
        $uni1 = University::create([
            'name' => 'Université de Pointe-Noire',
            'short_name' => 'UPN',
            'colors' => 'Blue/White',
            'description' => 'La grande université publique.'
        ]);

        $uni2 = University::create([
            'name' => 'Institut Supérieur de Technologie',
            'short_name' => 'IST',
            'colors' => 'Red/Black',
            'description' => 'Formation technique de pointe.'
        ]);
        
        $uni3 = University::create([
            'name' => 'École Supérieure de Commerce',
            'short_name' => 'ESC',
            'colors' => 'Green/Gold',
            'description' => 'Les futurs managers.'
        ]);

        // 2. Création des Équipes
        $team1 = Team::create([
            'university_id' => $uni1->id,
            'name' => 'UPN Lions',
            'coach' => 'Coach Mvuka',
            'category' => 'Senior',
            'year' => 2025
        ]);

        $team2 = Team::create([
            'university_id' => $uni2->id,
            'name' => 'IST Techs',
            'coach' => 'Coach Samba',
            'category' => 'Senior',
            'year' => 2025
        ]);
        
        $team3 = Team::create([
            'university_id' => $uni3->id,
            'name' => 'ESC Business',
            'coach' => 'Coach Ndoki',
            'category' => 'Senior',
            'year' => 2025
        ]);

        // 3. Création des Joueurs (5 par équipe pour tester)
        $positions = ['goalkeeper', 'defender', 'midfielder', 'forward'];
        
        foreach ([$team1, $team2, $team3] as $team) {
            for ($i = 1; $i <= 5; $i++) {
                Player::create([
                    'team_id' => $team->id,
                    'first_name' => 'Joueur',
                    'last_name' => $team->university->short_name . ' ' . $i,
                    'jersey_number' => $i * 2,
                    'position' => $positions[array_rand($positions)],
                    'birth_date' => '2000-01-01',
                    'nationality' => 'CG'
                ]);
            }
        }

        // 4. Création des Matchs

        // Match en direct (Live)
        $liveMatch = MatchModel::create([
            'home_team_id' => $team1->id,
            'away_team_id' => $team2->id,
            'match_date' => Carbon::now()->subMinutes(45),
            'venue' => 'Stade Municipal',
            'status' => 'live',
            'home_score' => 1,
            'away_score' => 1,
            'round' => 'group_stage',
            'group' => 'A'
        ]);

        // Événement but pour le match en direct
        MatchEvent::create([
            'match_id' => $liveMatch->id,
            'team_id' => $team1->id,
            'player_id' => $team1->players->first()->id,
            'event_type' => 'goal',
            'minute' => 23,
            'description' => 'Superbe frappe de loin'
        ]);

        // Match Terminé
        $finishedMatch = MatchModel::create([
            'home_team_id' => $team2->id,
            'away_team_id' => $team3->id,
            'match_date' => Carbon::yesterday(),
            'venue' => 'Complexe Sportif',
            'status' => 'finished',
            'home_score' => 2,
            'away_score' => 0,
            'round' => 'group_stage',
            'group' => 'A'
        ]);

        // Mise à jour classement (Simulation)
        Standing::create([
            'team_id' => $team2->id,
            'group' => 'A',
            'played' => 1, 'won' => 1, 'drawn' => 0, 'lost' => 0,
            'goals_for' => 2, 'goals_against' => 0, 'goal_difference' => 2,
            'points' => 3
        ]);

        Standing::create([
            'team_id' => $team3->id,
            'group' => 'A',
            'played' => 1, 'won' => 0, 'drawn' => 0, 'lost' => 1,
            'goals_for' => 0, 'goals_against' => 2, 'goal_difference' => -2,
            'points' => 0
        ]);

        // Match à venir
        MatchModel::create([
            'home_team_id' => $team1->id,
            'away_team_id' => $team3->id,
            'match_date' => Carbon::tomorrow()->addHours(14),
            'venue' => 'Stade Municipal',
            'status' => 'scheduled',
            'home_score' => 0,
            'away_score' => 0,
            'round' => 'group_stage',
            'group' => 'A'
        ]);
    }
}