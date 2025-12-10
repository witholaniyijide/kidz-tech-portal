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
        Schema::table('parent_notifications', function (Blueprint $table) {
            // Add student_id for notifications related to specific children
            if (!Schema::hasColumn('parent_notifications', 'student_id')) {
                $table->foreignId('student_id')->nullable()->after('parent_id')
                    ->constrained('students')->onDelete('cascade');
            }

            // Add title and message for better notification display
            if (!Schema::hasColumn('parent_notifications', 'title')) {
                $table->string('title')->nullable()->after('type');
            }

            if (!Schema::hasColumn('parent_notifications', 'message')) {
                $table->text('message')->nullable()->after('title');
            }

            // Add link for actionable notifications
            if (!Schema::hasColumn('parent_notifications', 'link')) {
                $table->string('link')->nullable()->after('message');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parent_notifications', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->dropColumn(['student_id', 'title', 'message', 'link']);
        });
    }
};
