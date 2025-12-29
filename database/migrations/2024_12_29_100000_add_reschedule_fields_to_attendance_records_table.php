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
        Schema::table('attendance_records', function (Blueprint $table) {
            $table->boolean('is_rescheduled')->default(false)->after('is_late');
            $table->time('original_scheduled_time')->nullable()->after('is_rescheduled');
            $table->string('reschedule_reason')->nullable()->after('original_scheduled_time');
            $table->string('reschedule_notes')->nullable()->after('reschedule_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance_records', function (Blueprint $table) {
            $table->dropColumn([
                'is_rescheduled',
                'original_scheduled_time',
                'reschedule_reason',
                'reschedule_notes'
            ]);
        });
    }
};
