<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds 'returned_by' column to track who returned the report (manager or director).
     * This helps distinguish between reports returned at different approval stages.
     */
    public function up(): void
    {
        Schema::table('tutor_reports', function (Blueprint $table) {
            if (!Schema::hasColumn('tutor_reports', 'returned_by')) {
                $table->string('returned_by')->nullable()->after('returned_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tutor_reports', function (Blueprint $table) {
            if (Schema::hasColumn('tutor_reports', 'returned_by')) {
                $table->dropColumn('returned_by');
            }
        });
    }
};
