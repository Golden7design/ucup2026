<?php
// database/migrations/..._add_aggregated_stats_to_matches_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            // Stats pour l'équipe domicile (Home)
            $table->unsignedSmallInteger('home_fouls')->default(0)->after('home_score');
            $table->unsignedSmallInteger('home_corners')->default(0)->after('home_fouls');
            $table->unsignedSmallInteger('home_offsides')->default(0)->after('home_corners');

            // Stats pour l'équipe visiteuse (Away)
            $table->unsignedSmallInteger('away_fouls')->default(0)->after('away_score');
            $table->unsignedSmallInteger('away_corners')->default(0)->after('away_fouls');
            $table->unsignedSmallInteger('away_offsides')->default(0)->after('away_corners');
        });
    }

    public function down(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn(['home_fouls', 'home_corners', 'home_offsides', 'away_fouls', 'away_corners', 'away_offsides']);
        });
    }
};