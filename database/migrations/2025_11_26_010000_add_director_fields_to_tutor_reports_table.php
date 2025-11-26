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
        Schema::table('tutor_reports', function (Blueprint $table) {
            // Add director_id foreign key
            $table->foreignId('director_id')->nullable()->after('tutor_id')->constrained('users')->onDelete('set null');

            // Add director_signature field after approved_by_director_at
            $table->string('director_signature')->nullable()->after('approved_by_director_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tutor_reports', function (Blueprint $table) {
            // Drop foreign key and index first
            $table->dropForeign(['director_id']);
            $table->dropIndex(['director_id']);

            // Drop columns
            $table->dropColumn(['director_id', 'director_signature']);
        });
    }
};
