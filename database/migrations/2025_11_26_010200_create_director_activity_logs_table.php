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
        Schema::create('director_activity_logs', function (Blueprint $table) {
            $table->id();

            // Director who performed the action
            $table->foreignId('director_id')->constrained('users')->onDelete('cascade');

            // Action details
            $table->string('action_type'); // e.g., 'approved_report', 'rejected_report', 'approved_assessment'

            // Polymorphic relationship to track what was acted upon
            $table->string('model_type')->nullable(); // e.g., 'App\Models\TutorReport', 'App\Models\TutorAssessment'
            $table->unsignedBigInteger('model_id')->nullable();

            // Request metadata
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamp('created_at')->useCurrent();

            // Indexes for better query performance
            $table->index('director_id');
            $table->index('action_type');
            $table->index(['model_type', 'model_id']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('director_activity_logs');
    }
};
