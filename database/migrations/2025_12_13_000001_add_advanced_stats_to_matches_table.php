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
            // Statistiques avancées
            $table->integer('home_yellow_cards')->default(0);
            $table->integer('home_red_cards')->default(0);
            $table->integer('away_yellow_cards')->default(0);
            $table->integer('away_red_cards')->default(0);
            
            $table->integer('home_possession')->default(50);
            $table->integer('away_possession')->default(50);
            
            $table->integer('home_shots')->default(0);
            $table->integer('away_shots')->default(0);
            
            $table->integer('home_shots_on_target')->default(0);
            $table->integer('away_shots_on_target')->default(0);
            
            $table->integer('home_saves')->default(0);
            $table->integer('away_saves')->default(0);
            
            $table->integer('home_free_kicks')->default(0);
            $table->integer('away_free_kicks')->default(0);
            
            $table->integer('home_throw_ins')->default(0);
            $table->integer('away_throw_ins')->default(0);
            
            $table->integer('home_goalkicks')->default(0);
            $table->integer('away_goalkicks')->default(0);
            
            $table->integer('home_penalties')->default(0);
            $table->integer('away_penalties')->default(0);
            
            // Informations supplémentaires sur le match
            $table->string('referee')->nullable();
            $table->string('weather')->nullable();
            $table->integer('temperature')->nullable();
            $table->integer('humidity')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn([
                'home_yellow_cards', 'home_red_cards', 'away_yellow_cards', 'away_red_cards',
                'home_possession', 'away_possession',
                'home_shots', 'away_shots',
                'home_shots_on_target', 'away_shots_on_target',
                'home_saves', 'away_saves',
                'home_free_kicks', 'away_free_kicks',
                'home_throw_ins', 'away_throw_ins',
                'home_goalkicks', 'away_goalkicks',
                'home_penalties', 'away_penalties',
                'referee', 'weather', 'temperature', 'humidity'
            ]);
        });
    }
};