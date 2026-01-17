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
            $table->integer('elapsed_time')->nullable()->default(0);
            $table->integer('additional_time_first_half')->nullable()->default(0);
            $table->integer('additional_time_second_half')->nullable()->default(0);
            $table->boolean('is_extra_time')->nullable()->default(false);
            $table->boolean('is_penalty_shootout')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn(['elapsed_time', 'additional_time_first_half', 'additional_time_second_half', 'is_extra_time', 'is_penalty_shootout']);
        });
    }
};
