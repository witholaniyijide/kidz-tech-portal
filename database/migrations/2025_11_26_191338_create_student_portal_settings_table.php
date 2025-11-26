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
        Schema::create('student_portal_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('timezone')->default('Africa/Lagos');
            $table->string('preferred_language')->default('en');
            $table->boolean('show_roadmap_public')->default(true)->comment('Show roadmap progress publicly');
            $table->timestamps();

            // Unique constraint to ensure one settings record per student
            $table->unique('student_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_portal_settings');
    }
};
