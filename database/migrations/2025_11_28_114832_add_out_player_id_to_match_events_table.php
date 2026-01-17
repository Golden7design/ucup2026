<?php
// ... nom du fichier de migration

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
        Schema::table('match_events', function (Blueprint $table) {
            // Ajout de la colonne 'out_player_id' comme clé étrangère
            // Elle doit être nullable car elle n'est utilisée que pour les substitutions.
            $table->foreignId('out_player_id')
                  ->nullable()
                  ->constrained('players') // Assurez-vous que le nom de la table joueurs est 'players'
                  ->after('assist_player_id'); // Optionnel, pour un meilleur ordre
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('match_events', function (Blueprint $table) {
            // Suppression de la contrainte et de la colonne
            $table->dropForeign(['out_player_id']);
            $table->dropColumn('out_player_id');
        });
    }
};