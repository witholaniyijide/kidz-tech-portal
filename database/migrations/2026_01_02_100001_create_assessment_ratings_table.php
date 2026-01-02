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
        Schema::create('assessment_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained('tutor_assessments')->onDelete('cascade');
            $table->foreignId('criteria_id')->constrained('assessment_criteria')->onDelete('cascade');
            $table->string('rating', 50); // e.g., 'Excellent', 'Good', 'Needs Improvement'
            $table->integer('score'); // 0-4 scale
            $table->timestamps();

            // Each assessment can have only one rating per criteria
            $table->unique(['assessment_id', 'criteria_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_ratings');
    }
};
