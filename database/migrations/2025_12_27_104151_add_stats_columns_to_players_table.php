<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('players', function (Blueprint $table) {
            // Colonnes de statistiques (dénormalisées pour les performances)
            if (!Schema::hasColumn('players', 'goals')) {
                $table->integer('goals')->unsigned()->default(0)->after('nationality');
            }
            if (!Schema::hasColumn('players', 'assists')) {
                $table->integer('assists')->unsigned()->default(0)->after('goals');
            }
            if (!Schema::hasColumn('players', 'yellow_cards')) {
                $table->integer('yellow_cards')->unsigned()->default(0)->after('assists');
            }
            if (!Schema::hasColumn('players', 'red_cards')) {
                $table->integer('red_cards')->unsigned()->default(0)->after('yellow_cards');
            }
            if (!Schema::hasColumn('players', 'matches_played')) {
                $table->integer('matches_played')->unsigned()->default(0)->after('red_cards');
            }
            if (!Schema::hasColumn('players', 'minutes_played')) {
                $table->integer('minutes_played')->unsigned()->default(0)->after('matches_played');
            }
            if (!Schema::hasColumn('players', 'passes_completed')) {
                $table->integer('passes_completed')->unsigned()->default(0)->after('minutes_played');
            }
            if (!Schema::hasColumn('players', 'pass_accuracy')) {
                $table->integer('pass_accuracy')->unsigned()->default(0)->after('passes_completed');
            }
            if (!Schema::hasColumn('players', 'tackles')) {
                $table->integer('tackles')->unsigned()->default(0)->after('pass_accuracy');
            }
            if (!Schema::hasColumn('players', 'interceptions')) {
                $table->integer('interceptions')->unsigned()->default(0)->after('tackles');
            }
            if (!Schema::hasColumn('players', 'fouls_committed')) {
                $table->integer('fouls_committed')->unsigned()->default(0)->after('interceptions');
            }
            if (!Schema::hasColumn('players', 'fouls_suffered')) {
                $table->integer('fouls_suffered')->unsigned()->default(0)->after('fouls_committed');
            }
            if (!Schema::hasColumn('players', 'shots_on_target')) {
                $table->integer('shots_on_target')->unsigned()->default(0)->after('fouls_suffered');
            }
            if (!Schema::hasColumn('players', 'dribbles')) {
                $table->integer('dribbles')->unsigned()->default(0)->after('shots_on_target');
            }
        });
    }

    public function down(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumnIfExists([
                'goals',
                'assists',
                'yellow_cards',
                'red_cards',
                'matches_played',
                'minutes_played',
                'passes_completed',
                'pass_accuracy',
                'tackles',
                'interceptions',
                'fouls_committed',
                'fouls_suffered',
                'shots_on_target',
                'dribbles',
            ]);
        });
    }
};