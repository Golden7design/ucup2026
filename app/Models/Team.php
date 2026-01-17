<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Match; // Inutile de réimporter si vous utilisez le FQN \App\Models\Match
use App\Models\University;
use App\Models\Player;
use App\Models\Standing;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'university_id',
        'name',
        'coach', // <--- Ceci est un attribut simple, pas une relation
        'category',
        'year'
    ];

    // Relations existantes et correctes:

    public function university()
    {
        return $this->belongsTo(University::class);
    }

    public function players()
    {
        return $this->hasMany(Player::class);
    }

    // Bonne pratique: utiliser le FQN pour éviter les conflits d'import
    public function homeMatches()
    {
        return $this->hasMany(\App\Models\Match::class, 'home_team_id');
    }

    public function awayMatches()
    {
        return $this->hasMany(\App\Models\Match::class, 'away_team_id');
    }

    /**
     * Retourne tous les matchs joués par cette équipe (à domicile ou à l'extérieur).
     * Note: Ceci retourne une Collection, pas une relation Query Builder.
     */
    public function matches()
    {
        return $this->homeMatches->merge($this->awayMatches);
    }

    public function standing()
    {
        return $this->hasOne(Standing::class);
    }
}