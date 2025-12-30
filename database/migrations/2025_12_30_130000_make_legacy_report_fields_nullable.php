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
            // Make legacy fields nullable since the new form doesn't use them
            if (Schema::hasColumn('tutor_reports', 'content')) {
                $table->text('content')->nullable()->change();
            }
            if (Schema::hasColumn('tutor_reports', 'progress_summary')) {
                $table->longText('progress_summary')->nullable()->change();
            }
            if (Schema::hasColumn('tutor_reports', 'strengths')) {
                $table->longText('strengths')->nullable()->change();
            }
            if (Schema::hasColumn('tutor_reports', 'weaknesses')) {
                $table->longText('weaknesses')->nullable()->change();
            }
            if (Schema::hasColumn('tutor_reports', 'next_steps')) {
                $table->longText('next_steps')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't revert - these should stay nullable
    }
};
