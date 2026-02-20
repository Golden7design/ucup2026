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

        if ($driver === 'mysql') {
            $enumList = "'" . implode("','", $values) . "'";
            DB::statement("ALTER TABLE match_events MODIFY event_type ENUM($enumList) NOT NULL");
        } elseif ($driver === 'pgsql') {
            $enumRow = DB::selectOne(
                "SELECT t.typname AS enum_name
                 FROM pg_type t
                 JOIN pg_attribute a ON a.atttypid = t.oid
                 JOIN pg_class c ON c.oid = a.attrelid
                 WHERE c.relname = 'match_events'
                   AND a.attname = 'event_type'
                   AND t.typcategory = 'E'
                 LIMIT 1"
            );

            if ($enumRow && !empty($enumRow->enum_name)) {
                $enumName = $enumRow->enum_name;
                foreach ($values as $value) {
                    $safeValue = str_replace("'", "''", $value);
                    DB::statement(
                        "DO $$ BEGIN
                            IF NOT EXISTS (
                                SELECT 1
                                FROM pg_type t
                                JOIN pg_enum e ON t.oid = e.enumtypid
                                WHERE t.typname = '{$enumName}' AND e.enumlabel = '{$safeValue}'
                            ) THEN
                                ALTER TYPE {$enumName} ADD VALUE '{$safeValue}';
                            END IF;
                        END $$;"
                    );
                }
            }
        }
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

        if ($driver === 'mysql') {
            $enumList = "'" . implode("','", $values) . "'";
            DB::statement("ALTER TABLE match_events MODIFY event_type ENUM($enumList) NOT NULL");
        }
    }
};
