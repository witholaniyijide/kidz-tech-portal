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
        Schema::create('parent_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->constrained('users')->onDelete('cascade');
            $table->string('type')->comment('e.g., report_ready, attendance_alert, milestone_completed');
            $table->json('data')->comment('Notification payload');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            // Index for querying unread notifications by parent
            $table->index(['parent_id', 'read_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parent_notifications');
    }
};
