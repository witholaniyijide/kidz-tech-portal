<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update any invalid type values to 'info' before modifying enum
        DB::table('manager_notifications')
            ->whereNotIn('type', ['info', 'alert', 'report', 'attendance', 'system'])
            ->update(['type' => 'info']);

        DB::table('director_notifications')
            ->whereNotIn('type', ['info', 'alert', 'report', 'attendance', 'system'])
            ->update(['type' => 'info']);

        DB::table('tutor_notifications')
            ->whereNotIn('type', ['info', 'alert', 'report', 'attendance', 'system'])
            ->update(['type' => 'info']);

        // Now safely modify the ENUM columns
        DB::statement("ALTER TABLE manager_notifications MODIFY COLUMN type ENUM('info', 'alert', 'report', 'attendance', 'system', 'notice', 'assessment') DEFAULT 'info'");
        DB::statement("ALTER TABLE director_notifications MODIFY COLUMN type ENUM('info', 'alert', 'report', 'attendance', 'system', 'notice', 'assessment') DEFAULT 'info'");
        DB::statement("ALTER TABLE tutor_notifications MODIFY COLUMN type ENUM('info', 'alert', 'report', 'attendance', 'system', 'notice', 'assessment') DEFAULT 'info'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert manager_notifications
        DB::statement("ALTER TABLE manager_notifications MODIFY COLUMN type ENUM('info', 'alert', 'report', 'attendance', 'system') DEFAULT 'info'");

        // Revert director_notifications
        DB::statement("ALTER TABLE director_notifications MODIFY COLUMN type ENUM('info', 'alert', 'report', 'attendance', 'system') DEFAULT 'info'");

        // Revert tutor_notifications
        DB::statement("ALTER TABLE tutor_notifications MODIFY COLUMN type ENUM('info', 'alert', 'report', 'attendance', 'system') DEFAULT 'info'");
    }
};
