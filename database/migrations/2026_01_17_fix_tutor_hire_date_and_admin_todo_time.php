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
        // Fix 1: Make hire_date nullable with default value
        Schema::table('tutors', function (Blueprint $table) {
            $table->date('hire_date')->nullable()->default(now()->toDateString())->change();
        });

        // Fix 2: Add time field to admin_todos
        Schema::table('admin_todos', function (Blueprint $table) {
            if (!Schema::hasColumn('admin_todos', 'due_time')) {
                $table->time('due_time')->nullable()->after('due_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tutors', function (Blueprint $table) {
            $table->date('hire_date')->nullable(false)->default(null)->change();
        });

        Schema::table('admin_todos', function (Blueprint $table) {
            if (Schema::hasColumn('admin_todos', 'due_time')) {
                $table->dropColumn('due_time');
            }
        });
    }
};
