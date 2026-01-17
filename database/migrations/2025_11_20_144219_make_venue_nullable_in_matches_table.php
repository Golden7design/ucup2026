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
            // Rend la colonne 'venue' existante facultative (nullable)
            $table->string('venue')->nullable()->change(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            // Si vous annulez la migration, la colonne redevient NOT NULL
            $table->string('venue')->nullable(false)->change();
        });
    }
};