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
            // Starting course - immutable once set
            $table->foreignId('starting_course_id')
                ->nullable()
                ->after('starting_course_level')
                ->constrained('courses')
                ->nullOnDelete();

            // Current course - can be changed or set to null
            $table->foreignId('current_course_id')
                ->nullable()
                ->after('starting_course_id')
                ->constrained('courses')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['starting_course_id']);
            $table->dropForeign(['current_course_id']);
            $table->dropColumn(['starting_course_id', 'current_course_id']);
        });
    }
};
