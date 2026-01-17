<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('match_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained()->onDelete('cascade');
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->foreignId('player_id')->constrained()->onDelete('cascade');
            $table->foreignId('assist_player_id')->nullable()->constrained('players')->onDelete('set null');
            $table->enum('event_type', ['goal', 'yellow_card', 'red_card', 'substitution', 'own_goal']);
            $table->integer('minute');
            $table->string('additional_time')->nullable(); // +2, +3, etc.
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('match_events');
    }
};