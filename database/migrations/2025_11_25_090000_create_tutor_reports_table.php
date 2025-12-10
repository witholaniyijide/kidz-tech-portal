<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tutor_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('tutor_id')->constrained('tutors')->onDelete('cascade');
            $table->string('month'); // e.g., "2025-01"
            $table->longText('progress_summary');
            $table->longText('strengths');
            $table->longText('weaknesses');
            $table->longText('next_steps');
            $table->integer('attendance_score')->default(0); // 0-100
            $table->enum('performance_rating', ['excellent', 'good', 'average', 'poor'])->nullable();
            $table->enum('status', ['draft', 'submitted', 'approved-by-manager', 'approved-by-director'])->default('draft');
            $table->text('manager_comment')->nullable();
            $table->text('director_comment')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_by_manager_at')->nullable();
            $table->timestamp('approved_by_director_at')->nullable();
            $table->timestamps();

            // Indexes for better query performance
            $table->index('tutor_id');
            $table->index('student_id');
            $table->index('status');
            $table->index('month');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tutor_reports');
    }
};
