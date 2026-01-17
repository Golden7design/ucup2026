<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lineups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained('matches')->onDelete('cascade');
            $table->foreignId('team_id')->constrained('teams')->onDelete('cascade');
            $table->foreignId('player_id')->constrained('players')->onDelete('cascade');
            $table->boolean('is_starter')->default(false);
            $table->string('position')->nullable();
            $table->timestamps();
            
            // Assurer qu'un joueur ne peut être que dans une seule composition par match
            $table->unique(['match_id', 'player_id']); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lineups');
    }
};
