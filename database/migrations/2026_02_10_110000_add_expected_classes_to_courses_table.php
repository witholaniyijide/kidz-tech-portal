<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            if (!Schema::hasColumn('courses', 'expected_classes')) {
                $table->unsignedInteger('expected_classes')->default(8)->after('description');
            }
        });

        // Update expected classes for each course based on curriculum
        $courseClasses = [
            1 => 6,   // Introduction to Computer Science
            2 => 8,   // Introduction to Coding & Fundamental Concepts
            3 => 48,  // Introduction to Scratch Programming
            4 => 13,  // Introduction to Artificial Intelligence
            5 => 8,   // Introduction to Graphics Design
            6 => 16,  // Game Development (Game Maker & Roblox)
            7 => 32,  // Mobile App Development
            8 => 32,  // Website Development
            9 => 8,   // Python Programming
            10 => 16, // Digital Literacy & Safety
            11 => 8,  // Machine Learning
            12 => 8,  // Robotics
        ];

        foreach ($courseClasses as $level => $expectedClasses) {
            DB::table('courses')
                ->where('level', $level)
                ->update(['expected_classes' => $expectedClasses]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            if (Schema::hasColumn('courses', 'expected_classes')) {
                $table->dropColumn('expected_classes');
            }
        });
    }
};
