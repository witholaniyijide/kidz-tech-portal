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
        Schema::table('tutor_todos', function (Blueprint $table) {
            if (!Schema::hasColumn('tutor_todos', 'due_time')) {
                $table->time('due_time')->nullable()->after('due_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tutor_todos', function (Blueprint $table) {
            if (Schema::hasColumn('tutor_todos', 'due_time')) {
                $table->dropColumn('due_time');
            }
        });
    }
};
