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
        Schema::table('payments', function (Blueprint $table) {
            // Add type column for income/expense tracking
            $table->enum('type', ['income', 'expense'])->default('income')->after('payment_type');

            // Add description for transaction details
            $table->text('description')->nullable()->after('notes');

            // Add category for categorizing transactions
            $table->string('category')->nullable()->after('description');

            // Make student_id nullable for expenses (which don't have students)
            $table->foreignId('student_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['type', 'description', 'category']);
        });
    }
};
