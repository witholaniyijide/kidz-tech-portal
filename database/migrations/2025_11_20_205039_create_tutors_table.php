<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tutors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('tutor_id')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female']);
            $table->text('address')->nullable();
            $table->string('state')->nullable();
            $table->string('location')->nullable();
            $table->json('specializations');
            $table->date('hire_date');
            $table->enum('status', ['active', 'inactive', 'on_leave'])->default('active');
            $table->decimal('hourly_rate', 10, 2)->nullable();
            $table->text('qualifications')->nullable();
            $table->text('notes')->nullable();
            $table->string('profile_photo')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tutors');
    }
};
