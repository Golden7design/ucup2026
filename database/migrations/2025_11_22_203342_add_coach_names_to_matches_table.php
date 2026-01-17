<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->string('home_coach')->nullable()->after('away_score');
            $table->string('away_coach')->nullable()->after('home_coach');
        });
    }

    public function down()
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn(['home_coach', 'away_coach']);
        });
    }
};