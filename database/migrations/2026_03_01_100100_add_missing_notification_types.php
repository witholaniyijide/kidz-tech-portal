<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This migration adds all missing notification types to the ENUM columns.
     * Without these, inserting notifications with certain types causes 500 errors.
     */
    public function up(): void
    {
        // Update any invalid type values before modifying enum
        DB::table('tutor_notifications')
            ->whereNotIn('type', ['info', 'alert', 'schedule', 'payment', 'system', 'notice', 'assessment'])
            ->update(['type' => 'info']);

        DB::table('manager_notifications')
            ->whereNotIn('type', ['info', 'alert', 'report', 'attendance', 'system', 'notice', 'assessment'])
            ->update(['type' => 'info']);

        // Add 'message' type to tutor_notifications
        DB::statement("ALTER TABLE tutor_notifications MODIFY COLUMN type ENUM('info', 'alert', 'schedule', 'payment', 'system', 'notice', 'assessment', 'message') DEFAULT 'info'");

        // Add 'message' type to manager_notifications
        DB::statement("ALTER TABLE manager_notifications MODIFY COLUMN type ENUM('info', 'alert', 'report', 'attendance', 'system', 'notice', 'assessment', 'message') DEFAULT 'info'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Update any 'message' type back to 'info' before reverting
        DB::table('tutor_notifications')
            ->where('type', 'message')
            ->update(['type' => 'info']);

        DB::table('manager_notifications')
            ->where('type', 'message')
            ->update(['type' => 'info']);

        // Revert tutor_notifications
        DB::statement("ALTER TABLE tutor_notifications MODIFY COLUMN type ENUM('info', 'alert', 'schedule', 'payment', 'system', 'notice', 'assessment') DEFAULT 'info'");

        // Revert manager_notifications
        DB::statement("ALTER TABLE manager_notifications MODIFY COLUMN type ENUM('info', 'alert', 'report', 'attendance', 'system', 'notice', 'assessment') DEFAULT 'info'");
    }
};
