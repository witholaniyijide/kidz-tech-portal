<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This migration fixes the 500 error when manager sends back report for correction:
     * 1. Adds 'returned' to the status enum
     * 2. Adds 'returned_at' timestamp column
     */
    public function up(): void
    {
        // Add 'returned' to the status enum
        DB::statement("ALTER TABLE tutor_reports MODIFY COLUMN status ENUM('draft', 'submitted', 'approved-by-manager', 'approved-by-director', 'rejected', 'returned') DEFAULT 'draft'");

        // Add returned_at column if it doesn't exist
        if (!Schema::hasColumn('tutor_reports', 'returned_at')) {
            Schema::table('tutor_reports', function (Blueprint $table) {
                $table->timestamp('returned_at')->nullable()->after('approved_by_director_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Update any 'returned' reports to 'draft' before removing enum value
        DB::table('tutor_reports')
            ->where('status', 'returned')
            ->update(['status' => 'draft']);

        // Remove 'returned' from the status enum
        DB::statement("ALTER TABLE tutor_reports MODIFY COLUMN status ENUM('draft', 'submitted', 'approved-by-manager', 'approved-by-director', 'rejected') DEFAULT 'draft'");

        // Drop returned_at column
        Schema::table('tutor_reports', function (Blueprint $table) {
            $table->dropColumn('returned_at');
        });
    }
};
