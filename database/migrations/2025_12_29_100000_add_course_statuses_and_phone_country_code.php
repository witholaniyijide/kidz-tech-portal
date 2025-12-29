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
        // Add course_statuses JSON field to students table for tracking course progress
        Schema::table('students', function (Blueprint $table) {
            $table->json('course_statuses')->nullable()->after('starting_course_level');
            $table->boolean('class_reminder_enabled')->default(false)->after('allow_parent_notifications');
            $table->integer('class_reminder_minutes')->default(30)->after('class_reminder_enabled');
        });

        // Add phone_country_code field to users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_country_code', 10)->default('+234')->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['course_statuses', 'class_reminder_enabled', 'class_reminder_minutes']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone_country_code');
        });
    }
};
