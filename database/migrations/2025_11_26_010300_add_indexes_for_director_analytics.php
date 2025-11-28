<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Check if an index exists on a table
     */
    private function indexExists($table, $index): bool
    {
        $indexes = DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$index]);
        return !empty($indexes);
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Index for enrollment analytics and date-based filtering
            if (!$this->indexExists('students', 'students_created_at_index')) {
                $table->index('created_at', 'students_created_at_index');
            }
            // Index for status filtering in analytics
            if (!$this->indexExists('students', 'students_status_index')) {
                $table->index('status', 'students_status_index');
            }
        });

        Schema::table('tutor_reports', function (Blueprint $table) {
            // Note: status, month, and tutor_id indexes already exist from create migration
            // Only add new index for director approval tracking
            if (!$this->indexExists('tutor_reports', 'tutor_reports_director_approved_at_index')) {
                $table->index('approved_by_director_at', 'tutor_reports_director_approved_at_index');
            }
        });

        Schema::table('tutor_assessments', function (Blueprint $table) {
            // Note: status and tutor_id indexes already exist from create migration
            // Add new indexes for time-based queries and director approval tracking
            if (!$this->indexExists('tutor_assessments', 'tutor_assessments_created_at_index')) {
                $table->index('created_at', 'tutor_assessments_created_at_index');
            }
            if (!$this->indexExists('tutor_assessments', 'tutor_assessments_director_approved_at_index')) {
                $table->index('approved_by_director_at', 'tutor_assessments_director_approved_at_index');
            }
        });

        Schema::table('tutors', function (Blueprint $table) {
            // Index for active tutor filtering
            if (!$this->indexExists('tutors', 'tutors_status_index')) {
                $table->index('status', 'tutors_status_index');
            }
        });

        // Note: director_activity_logs already has all necessary indexes from create migration
        // (director_id, action_type, [model_type, model_id], created_at)
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if ($this->indexExists('students', 'students_created_at_index')) {
                $table->dropIndex('students_created_at_index');
            }
            if ($this->indexExists('students', 'students_status_index')) {
                $table->dropIndex('students_status_index');
            }
        });

        Schema::table('tutor_reports', function (Blueprint $table) {
            if ($this->indexExists('tutor_reports', 'tutor_reports_director_approved_at_index')) {
                $table->dropIndex('tutor_reports_director_approved_at_index');
            }
        });

        Schema::table('tutor_assessments', function (Blueprint $table) {
            if ($this->indexExists('tutor_assessments', 'tutor_assessments_created_at_index')) {
                $table->dropIndex('tutor_assessments_created_at_index');
            }
            if ($this->indexExists('tutor_assessments', 'tutor_assessments_director_approved_at_index')) {
                $table->dropIndex('tutor_assessments_director_approved_at_index');
            }
        });

        Schema::table('tutors', function (Blueprint $table) {
            if ($this->indexExists('tutors', 'tutors_status_index')) {
                $table->dropIndex('tutors_status_index');
            }
        });
    }
};
