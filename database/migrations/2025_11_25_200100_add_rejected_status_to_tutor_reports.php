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
        // First, check if there are any invalid status values and update them
        $invalidStatuses = DB::table('tutor_reports')
            ->whereNotIn('status', ['draft', 'submitted', 'approved-by-manager', 'approved-by-director'])
            ->get();

        if ($invalidStatuses->count() > 0) {
            // Update any invalid statuses to 'draft' as a safe default
            DB::table('tutor_reports')
                ->whereNotIn('status', ['draft', 'submitted', 'approved-by-manager', 'approved-by-director'])
                ->update(['status' => 'draft']);
        }

        // Now safely add 'rejected' to the status enum
        DB::statement("ALTER TABLE tutor_reports MODIFY COLUMN status ENUM('draft', 'submitted', 'approved-by-manager', 'approved-by-director', 'rejected') DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Before removing 'rejected', update any rejected reports to 'draft'
        DB::table('tutor_reports')
            ->where('status', 'rejected')
            ->update(['status' => 'draft']);

        // Remove 'rejected' from the status enum
        DB::statement("ALTER TABLE tutor_reports MODIFY COLUMN status ENUM('draft', 'submitted', 'approved-by-manager', 'approved-by-director') DEFAULT 'draft'");
    }
};
