<?php
// .../database/migrations/XXXXXXXX_add_photo_path_to_players_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('players', function (Blueprint $table) {
            // Ajoute la colonne photo_path de type chaîne, qui peut être NULL
            $table->string('photo_path')->nullable()->after('height'); 
        });
    }

    public function down(): void
    {
        Schema::table('players', function (Blueprint $table) {
            // Supprime la colonne
            $table->dropColumn('photo_path');
        });
    }
};