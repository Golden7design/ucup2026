<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('matches', function (Blueprint $table) {
            // Utilisé pour stocker l'heure réelle à laquelle le match est passé en 'live'
            $table->timestamp('start_time')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn('start_time');
        });
    }
};
