<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            // Ajout de la colonne 'match_type'. Utilisez un enum pour des valeurs limitées
            $table->enum('match_type', ['tournament', 'friendly'])->default('tournament')->after('group')->nullable();
            // 'tournament' sera la valeur par défaut pour les matchs existants.
        });
    }

    public function down(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn('match_type');
        });
    }
};