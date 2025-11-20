<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('instructor_id')->constrained('users')->onDelete('cascade');
            $table->string('month');
            $table->string('year');
            $table->json('courses');
            $table->json('skills_mastered');
            $table->json('skills_new')->nullable();
            $table->json('projects');
            $table->text('improvement');
            $table->text('goals');
            $table->text('assignments');
            $table->text('comments');
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            
            $table->unique(['student_id', 'month', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
