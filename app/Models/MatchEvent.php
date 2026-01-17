<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Assurez-vous d'importer les modèles Player, Team et MatchModel
use App\Models\Player;
use App\Models\Team;
use App\Models\MatchModel; 

class MatchEvent extends Model
{
    use HasFactory;

    protected $table = 'match_events'; // Assurez-vous que le nom de votre table est correct

    protected $fillable = [
        'match_id',        
        'team_id',         
        'player_id',        // Joueur principal (buteur, cartonné, entrant)
        'event_type',       // Type d'événement ('goal', 'red_card', 'substitution_in', etc.)
        'minute',           // Minute du match
        'assist_player_id', // Joueur passeur (pour les buts)
        'out_player_id',    // Joueur sortant (pour les substitutions 'substitution_in')
    ];

    // --- RELATIONS ---

    /**
     * Relation vers le Match associé.
     */
    public function match()
    {
        return $this->belongsTo(MatchModel::class, 'match_id');
    }

    /**
     * Relation vers l'équipe concernée par l'événement.
     * Requis pour l'affichage de l'équipe dans live.blade.php ($event->team->university->short_name)
     */
    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id'); 
    }
    
    /**
     * Relation vers le joueur principal de l'événement (buteur, cartonné, entrant).
     * Requis pour l'affichage dans live.blade.php ($event->player->...)
     */
    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }

    /**
     * Relation vers le joueur qui a fait la passe décisive.
     * Requis pour l'affichage dans live.blade.php ($event->assistPlayer->...)
     */
    public function assistPlayer()
    {
        return $this->belongsTo(Player::class, 'assist_player_id');
    }

    /**
     * Relation vers le joueur qui est sorti lors d'une substitution (uniquement pour event_type='substitution_in').
     * Requis pour l'affichage dans live.blade.php ($event->outPlayer->...)
     */
    public function outPlayer()
    {
        return $this->belongsTo(Player::class, 'out_player_id');
    }
}