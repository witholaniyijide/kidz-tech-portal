<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This migration adds columns that are defined in the TutorReport model's
     * $fillable array but were never created in the database.
     */
    public function up(): void
    {
        Schema::table('tutor_reports', function (Blueprint $table) {
            // Add period_from and period_to columns for report date ranges
            if (!Schema::hasColumn('tutor_reports', 'period_from')) {
                $table->date('period_from')->nullable()->after('year');
            }
            if (!Schema::hasColumn('tutor_reports', 'period_to')) {
                $table->date('period_to')->nullable()->after('period_from');
            }

            // Add content and summary columns for report text
            if (!Schema::hasColumn('tutor_reports', 'content')) {
                $table->text('content')->nullable()->after('projects');
            }
            if (!Schema::hasColumn('tutor_reports', 'summary')) {
                $table->text('summary')->nullable()->after('content');
            }

            // Add rating column for overall report rating
            if (!Schema::hasColumn('tutor_reports', 'rating')) {
                $table->string('rating')->nullable()->after('performance_rating');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tutor_reports', function (Blueprint $table) {
            $columns = ['period_from', 'period_to', 'content', 'summary', 'rating'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('tutor_reports', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
