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
        Schema::create('monthly_class_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutor_id')->constrained('tutors')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->integer('year');
            $table->integer('month');
            $table->integer('total_classes')->default(0); // Total expected classes for the month
            $table->integer('completed_classes')->default(0); // Classes completed (approved attendance)
            $table->json('class_days')->nullable(); // Days of the week for classes e.g., ["Monday", "Wednesday"]
            $table->text('notes')->nullable();
            $table->timestamps();

            // Unique constraint: one record per student per month
            $table->unique(['student_id', 'year', 'month']);

            // Index for quick tutor lookups
            $table->index(['tutor_id', 'year', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_class_schedules');
    }
};
