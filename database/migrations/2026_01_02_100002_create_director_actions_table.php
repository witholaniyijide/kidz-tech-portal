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
        Schema::create('director_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained('tutor_assessments')->onDelete('cascade');
            $table->foreignId('director_id')->constrained('users')->onDelete('cascade');
            $table->enum('action_type', ['approve', 'approve_no_penalty'])->default('approve');
            $table->decimal('penalty_amount', 10, 2)->default(0.00);
            $table->decimal('suggested_penalty', 10, 2)->default(0.00);
            $table->text('director_comment')->nullable();
            $table->timestamp('action_date')->useCurrent();
            $table->timestamps();

            $table->index(['assessment_id', 'director_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('director_actions');
    }
};
