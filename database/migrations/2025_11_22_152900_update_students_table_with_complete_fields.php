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
            // Student Info (keep existing: first_name, last_name, email, date_of_birth, gender)
            if (!Schema::hasColumn('students', 'other_name')) {
                $table->string('other_name')->nullable()->after('first_name');
            }
            if (!Schema::hasColumn('students', 'age')) {
                $table->integer('age')->nullable()->after('date_of_birth');
            }
            if (!Schema::hasColumn('students', 'coding_experience')) {
                $table->text('coding_experience')->nullable()->comment('Coding experience when joined');
            }
            if (!Schema::hasColumn('students', 'career_interest')) {
                $table->text('career_interest')->nullable();
            }

            // Class Info
            if (!Schema::hasColumn('students', 'class_link')) {
                $table->string('class_link')->nullable()->comment('Zoom/Meet link');
            }
            if (!Schema::hasColumn('students', 'google_classroom_link')) {
                $table->string('google_classroom_link')->nullable()->comment('Track course/level here');
            }
            if (!Schema::hasColumn('students', 'tutor_id')) {
                $table->foreignId('tutor_id')->nullable()->constrained('tutors')->onDelete('set null')->comment('Primary tutor');
            }
            if (!Schema::hasColumn('students', 'class_schedule')) {
                $table->json('class_schedule')->nullable()->comment('Days and times - JSON: [{day: "Monday", time: "3:00 PM"}, ...]');
            }
            if (!Schema::hasColumn('students', 'classes_per_week')) {
                $table->integer('classes_per_week')->default(1)->comment('1, 2, or 3 times per week');
            }
            if (!Schema::hasColumn('students', 'total_periods')) {
                $table->integer('total_periods')->nullable()->comment('Total periods for current month');
            }
            if (!Schema::hasColumn('students', 'completed_periods')) {
                $table->integer('completed_periods')->default(0)->comment('Completed periods this month');
            }

            // Parent Info - Father
            if (!Schema::hasColumn('students', 'father_name')) {
                $table->string('father_name')->nullable();
            }
            if (!Schema::hasColumn('students', 'father_phone')) {
                $table->string('father_phone')->nullable();
            }
            if (!Schema::hasColumn('students', 'father_email')) {
                $table->string('father_email')->nullable();
            }
            if (!Schema::hasColumn('students', 'father_occupation')) {
                $table->string('father_occupation')->nullable();
            }
            if (!Schema::hasColumn('students', 'father_location')) {
                $table->string('father_location')->nullable();
            }

            // Parent Info - Mother
            if (!Schema::hasColumn('students', 'mother_name')) {
                $table->string('mother_name')->nullable();
            }
            if (!Schema::hasColumn('students', 'mother_phone')) {
                $table->string('mother_phone')->nullable();
            }
            if (!Schema::hasColumn('students', 'mother_email')) {
                $table->string('mother_email')->nullable();
            }
            if (!Schema::hasColumn('students', 'mother_occupation')) {
                $table->string('mother_occupation')->nullable();
            }
            if (!Schema::hasColumn('students', 'mother_location')) {
                $table->string('mother_location')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $columns = [
                'other_name', 'age', 'coding_experience', 'career_interest',
                'class_link', 'google_classroom_link', 'tutor_id', 'class_schedule',
                'classes_per_week', 'total_periods', 'completed_periods',
                'father_name', 'father_phone', 'father_email', 'father_occupation', 'father_location',
                'mother_name', 'mother_phone', 'mother_email', 'mother_occupation', 'mother_location',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('students', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
