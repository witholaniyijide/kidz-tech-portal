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
        Schema::table('tutors', function (Blueprint $table) {
            // Make fields nullable that should be optional
            $table->date('date_of_birth')->nullable()->change();
            $table->date('hire_date')->nullable()->change();
            $table->json('specializations')->nullable()->change();
            $table->enum('gender', ['male', 'female'])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tutors', function (Blueprint $table) {
            $table->date('date_of_birth')->nullable(false)->change();
            $table->date('hire_date')->nullable(false)->change();
            $table->json('specializations')->nullable(false)->change();
            $table->enum('gender', ['male', 'female'])->nullable(false)->change();
        });
    }
};
