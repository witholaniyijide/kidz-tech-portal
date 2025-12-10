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
            $table->boolean('is_stand_in')->default(false)->after('status');
            $table->string('stand_in_reason')->nullable()->after('is_stand_in');
            
            // Late submission tracking
            $table->boolean('is_late')->default(false)->after('stand_in_reason');
            
            // Rejection reason for declined attendance
            $table->text('rejection_reason')->nullable()->after('approved_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance_records', function (Blueprint $table) {
            $table->dropColumn(['is_stand_in', 'stand_in_reason', 'is_late', 'rejection_reason']);
        });
    }
};
