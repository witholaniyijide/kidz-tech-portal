<?php

namespace Tests\Feature\ParentStudent;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Student;
use App\Models\StudentProgress;

class StudentProgressTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creating progress items for a student
     */
    public function test_can_create_progress_items_for_student(): void
    {
        $student = Student::create([
            'student_id' => 'STU-TEST-001',
            'first_name' => 'Test',
            'last_name' => 'Student',
            'email' => 'student@test.com',
            'date_of_birth' => now()->subYears(10),
            'gender' => 'male',
            'status' => 'active',
        ]);

        $progress = StudentProgress::create([
            'student_id' => $student->id,
            'title' => 'Complete Scratch Intro',
            'description' => 'Finish the introduction to Scratch',
            'milestone_code' => 'SCRATCH-001',
            'completed' => false,
            'points' => 10,
        ]);

        $this->assertDatabaseHas('student_progress', [
            'student_id' => $student->id,
            'title' => 'Complete Scratch Intro',
            'milestone_code' => 'SCRATCH-001',
        ]);

        $this->assertEquals(1, $student->progress()->count());
    }

    /**
     * Test marking progress item as complete
     */
    public function test_can_mark_progress_item_as_complete(): void
    {
        $student = Student::create([
            'student_id' => 'STU-TEST-002',
            'first_name' => 'Test',
            'last_name' => 'Student',
            'email' => 'student2@test.com',
            'date_of_birth' => now()->subYears(10),
            'gender' => 'female',
            'status' => 'active',
        ]);

        $progress = StudentProgress::create([
            'student_id' => $student->id,
            'title' => 'Complete Python Basics',
            'description' => 'Learn Python fundamentals',
            'milestone_code' => 'PYTHON-001',
            'completed' => false,
            'points' => 20,
        ]);

        // Mark as complete
        $progress->update([
            'completed' => true,
            'completed_at' => now(),
        ]);

        $this->assertTrue($progress->fresh()->completed);
        $this->assertNotNull($progress->fresh()->completed_at);
    }

    /**
     * Test student->progress() relationship returns expected count
     */
    public function test_student_progress_relationship_returns_correct_count(): void
    {
        $student = Student::create([
            'student_id' => 'STU-TEST-003',
            'first_name' => 'Test',
            'last_name' => 'Student',
            'email' => 'student3@test.com',
            'date_of_birth' => now()->subYears(10),
            'gender' => 'male',
            'status' => 'active',
        ]);

        // Create 5 progress items
        for ($i = 1; $i <= 5; $i++) {
            StudentProgress::create([
                'student_id' => $student->id,
                'title' => "Milestone {$i}",
                'description' => "Description for milestone {$i}",
                'milestone_code' => "CODE-{$i}",
                'completed' => $i <= 2, // First 2 are completed
                'completed_at' => $i <= 2 ? now() : null,
                'points' => $i * 10,
            ]);
        }

        $this->assertEquals(5, $student->progress()->count());
        $this->assertEquals(2, $student->progress()->where('completed', true)->count());
        $this->assertEquals(3, $student->progress()->where('completed', false)->count());
    }

    /**
     * Test progressPercentage helper method
     */
    public function test_progress_percentage_calculation(): void
    {
        $student = Student::create([
            'student_id' => 'STU-TEST-004',
            'first_name' => 'Test',
            'last_name' => 'Student',
            'email' => 'student4@test.com',
            'date_of_birth' => now()->subYears(10),
            'gender' => 'female',
            'status' => 'active',
            'roadmap_progress' => 50, // Explicitly set progress
        ]);

        // Test with explicit roadmap_progress value
        $this->assertEquals(50, $student->progressPercentage());

        // Create a student without explicit progress
        $student2 = Student::create([
            'student_id' => 'STU-TEST-005',
            'first_name' => 'Test2',
            'last_name' => 'Student',
            'email' => 'student5@test.com',
            'date_of_birth' => now()->subYears(10),
            'gender' => 'male',
            'status' => 'active',
        ]);

        // Create 10 progress items, 4 completed
        for ($i = 1; $i <= 10; $i++) {
            StudentProgress::create([
                'student_id' => $student2->id,
                'title' => "Milestone {$i}",
                'milestone_code' => "CODE2-{$i}",
                'completed' => $i <= 4,
                'completed_at' => $i <= 4 ? now() : null,
                'points' => 10,
            ]);
        }

        // Should calculate 40% (4 out of 10)
        $this->assertEquals(40, $student2->progressPercentage());
    }

    /**
     * Test progress with no items returns 0%
     */
    public function test_progress_percentage_with_no_items(): void
    {
        $student = Student::create([
            'student_id' => 'STU-TEST-006',
            'first_name' => 'Test',
            'last_name' => 'Student',
            'email' => 'student6@test.com',
            'date_of_birth' => now()->subYears(10),
            'gender' => 'male',
            'status' => 'active',
        ]);

        $this->assertEquals(0, $student->progressPercentage());
    }
}
