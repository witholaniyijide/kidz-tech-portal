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
        Schema::table('daily_class_schedules', function (Blueprint $table) {
            if (!Schema::hasColumn('daily_class_schedules', 'rescheduled_classes')) {
                $table->json('rescheduled_classes')->nullable()->after('classes');
            }
            if (!Schema::hasColumn('daily_class_schedules', 'repeat_weekly')) {
                $table->boolean('repeat_weekly')->default(false)->after('footer_note');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_class_schedules', function (Blueprint $table) {
            if (Schema::hasColumn('daily_class_schedules', 'rescheduled_classes')) {
                $table->dropColumn('rescheduled_classes');
            }
            if (Schema::hasColumn('daily_class_schedules', 'repeat_weekly')) {
                $table->dropColumn('repeat_weekly');
            }
        });
    }
};
