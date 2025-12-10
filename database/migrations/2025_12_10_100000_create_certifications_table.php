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
        Schema::create('certifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->string('certificate_id')->unique()->comment('Unique certificate validation ID');
            $table->string('title')->comment('Certificate title, e.g., Scratch Programming Completion');
            $table->string('course_name')->nullable()->comment('Associated course name');
            $table->string('milestone_name')->nullable()->comment('Associated milestone name');
            $table->text('description')->nullable();
            $table->string('file_path')->comment('Path to uploaded certificate PDF/JPG');
            $table->string('file_type')->comment('pdf, jpg, png');
            $table->date('issue_date')->comment('Date certificate was issued');
            $table->date('expiry_date')->nullable()->comment('Optional expiry date');
            $table->enum('status', ['active', 'revoked'])->default('active');
            $table->timestamps();

            // Indexes for common queries
            $table->index(['student_id', 'status']);
            $table->index('certificate_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certifications');
    }
};
