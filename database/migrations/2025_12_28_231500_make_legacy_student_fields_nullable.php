<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Make legacy fields nullable since the new student forms use
     * father/mother fields instead of generic parent fields.
     */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Make legacy parent fields nullable if they exist
            if (Schema::hasColumn('students', 'parent_name')) {
                $table->string('parent_name')->nullable()->change();
            }
            if (Schema::hasColumn('students', 'parent_email')) {
                $table->string('parent_email')->nullable()->change();
            }
            if (Schema::hasColumn('students', 'parent_phone')) {
                $table->string('parent_phone')->nullable()->change();
            }
            if (Schema::hasColumn('students', 'parent_relationship')) {
                $table->string('parent_relationship')->nullable()->change();
            }
            
            // Make student_id nullable with default (will be auto-generated)
            if (Schema::hasColumn('students', 'student_id')) {
                $table->string('student_id')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse - fields can remain nullable
    }
};
