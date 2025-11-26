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
        Schema::create('tutor_assessments', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->foreignId('tutor_id')->constrained('tutors')->onDelete('cascade');
            $table->foreignId('manager_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('director_id')->nullable()->constrained('users')->onDelete('set null');

            // Assessment period
            $table->string('assessment_month'); // e.g., "2025-11"

            // Assessment content
            $table->text('strengths');
            $table->text('weaknesses');
            $table->text('recommendations');

            // Ratings
            $table->integer('performance_score')->default(0); // 0-100
            $table->enum('professionalism_rating', ['excellent', 'good', 'average', 'poor'])->nullable();
            $table->enum('communication_rating', ['excellent', 'good', 'average', 'poor'])->nullable();
            $table->enum('punctuality_rating', ['excellent', 'good', 'average', 'poor'])->nullable();

            // Comments
            $table->text('manager_comment');
            $table->text('director_comment')->nullable();

            // Approval timestamps
            $table->timestamp('approved_by_manager_at')->nullable();
            $table->timestamp('approved_by_director_at')->nullable();

            // Status
            $table->enum('status', ['draft', 'submitted', 'approved-by-manager', 'approved-by-director'])->default('draft');

            $table->timestamps();

            // Indexes for better query performance
            $table->index('tutor_id');
            $table->index('manager_id');
            $table->index('director_id');
            $table->index('status');
            $table->index('assessment_month');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutor_assessments');
    }
};
