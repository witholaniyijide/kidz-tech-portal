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
     * Adds first_submitted_at to preserve the original submission timestamp
     * even when a report is returned and resubmitted.
     */
    public function up(): void
    {
        Schema::table('tutor_reports', function (Blueprint $table) {
            $table->timestamp('first_submitted_at')->nullable()->after('submitted_at');
        });

        // Backfill existing records: set first_submitted_at to submitted_at for all reports
        // that have been submitted at least once
        DB::table('tutor_reports')
            ->whereNotNull('submitted_at')
            ->whereNull('first_submitted_at')
            ->update(['first_submitted_at' => DB::raw('submitted_at')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tutor_reports', function (Blueprint $table) {
            $table->dropColumn('first_submitted_at');
        });
    }
};
