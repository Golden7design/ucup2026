<?php

// database/migrations/[timestamp]_add_timer_paused_at_to_matches_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->timestamp('timer_paused_at')->nullable()->after('start_time');
        });
    }

    public function down()
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn('timer_paused_at');
        });
    }
};
