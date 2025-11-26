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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('action'); // e.g., 'report.approve.director', 'report.reject.director'
            $table->string('auditable_type'); // e.g., App\Models\TutorReport
            $table->unsignedBigInteger('auditable_id');
            $table->json('meta')->nullable(); // Store comment, previous status, etc.
            $table->timestamps();

            // Indexes for better query performance
            $table->index('user_id');
            $table->index(['auditable_type', 'auditable_id']);
            $table->index('action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
