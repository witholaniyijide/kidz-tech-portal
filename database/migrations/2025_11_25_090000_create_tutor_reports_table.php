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
            $table->foreignId('tutor_id')->constrained('tutors')->onDelete('cascade');
            $table->foreignId('student_id')->nullable()->constrained('students')->onDelete('set null');
            $table->string('title');
            $table->string('month'); // e.g., "November 2025"
            $table->date('period_from')->nullable();
            $table->date('period_to')->nullable();
            $table->text('content'); // detailed report
            $table->text('summary')->nullable();
            $table->integer('rating')->nullable(); // 1-10
            $table->enum('status', ['draft', 'submitted', 'manager_review', 'director_approved', 'rejected'])->default('draft');
            $table->timestamp('submitted_at')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            // Indexes for better query performance
            $table->index('tutor_id');
            $table->index('student_id');
            $table->index('status');
            $table->index('created_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tutor_reports');
    }
};
