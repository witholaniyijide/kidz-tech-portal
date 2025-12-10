<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\StudentProgress;

class StudentProgressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all students from the database
        $students = Student::all();

        if ($students->isEmpty()) {
            $this->command->warn('⚠ No students found. Please run ParentPortalSeeder first.');
            return;
        }

        // Sample progress items for each student
        $progressTemplates = [
            [
                'title' => 'Introduction to Scratch',
                'description' => 'Complete the introduction module and create first project',
                'milestone_code' => 'SCRATCH-INTRO-001',
                'completed' => true,
                'points' => 10,
            ],
            [
                'title' => 'Build a Simple Animation',
                'description' => 'Create an animation with at least 3 sprites and 2 backgrounds',
                'milestone_code' => 'SCRATCH-ANIM-001',
                'completed' => true,
                'points' => 20,
            ],
            [
                'title' => 'Create Interactive Story',
                'description' => 'Build an interactive story with user choices',
                'milestone_code' => 'SCRATCH-STORY-001',
                'completed' => false,
                'points' => 30,
            ],
            [
                'title' => 'Game Development Basics',
                'description' => 'Learn game loops, scoring, and collision detection',
                'milestone_code' => 'SCRATCH-GAME-001',
                'completed' => false,
                'points' => 40,
            ],
            [
                'title' => 'Final Project Showcase',
                'description' => 'Create and present a complete game or animation project',
                'milestone_code' => 'SCRATCH-FINAL-001',
                'completed' => false,
                'points' => 50,
            ],
        ];

        $count = 0;

        foreach ($students as $student) {
            foreach ($progressTemplates as $template) {
                // Check if progress item already exists
                $exists = StudentProgress::where('student_id', $student->id)
                    ->where('milestone_code', $template['milestone_code'])
                    ->exists();

                if (!$exists) {
                    StudentProgress::create([
                        'student_id' => $student->id,
                        'title' => $template['title'],
                        'description' => $template['description'],
                        'milestone_code' => $template['milestone_code'],
                        'completed' => $template['completed'],
                        'completed_at' => $template['completed'] ? now()->subDays(rand(1, 30)) : null,
                        'points' => $template['points'],
                    ]);
                    $count++;
                }
            }
        }

        $this->command->info("✓ Student progress seeder completed: Created {$count} progress items for {$students->count()} students");
    }
}
