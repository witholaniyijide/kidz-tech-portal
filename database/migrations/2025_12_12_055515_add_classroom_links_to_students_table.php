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
            if (!Schema::hasColumn('students', 'live_classroom_link')) {
                $table->string('live_classroom_link')->nullable()->after('google_classroom_link');
            }
            if (!Schema::hasColumn('students', 'current_level')) {
                $table->string('current_level')->nullable()->after('live_classroom_link');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['live_classroom_link', 'current_level']);
        });
    }
};
