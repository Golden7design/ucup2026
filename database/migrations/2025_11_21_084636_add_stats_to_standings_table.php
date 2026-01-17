<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('standings', function (Blueprint $table) {
            // Vérifiez si la colonne 'wins' existe avant d'ajouter
            if (!Schema::hasColumn('standings', 'wins')) {
                $table->smallInteger('wins')->unsigned()->notNull()->default(0);
            }
            if (!Schema::hasColumn('standings', 'losses')) {
                $table->smallInteger('losses')->unsigned()->notNull()->default(0);
            }
            if (!Schema::hasColumn('standings', 'draws')) {
                $table->smallInteger('draws')->unsigned()->notNull()->default(0);
            }
            // Ajoutez d'autres vérifications pour les autres colonnes de stats...
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('standings', function (Blueprint $table) {
            $table->dropColumn([
                'group', 'played', 'points', 'wins', 'draws', 'losses', 
                'goals_for', 'goals_against', 'goal_difference'
            ]);
        });
    }
};