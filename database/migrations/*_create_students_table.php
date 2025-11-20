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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_id')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female']);
            $table->text('address')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->default('Nigeria');
            
            $table->string('parent_name');
            $table->string('parent_email')->nullable();
            $table->string('parent_phone');
            $table->string('parent_relationship')->default('Parent');
            
            $table->date('enrollment_date');
            $table->enum('status', ['active', 'inactive', 'graduated', 'withdrawn'])->default('active');
            $table->string('location')->nullable();
            
            $table->text('notes')->nullable();
            $table->string('profile_photo')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
