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
            // Roadmap tracking fields
            if (!Schema::hasColumn('students', 'roadmap_stage')) {
                $table->string('roadmap_stage')->nullable()->comment('Current curriculum stage slug');
                $table->index('roadmap_stage');
            }
            if (!Schema::hasColumn('students', 'roadmap_progress')) {
                $table->integer('roadmap_progress')->default(0)->comment('Progress percentage 0-100');
            }
            if (!Schema::hasColumn('students', 'roadmap_next_milestone')) {
                $table->string('roadmap_next_milestone')->nullable()->comment('Next milestone to reach');
            }
            if (!Schema::hasColumn('students', 'learning_notes')) {
                $table->text('learning_notes')->nullable()->comment('Tutor notes visible to parents');
            }

            // Parent notification preferences
            if (!Schema::hasColumn('students', 'allow_parent_notifications')) {
                $table->boolean('allow_parent_notifications')->default(true);
            }
            if (!Schema::hasColumn('students', 'preferred_contact_method')) {
                $table->enum('preferred_contact_method', ['email', 'sms', 'none'])->default('email');
            }
            if (!Schema::hasColumn('students', 'visible_to_parent')) {
                $table->boolean('visible_to_parent')->default(true)->comment('Show sensitive internal notes to parent');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $columns = [
                'roadmap_stage',
                'roadmap_progress',
                'roadmap_next_milestone',
                'learning_notes',
                'allow_parent_notifications',
                'preferred_contact_method',
                'visible_to_parent',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('students', $column)) {
                    $table->dropColumn($column);
                }
            }

            // Drop index if it exists
            if (Schema::hasColumn('students', 'roadmap_stage')) {
                $table->dropIndex(['roadmap_stage']);
            }
        });
    }
};
