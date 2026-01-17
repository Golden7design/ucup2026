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
        Schema::table('lineups', function (Blueprint $table) {
            
            // 1. Renommage de l'ancienne colonne 'position' en 'role'
            // (Elle stockait 'starter' ou 'bench')
            $table->renameColumn('position', 'role');
            
            // 2. Ajout de la nouvelle colonne 'match_position'
            // (Elle stockera le poste tactique comme 'DC', 'MOC', 'BU')
            $table->string('match_position', 5)->nullable()->after('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lineups', function (Blueprint $table) {
            // Annulation : suppression de la nouvelle colonne
            $table->dropColumn('match_position');
            
            // Annulation : renommage inverse de la colonne
            $table->renameColumn('role', 'position');
        });
    }
};