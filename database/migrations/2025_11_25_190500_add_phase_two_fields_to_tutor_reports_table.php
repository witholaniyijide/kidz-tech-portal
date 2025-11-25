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
        Schema::table('tutor_reports', function (Blueprint $table) {
            // Add new Phase 2 fields
            $table->text('progress_summary')->nullable()->after('summary');
            $table->text('strengths')->nullable()->after('progress_summary');
            $table->text('weaknesses')->nullable()->after('strengths');
            $table->text('next_steps')->nullable()->after('weaknesses');
            $table->integer('attendance_score')->nullable()->after('next_steps'); // 0-100
            $table->enum('performance_rating', ['excellent', 'good', 'average', 'poor'])->nullable()->after('attendance_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tutor_reports', function (Blueprint $table) {
            $table->dropColumn([
                'progress_summary',
                'strengths',
                'weaknesses',
                'next_steps',
                'attendance_score',
                'performance_rating',
            ]);
        });
    }
};
