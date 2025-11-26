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
            // Index for report status filtering in analytics
            $table->index('status', 'tutor_reports_status_index');
            // Index for monthly report grouping
            $table->index('month', 'tutor_reports_month_index');
            // Index for tutor performance queries
            $table->index('tutor_id', 'tutor_reports_tutor_id_index');
            // Index for director approval tracking
            $table->index('approved_by_director_at', 'tutor_reports_director_approved_at_index');
        });

        Schema::table('tutor_assessments', function (Blueprint $table) {
            // Index for assessment status filtering
            $table->index('status', 'tutor_assessments_status_index');
            // Index for tutor performance analytics
            $table->index('tutor_id', 'tutor_assessments_tutor_id_index');
            // Index for time-based queries
            $table->index('created_at', 'tutor_assessments_created_at_index');
            // Index for director approval tracking
            $table->index('approved_by_director_at', 'tutor_assessments_director_approved_at_index');
        });

        Schema::table('tutors', function (Blueprint $table) {
            // Index for active tutor filtering
            $table->index('status', 'tutors_status_index');
        });

        Schema::table('director_activity_logs', function (Blueprint $table) {
            // Index for activity log filtering by model type
            $table->index('model_type', 'director_activity_logs_model_type_index');
            // Index for activity log filtering by action
            $table->index('action', 'director_activity_logs_action_index');
            // Index for date-based filtering
            $table->index('created_at', 'director_activity_logs_created_at_index');
            // Index for user tracking
            $table->index('user_id', 'director_activity_logs_user_id_index');
        });
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
            $table->dropIndex('tutor_reports_status_index');
            $table->dropIndex('tutor_reports_month_index');
            $table->dropIndex('tutor_reports_tutor_id_index');
            $table->dropIndex('tutor_reports_director_approved_at_index');
        });

        Schema::table('tutor_assessments', function (Blueprint $table) {
            $table->dropIndex('tutor_assessments_status_index');
            $table->dropIndex('tutor_assessments_tutor_id_index');
            $table->dropIndex('tutor_assessments_created_at_index');
            $table->dropIndex('tutor_assessments_director_approved_at_index');
        });

        Schema::table('tutors', function (Blueprint $table) {
            $table->dropIndex('tutors_status_index');
        });

        Schema::table('director_activity_logs', function (Blueprint $table) {
            $table->dropIndex('director_activity_logs_model_type_index');
            $table->dropIndex('director_activity_logs_action_index');
            $table->dropIndex('director_activity_logs_created_at_index');
            $table->dropIndex('director_activity_logs_user_id_index');
        });
    }
};
