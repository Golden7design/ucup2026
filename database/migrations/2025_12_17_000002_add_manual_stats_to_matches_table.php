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
        Schema::table('matches', function (Blueprint $table) {
            // Statistiques manuelles pour l'équipe à domicile
            // home_shots et away_shots existent déjà, nous ne les ajoutons pas
            $table->integer('home_possession')->nullable()->change(); // Déjà existe, mais on le rend nullable
            
            // Vérifier si les colonnes existent avant de les ajouter
            if (!Schema::hasColumn('matches', 'home_shots_on_target')) {
                $table->integer('home_shots_on_target')->nullable()->default(0);
            }
            if (!Schema::hasColumn('matches', 'home_corners')) {
                $table->integer('home_corners')->nullable()->default(0);
            }
            if (!Schema::hasColumn('matches', 'home_fouls')) {
                $table->integer('home_fouls')->nullable()->default(0);
            }
            if (!Schema::hasColumn('matches', 'home_yellow_cards')) {
                $table->integer('home_yellow_cards')->nullable()->default(0);
            }
            if (!Schema::hasColumn('matches', 'home_red_cards')) {
                $table->integer('home_red_cards')->nullable()->default(0);
            }
            if (!Schema::hasColumn('matches', 'home_offsides')) {
                $table->integer('home_offsides')->nullable()->default(0);
            }
            
            // Statistiques manuelles pour l'équipe à l'extérieur
            $table->integer('away_possession')->nullable()->change(); // Déjà existe, mais on le rend nullable
            
            if (!Schema::hasColumn('matches', 'away_shots_on_target')) {
                $table->integer('away_shots_on_target')->nullable()->default(0);
            }
            if (!Schema::hasColumn('matches', 'away_corners')) {
                $table->integer('away_corners')->nullable()->default(0);
            }
            if (!Schema::hasColumn('matches', 'away_fouls')) {
                $table->integer('away_fouls')->nullable()->default(0);
            }
            if (!Schema::hasColumn('matches', 'away_yellow_cards')) {
                $table->integer('away_yellow_cards')->nullable()->default(0);
            }
            if (!Schema::hasColumn('matches', 'away_red_cards')) {
                $table->integer('away_red_cards')->nullable()->default(0);
            }
            if (!Schema::hasColumn('matches', 'away_offsides')) {
                $table->integer('away_offsides')->nullable()->default(0);
            }
            
            // Notes et commentaires de l'admin
            if (!Schema::hasColumn('matches', 'admin_notes')) {
                $table->text('admin_notes')->nullable();
            }
            if (!Schema::hasColumn('matches', 'match_report')) {
                $table->text('match_report')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            // Supprimer les colonnes ajoutées
            $table->dropColumn([
                'home_shots',
                'home_shots_on_target',
                'home_corners',
                'home_fouls',
                'home_yellow_cards',
                'home_red_cards',
                'home_offsides',
                'away_shots',
                'away_shots_on_target',
                'away_corners',
                'away_fouls',
                'away_yellow_cards',
                'away_red_cards',
                'away_offsides',
                'admin_notes',
                'match_report'
            ]);
            
            // Remettre les colonnes existantes à leur état d'origine
            $table->integer('home_possession')->default(50)->change();
            $table->integer('away_possession')->default(50)->change();
        });
    }
};