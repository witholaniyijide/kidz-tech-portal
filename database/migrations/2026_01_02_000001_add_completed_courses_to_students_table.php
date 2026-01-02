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
        Schema::table('students', function (Blueprint $table) {
            // JSON array of completed course IDs [1, 2, 3, 5, 7] etc
            // Allows non-sequential completion (e.g., completed 1,2,3,5 but not 4)
            $table->json('completed_courses')->nullable()->after('starting_course_level');

            // Current course level can be text like "Scratch Level 3" or numeric like "5"
            // This field already exists but adding comment for clarity
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('completed_courses');
        });
    }
};
