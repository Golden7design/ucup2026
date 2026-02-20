<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $values = [
            'goal',
            'penalty_goal',
            'own_goal',
            'yellow_card',
            'second_yellow',
            'red_card',
            'substitution',
            'substitution_in',
            'substitution_out',
            'injury',
            'penalty_missed',
            'big_chance_missed',
        ];

        $driver = DB::getDriverName();

        if ($driver !== 'pgsql') {
            return;
        }

        $constraintRow = DB::selectOne(
            "SELECT conname
             FROM pg_constraint
             WHERE conrelid = 'match_events'::regclass
               AND contype = 'c'
               AND conname LIKE '%event_type%check%'
             LIMIT 1"
        );

        if ($constraintRow && !empty($constraintRow->conname)) {
            $constraintName = $constraintRow->conname;
            DB::statement("ALTER TABLE match_events DROP CONSTRAINT {$constraintName}");
        }

        $list = "'" . implode("','", $values) . "'";
        DB::statement("ALTER TABLE match_events ADD CONSTRAINT match_events_event_type_check CHECK (event_type IN ($list))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $values = [
            'goal',
            'yellow_card',
            'red_card',
            'substitution',
            'own_goal',
        ];

        $driver = DB::getDriverName();

        if ($driver !== 'pgsql') {
            return;
        }

        $constraintRow = DB::selectOne(
            "SELECT conname
             FROM pg_constraint
             WHERE conrelid = 'match_events'::regclass
               AND contype = 'c'
               AND conname LIKE '%event_type%check%'
             LIMIT 1"
        );

        if ($constraintRow && !empty($constraintRow->conname)) {
            $constraintName = $constraintRow->conname;
            DB::statement("ALTER TABLE match_events DROP CONSTRAINT {$constraintName}");
        }

        $list = "'" . implode("','", $values) . "'";
        DB::statement("ALTER TABLE match_events ADD CONSTRAINT match_events_event_type_check CHECK (event_type IN ($list))");
    }
};
