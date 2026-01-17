<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('match_lineups', function (Blueprint $table) {
            $table->id();
            
            // Clés étrangères
            $table->foreignId('match_id')->constrained('matches')->onDelete('cascade');
            $table->foreignId('team_id')->constrained('teams')->onDelete('cascade');
            $table->foreignId('player_id')->constrained('players')->onDelete('cascade');
            
            // Rôle du joueur : titulaire ou remplaçant
            $table->enum('role', ['starter', 'substitute']);
            
            // Position spécifique dans la formation (ex: GK, LW, CB)
            $table->string('starting_position')->nullable(); 
            
            // Ordre d'affichage du joueur (1 à 11 pour les titulaires, 12+ pour le banc)
            $table->unsignedSmallInteger('order_key')->nullable(); 

            $table->timestamps();
            
            // Contrainte : un joueur ne peut être dans la composition qu'une seule fois par match
            $table->unique(['match_id', 'player_id']); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('match_lineups');
    }
};