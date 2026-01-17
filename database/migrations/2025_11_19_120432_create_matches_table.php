<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('home_team_id')->constrained('teams')->onDelete('cascade');
            $table->foreignId('away_team_id')->constrained('teams')->onDelete('cascade');
            $table->dateTime('match_date');
            $table->string('venue');
            $table->enum('status', ['scheduled', 'live', 'halftime', 'finished', 'postponed'])->default('scheduled');
            $table->integer('home_score')->default(0);
            $table->integer('away_score')->default(0);
            $table->string('round')->nullable(); // group_stage, quarter_final, semi_final, final
            $table->string('group')->nullable(); // A, B, C, D
            $table->integer('attendance')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('matches');
    }
};