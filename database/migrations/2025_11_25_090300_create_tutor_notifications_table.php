<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tutor_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutor_id')->constrained('tutors')->onDelete('cascade');
            $table->string('title');
            $table->text('body');
            $table->enum('type', ['info', 'alert', 'schedule', 'payment', 'system'])->default('info');
            $table->boolean('is_read')->default(false);
            $table->json('meta')->nullable(); // optional extra data
            $table->timestamps();

            // Indexes for better query performance
            $table->index('tutor_id');
            $table->index('is_read');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tutor_notifications');
    }
};
