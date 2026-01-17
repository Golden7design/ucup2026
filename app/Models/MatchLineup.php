<?php
// app/Models/MatchLineup.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchLineup extends Model
{
    use HasFactory;

    // Toutes les colonnes sauf 'id', 'created_at', 'updated_at' peuvent être assignées en masse
    protected $guarded = []; 

    // 🚨 CORRECTION CRUCIALE : Ajout du casting
    protected $casts = [
        'is_starter' => 'boolean', 
        'match_id' => 'integer',
        'team_id' => 'integer',
        'player_id' => 'integer',
    ];

    // Définir les relations pour l'accès aux données dans les vues
    public function match()
    {
        // Assurez-vous d'importer MatchModel au début si ce n'est pas fait (Non nécessaire ici)
        return $this->belongsTo(MatchModel::class, 'match_id');
    }

    public function team()
    {
        // Assurez-vous d'importer Team au début si ce n'est pas fait
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function player()
    {
        // Assurez-vous d'importer Player au début si ce n'est pas fait
        return $this->belongsTo(Player::class, 'player_id');
    }
}