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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('level')->unique(); // 1-12
            $table->string('name'); // e.g., "Introduction to Computer Science"
            $table->string('full_name'); // e.g., "Level 1 - Introduction to Computer Science"
            $table->text('description')->nullable();
            $table->boolean('certificate_eligible')->default(true); // Course 1 = false
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed the 12 curriculum courses
        $courses = [
            ['level' => 1, 'name' => 'Introduction to Computer Science', 'certificate_eligible' => false],
            ['level' => 2, 'name' => 'Coding & Fundamental Concepts', 'certificate_eligible' => true],
            ['level' => 3, 'name' => 'Scratch Programming', 'certificate_eligible' => true],
            ['level' => 4, 'name' => 'Artificial Intelligence', 'certificate_eligible' => true],
            ['level' => 5, 'name' => 'Graphic Design', 'certificate_eligible' => true],
            ['level' => 6, 'name' => 'Game Development', 'certificate_eligible' => true],
            ['level' => 7, 'name' => 'Mobile App Development', 'certificate_eligible' => true],
            ['level' => 8, 'name' => 'Website Development', 'certificate_eligible' => true],
            ['level' => 9, 'name' => 'Python Programming', 'certificate_eligible' => true],
            ['level' => 10, 'name' => 'Digital Literacy & Safety/Security', 'certificate_eligible' => true],
            ['level' => 11, 'name' => 'Machine Learning', 'certificate_eligible' => true],
            ['level' => 12, 'name' => 'Robotics', 'certificate_eligible' => true],
        ];

        foreach ($courses as $course) {
            DB::table('courses')->insert([
                'level' => $course['level'],
                'name' => $course['name'],
                'full_name' => "Level {$course['level']} - {$course['name']}",
                'certificate_eligible' => $course['certificate_eligible'],
                'sort_order' => $course['level'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
