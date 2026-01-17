<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Importation des modèles requis pour les relations
use App\Models\MatchModel;
use App\Models\Team;
use App\Models\Player;

class Lineup extends Model
{
    use HasFactory;
    
    // Le nom de la table de composition d'équipe dans votre base de données.
    // Il est souvent appelé 'lineups' ou 'match_lineups'.
    protected $table = 'lineups'; 

    protected $fillable = [
        'match_id',
        'team_id',
        'player_id',
        'is_starter', // Pour distinguer les titulaires des remplaçants
        'position',   // (Optionnel) Ex: 'GK', 'DEF', 'MID', 'FWD'
        'jersey_number', // (Optionnel, mais utile)
    ];

    // --- RELATIONS REQUISES PAR LE CONTROLEUR ---

    /**
     * Relation vers le joueur associé à cette composition.
     * Le LiveMatchController utilise ->with('player') pour charger les noms.
     */
    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }

    /**
     * Relation vers le match auquel appartient cette composition.
     */
    public function match()
    {
        return $this->belongsTo(MatchModel::class, 'match_id');
    }

    /**
     * Relation vers l'équipe à laquelle appartient ce joueur.
     */
    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
}