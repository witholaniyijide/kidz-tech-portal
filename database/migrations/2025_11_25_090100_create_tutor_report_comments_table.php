<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tutor_report_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('tutor_reports')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('comment');
            $table->string('role')->nullable(); // 'tutor', 'manager', 'director'
            $table->timestamps();

            // Indexes for better query performance
            $table->index('report_id');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tutor_report_comments');
    }
};
