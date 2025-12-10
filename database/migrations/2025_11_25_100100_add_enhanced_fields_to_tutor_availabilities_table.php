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
        Schema::table('tutor_availabilities', function (Blueprint $table) {
            // Type of availability slot
            $table->enum('type', ['available', 'unavailable'])->default('available')->after('day');
            
            // For date-specific overrides (null means weekly recurring)
            $table->date('specific_date')->nullable()->after('end_time');
            
            // Timezone for the tutor
            $table->string('timezone')->default('Africa/Lagos')->after('specific_date');
            
            // Google Calendar integration
            $table->string('google_calendar_id')->nullable()->after('timezone');
            
            // Index for faster queries
            $table->index(['tutor_id', 'day', 'specific_date']);
        });

        // Also add timezone to tutors table if not exists
        if (!Schema::hasColumn('tutors', 'timezone')) {
            Schema::table('tutors', function (Blueprint $table) {
                $table->string('timezone')->default('Africa/Lagos')->after('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tutor_availabilities', function (Blueprint $table) {
            $table->dropIndex(['tutor_id', 'day', 'specific_date']);
            $table->dropColumn(['type', 'specific_date', 'timezone', 'google_calendar_id']);
        });

        if (Schema::hasColumn('tutors', 'timezone')) {
            Schema::table('tutors', function (Blueprint $table) {
                $table->dropColumn('timezone');
            });
        }
    }
};
