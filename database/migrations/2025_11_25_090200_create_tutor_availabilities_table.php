<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tutor_availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutor_id')->constrained('tutors')->onDelete('cascade');
            $table->enum('day', ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']);
            $table->time('start_time');
            $table->time('end_time');
            $table->string('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Composite index for tutor_id and day
            $table->index(['tutor_id', 'day']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tutor_availabilities');
    }
};
