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
            // Add director_comment if it doesn't exist
            if (!Schema::hasColumn('tutor_reports', 'director_comment')) {
                $table->text('director_comment')->nullable()->after('manager_comment');
            }

            // Add director_reviewed_at timestamp
            if (!Schema::hasColumn('tutor_reports', 'director_reviewed_at')) {
                $table->timestamp('director_reviewed_at')->nullable()->after('approved_by_director_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tutor_reports', function (Blueprint $table) {
            if (Schema::hasColumn('tutor_reports', 'director_reviewed_at')) {
                $table->dropColumn('director_reviewed_at');
            }
        });
    }
};
