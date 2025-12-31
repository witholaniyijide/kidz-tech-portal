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
        Schema::table('tutor_assessments', function (Blueprint $table) {
            // Add student_id foreign key
            $table->foreignId('student_id')->nullable()->after('director_id')->constrained('students')->onDelete('cascade');

            // Add class date and week tracking
            $table->date('class_date')->nullable()->after('assessment_month');
            $table->integer('week')->nullable()->after('class_date');
            $table->integer('year')->nullable()->after('week');

            // Add session number (1-3 for assessments split across sessions)
            $table->integer('session')->nullable()->after('year');

            // Add JSON fields for criteria
            $table->json('criteria_assessed')->nullable()->after('session');
            $table->json('criteria_ratings')->nullable()->after('criteria_assessed');

            // Make text fields nullable since they're optional in the form
            $table->text('strengths')->nullable()->change();
            $table->text('weaknesses')->nullable()->change();
            $table->text('recommendations')->nullable()->change();
            $table->text('manager_comment')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tutor_assessments', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->dropColumn([
                'student_id',
                'class_date',
                'week',
                'year',
                'session',
                'criteria_assessed',
                'criteria_ratings',
            ]);

            // Revert text fields to NOT NULL (cannot easily revert in down)
        });
    }
};
