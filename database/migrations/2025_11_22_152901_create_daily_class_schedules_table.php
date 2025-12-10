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
        Schema::create('daily_class_schedules', function (Blueprint $table) {
            $table->id();
            $table->date('schedule_date');
            $table->string('day_name')->comment('e.g., Monday, Saturday');
            $table->json('classes')->comment('Array of classes for the day');
            $table->enum('status', ['draft', 'posted'])->default('draft');
            $table->foreignId('posted_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('posted_at')->nullable();
            $table->text('footer_note')->nullable()->comment('Motivational footer message');
            $table->timestamps();

            $table->unique('schedule_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_class_schedules');
    }
};
