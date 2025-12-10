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
            // Stand-in attendance fields
            if (!Schema::hasColumn('attendance_records', 'is_stand_in')) {
                $table->boolean('is_stand_in')->default(false)->after('status');
            }
            if (!Schema::hasColumn('attendance_records', 'stand_in_reason')) {
                $table->string('stand_in_reason')->nullable();
            }

            // Late submission tracking
            if (!Schema::hasColumn('attendance_records', 'is_late')) {
                $table->boolean('is_late')->default(false);
            }

            // Rejection reason for declined attendance
            if (!Schema::hasColumn('attendance_records', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance_records', function (Blueprint $table) {
            $columns = ['is_stand_in', 'stand_in_reason', 'is_late', 'rejection_reason'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('attendance_records', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
