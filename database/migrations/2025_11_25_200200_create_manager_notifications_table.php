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
        Schema::create('manager_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('body');
            $table->enum('type', ['info', 'alert', 'report', 'attendance', 'system'])->default('info');
            $table->boolean('is_read')->default(false);
            $table->json('meta')->nullable(); // optional extra data
            $table->timestamps();

            // Indexes for better query performance
            $table->index('user_id');
            $table->index('is_read');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manager_notifications');
    }
};
