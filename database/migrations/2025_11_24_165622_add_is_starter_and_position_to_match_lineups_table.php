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
        Schema::table('match_lineups', function (Blueprint $table) {
            // 1. Ajout de 'is_starter' qui manquait et est nécessaire pour différencier les joueurs
            if (!Schema::hasColumn('match_lineups', 'is_starter')) {
                $table->boolean('is_starter')->default(false)->after('player_id'); 
            }
            
            // 2. Ajout de 'position' comme prévu, après 'is_starter'
            if (!Schema::hasColumn('match_lineups', 'position')) {
                $table->string('position', 10)->nullable()->after('is_starter');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('match_lineups', function (Blueprint $table) {
            if (Schema::hasColumn('match_lineups', 'position')) {
                $table->dropColumn('position');
            }
            if (Schema::hasColumn('match_lineups', 'is_starter')) {
                 $table->dropColumn('is_starter');
            }
        });
    }
};