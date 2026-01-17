<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('coach')->nullable();
            $table->string('category')->default('senior'); // senior, junior, etc.
            $table->integer('year');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('teams');
    }
};