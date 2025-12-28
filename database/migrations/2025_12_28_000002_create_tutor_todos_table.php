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
        Schema::create('tutor_todos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutor_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->boolean('completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->date('due_date')->nullable();
            $table->timestamps();

            $table->index(['tutor_id', 'completed']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutor_todos');
    }
};
