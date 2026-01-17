<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MatchEvent;
use App\Models\MatchLineup;
use App\Models\Team; 

class Player extends Model
{
    use HasFactory;
 
    protected $fillable = [
        'team_id',
        'first_name',
        'last_name',
        'jersey_number',
        'position',
        'birth_date',
        'height',
        'nationality',
        'photo_path',
        'goals',
        'assists',
        'yellow_cards',
        'red_cards',
        'matches_played',
        'minutes_played',
        'passes_completed',
        'pass_accuracy',
        'tackles',
        'interceptions',
        'fouls_committed',
        'fouls_suffered',
        'shots_on_target',
        'dribbles',
    ];

    protected $casts = [
        'birth_date' => 'date'
    ];

    // ------------------------------------
    // 🔗 RELATIONS
    // ------------------------------------
    
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
    
    public function matchEvents()
    {
        // Événements où ce joueur est l'acteur principal (but, carton, etc.)
        return $this->hasMany(MatchEvent::class, 'player_id'); 
    }
    
    /**
     * Relation pour les passes décisives (Assists). 
     * Événements où ce joueur a fait la passe décisive.
     */
    public function assists()
    {
        return $this->hasMany(MatchEvent::class, 'assist_player_id');
    }

    /**
     * Relation pour la composition d'équipe (Lineup).
     */
    public function lineups()
    {
        return $this->hasMany(MatchLineup::class);
    }

    // ------------------------------------
    // Accessors et Mutators
    // ------------------------------------
    
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getNameAttribute()
    {
        return $this->full_name;
    }

    public function getAgeAttribute()
    {
        return $this->birth_date ? $this->birth_date->age : null;
    }
    
    // 📸 Correction mineure du chemin par défaut pour plus de clarté
    public function getPhotoUrlAttribute()
    {
        // Si photo_path existe, utilise le lien symbolique. Sinon, utilise une image par défaut.
        return $this->photo_path ? asset('storage/' . $this->photo_path) : asset('images/default-player.png');
    }

    // ------------------------------------
    // 🔢 Fonctions de Statistiques
    // ------------------------------------
    
    public function getGoalsCount(): int
    {
        return $this->matchEvents()->where('event_type', 'goal')->count();
    }

    public function getAssistsCount(): int
    {
        // Utilise la relation 'assists' qui est bien définie pour les événements où il est passeur
        // On filtre uniquement les événements de type 'goal' car une passe décisive mène forcément à un but.
        return $this->assists()->where('event_type', 'goal')->count(); 
    }

    public function getYellowCardsCount(): int
    {
        return $this->matchEvents()->where('event_type', 'yellow_card')->count();
    }

    public function getRedCardsCount(): int
    {
        return $this->matchEvents()->where('event_type', 'red_card')->count();
    }
}