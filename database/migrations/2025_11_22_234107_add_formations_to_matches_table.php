<?php
// Fichier : database/migrations/XXXX_XX_XX_XXXXXX_add_formations_to_matches_table.php

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
            // home_formation (ex: 4-3-3, 4-2-3-1)
            $table->string('home_formation', 10)->nullable()->after('away_score');

            // away_formation (ex: 4-3-3, 4-2-3-1)
            $table->string('away_formation', 10)->nullable()->after('home_formation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn(['home_formation', 'away_formation']);
        });
    }
};