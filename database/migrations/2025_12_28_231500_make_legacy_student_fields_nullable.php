<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Make all optional student fields nullable to prevent
     * "Field doesn't have a default value" errors.
     */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Legacy parent fields
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

            // Student ID (auto-generated)
            if (Schema::hasColumn('students', 'student_id')) {
                $table->string('student_id')->nullable()->change();
            }

            // Date fields
            if (Schema::hasColumn('students', 'enrollment_date')) {
                $table->date('enrollment_date')->nullable()->change();
            }

            // Other potentially NOT NULL fields
            if (Schema::hasColumn('students', 'phone')) {
                $table->string('phone')->nullable()->change();
            }
            if (Schema::hasColumn('students', 'address')) {
                $table->string('address')->nullable()->change();
            }
            if (Schema::hasColumn('students', 'state')) {
                $table->string('state')->nullable()->change();
            }
            if (Schema::hasColumn('students', 'country')) {
                $table->string('country')->nullable()->change();
            }
            if (Schema::hasColumn('students', 'current_level')) {
                $table->string('current_level')->nullable()->change();
            }
            if (Schema::hasColumn('students', 'location')) {
                $table->string('location')->nullable()->change();
            }
            if (Schema::hasColumn('students', 'notes')) {
                $table->text('notes')->nullable()->change();
            }
            if (Schema::hasColumn('students', 'profile_photo')) {
                $table->string('profile_photo')->nullable()->change();
            }
            if (Schema::hasColumn('students', 'live_classroom_link')) {
                $table->string('live_classroom_link')->nullable()->change();
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
