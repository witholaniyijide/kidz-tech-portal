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
        Schema::table('tutor_reports', function (Blueprint $table) {
            // Title and year
            if (!Schema::hasColumn('tutor_reports', 'title')) {
                $table->string('title')->nullable()->after('director_id');
            }
            if (!Schema::hasColumn('tutor_reports', 'year')) {
                $table->string('year', 4)->nullable()->after('month');
            }
            if (!Schema::hasColumn('tutor_reports', 'created_by')) {
                $table->foreignId('created_by')->nullable()->after('director_id');
            }
            
            // Courses - JSON array of course names
            if (!Schema::hasColumn('tutor_reports', 'courses')) {
                $table->json('courses')->nullable()->after('period_to');
            }
            
            // Skills - JSON arrays
            if (!Schema::hasColumn('tutor_reports', 'skills_mastered')) {
                $table->json('skills_mastered')->nullable()->after('courses');
            }
            if (!Schema::hasColumn('tutor_reports', 'new_skills')) {
                $table->json('new_skills')->nullable()->after('skills_mastered');
            }
            
            // Projects - JSON array of objects [{title, link}]
            if (!Schema::hasColumn('tutor_reports', 'projects')) {
                $table->json('projects')->nullable()->after('new_skills');
            }
            
            // MVP Report content sections
            if (!Schema::hasColumn('tutor_reports', 'areas_for_improvement')) {
                $table->text('areas_for_improvement')->nullable()->after('weaknesses');
            }
            if (!Schema::hasColumn('tutor_reports', 'goals_next_month')) {
                $table->text('goals_next_month')->nullable()->after('areas_for_improvement');
            }
            if (!Schema::hasColumn('tutor_reports', 'assignments')) {
                $table->text('assignments')->nullable()->after('goals_next_month');
            }
            if (!Schema::hasColumn('tutor_reports', 'comments_observation')) {
                $table->text('comments_observation')->nullable()->after('assignments');
            }
            
            // Import from Claude Artifact metadata
            if (!Schema::hasColumn('tutor_reports', 'imported_from_artifact')) {
                $table->boolean('imported_from_artifact')->default(false)->after('approved_by_director_at');
            }
            if (!Schema::hasColumn('tutor_reports', 'artifact_export_date')) {
                $table->timestamp('artifact_export_date')->nullable()->after('imported_from_artifact');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tutor_reports', function (Blueprint $table) {
            $columns = [
                'title', 'year', 'created_by', 'courses', 'skills_mastered', 
                'new_skills', 'projects', 'areas_for_improvement', 
                'goals_next_month', 'assignments', 'comments_observation',
                'imported_from_artifact', 'artifact_export_date'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('tutor_reports', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
