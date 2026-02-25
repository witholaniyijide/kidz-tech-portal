<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tutor_assessments', function (Blueprint $table) {
            // Incident counts for event-based criteria
            if (!Schema::hasColumn('tutor_assessments', 'punctuality_late_count')) {
                $table->integer('punctuality_late_count')->default(0)->after('criteria_ratings');
            }
            if (!Schema::hasColumn('tutor_assessments', 'video_off_count')) {
                $table->integer('video_off_count')->default(0)->after('punctuality_late_count');
            }

            // Auto-calculated penalty deductions
            if (!Schema::hasColumn('tutor_assessments', 'punctuality_penalty')) {
                $table->integer('punctuality_penalty')->default(0)->after('video_off_count');
            }
            if (!Schema::hasColumn('tutor_assessments', 'video_penalty')) {
                $table->integer('video_penalty')->default(0)->after('punctuality_penalty');
            }
            if (!Schema::hasColumn('tutor_assessments', 'total_penalty_deductions')) {
                $table->integer('total_penalty_deductions')->default(0)->after('video_penalty');
            }

            // Student chips context (JSON array of {name, classes_attended, total_classes})
            if (!Schema::hasColumn('tutor_assessments', 'student_chips')) {
                $table->json('student_chips')->nullable()->after('total_penalty_deductions');
            }

            // Assessment date (when manager filled in the form, separate from month)
            if (!Schema::hasColumn('tutor_assessments', 'assessment_date')) {
                $table->date('assessment_date')->nullable()->after('student_chips');
            }
        });

        // Ensure student_id, week, session, class_date are nullable for new monthly assessments
        Schema::table('tutor_assessments', function (Blueprint $table) {
            if (Schema::hasColumn('tutor_assessments', 'student_id')) {
                $table->unsignedBigInteger('student_id')->nullable()->change();
            }
            if (Schema::hasColumn('tutor_assessments', 'week')) {
                $table->integer('week')->nullable()->change();
            }
            if (Schema::hasColumn('tutor_assessments', 'session')) {
                $table->string('session')->nullable()->change();
            }
            if (Schema::hasColumn('tutor_assessments', 'class_date')) {
                $table->date('class_date')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('tutor_assessments', function (Blueprint $table) {
            $columns = ['punctuality_late_count', 'video_off_count', 'punctuality_penalty', 'video_penalty', 'total_penalty_deductions', 'student_chips', 'assessment_date'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('tutor_assessments', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
