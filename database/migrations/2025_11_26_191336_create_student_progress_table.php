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
        Schema::create('student_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('milestone_code')->nullable()->comment('Reference to curriculum item');
            $table->boolean('completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->integer('points')->default(0);
            $table->timestamps();

            // Index for querying progress by student and completion status
            $table->index(['student_id', 'completed']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_progress');
    }
};
