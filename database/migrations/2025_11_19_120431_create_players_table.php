<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->integer('jersey_number');
            $table->string('position'); // goalkeeper, defender, midfielder, forward
            $table->date('birth_date')->nullable();
            $table->string('photo')->nullable();
            $table->string('nationality')->default('DRC');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('players');
    }
};