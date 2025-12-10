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
            if (!Schema::hasColumn('tutor_availabilities', 'type')) {
                $table->enum('type', ['available', 'unavailable'])->default('available');
            }

            // For date-specific overrides (null means weekly recurring)
            if (!Schema::hasColumn('tutor_availabilities', 'specific_date')) {
                $table->date('specific_date')->nullable();
            }

            // Timezone for the tutor
            if (!Schema::hasColumn('tutor_availabilities', 'timezone')) {
                $table->string('timezone')->default('Africa/Lagos');
            }

            // Google Calendar integration
            if (!Schema::hasColumn('tutor_availabilities', 'google_calendar_id')) {
                $table->string('google_calendar_id')->nullable();
            }
        });

        // Also add timezone to tutors table if not exists
        if (!Schema::hasColumn('tutors', 'timezone')) {
            Schema::table('tutors', function (Blueprint $table) {
                $table->string('timezone')->default('Africa/Lagos');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tutor_availabilities', function (Blueprint $table) {
            $columns = ['type', 'specific_date', 'timezone', 'google_calendar_id'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('tutor_availabilities', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        if (Schema::hasColumn('tutors', 'timezone')) {
            Schema::table('tutors', function (Blueprint $table) {
                $table->dropColumn('timezone');
            });
        }
    }
};
