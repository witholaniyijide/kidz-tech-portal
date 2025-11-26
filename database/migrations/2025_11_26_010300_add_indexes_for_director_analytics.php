<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Index for enrollment analytics and date-based filtering
            $table->index('created_at', 'students_created_at_index');
            // Index for status filtering in analytics
            $table->index('status', 'students_status_index');
        });

        Schema::table('tutor_reports', function (Blueprint $table) {
            // Note: status, month, and tutor_id indexes already exist from create migration
            // Only add new index for director approval tracking
            $table->index('approved_by_director_at', 'tutor_reports_director_approved_at_index');
        });

        Schema::table('tutor_assessments', function (Blueprint $table) {
            // Note: status and tutor_id indexes already exist from create migration
            // Add new indexes for time-based queries and director approval tracking
            $table->index('created_at', 'tutor_assessments_created_at_index');
            $table->index('approved_by_director_at', 'tutor_assessments_director_approved_at_index');
        });

        Schema::table('tutors', function (Blueprint $table) {
            // Index for active tutor filtering
            $table->index('status', 'tutors_status_index');
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
            $table->dropIndex('students_created_at_index');
            $table->dropIndex('students_status_index');
        });

        Schema::table('tutor_reports', function (Blueprint $table) {
            $table->dropIndex('tutor_reports_director_approved_at_index');
        });

        Schema::table('tutor_assessments', function (Blueprint $table) {
            $table->dropIndex('tutor_assessments_created_at_index');
            $table->dropIndex('tutor_assessments_director_approved_at_index');
        });

        Schema::table('tutors', function (Blueprint $table) {
            $table->dropIndex('tutors_status_index');
        });
    }
};
