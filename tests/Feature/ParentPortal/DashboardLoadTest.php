<?php

namespace Tests\Feature\ParentPortal;

use App\Models\User;
use App\Models\Student;
use App\Models\StudentProgress;
use App\Models\TutorReport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardLoadTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that parent dashboard loads successfully
     */
    public function test_parent_dashboard_loads_successfully(): void
    {
        // Create a parent user
        $parent = User::factory()->create([
            'role' => 'parent',
            'email' => 'parent@test.com',
        ]);

        // Create a student linked to the parent
        $student = Student::factory()->create([
            'parent_id' => $parent->id,
            'roadmap_stage' => 'Scratch Beginner',
            'roadmap_progress' => 45,
        ]);

        // Authenticate as parent
        $response = $this->actingAs($parent)->get(route('student.dashboard'));

        // Assert the response is successful
        $response->assertStatus(200);

        // Assert the view contains expected data
        $response->assertViewHas('student');
        $response->assertSee($student->first_name);
    }

    /**
     * Test that student dashboard loads successfully
     */
    public function test_student_dashboard_loads_successfully_for_student_user(): void
    {
        // Create a student user
        $studentUser = User::factory()->create([
            'role' => 'student',
            'email' => 'student@test.com',
        ]);

        // Create a student record
        $student = Student::factory()->create([
            'parent_id' => $studentUser->id,
            'roadmap_stage' => 'Python',
            'roadmap_progress' => 75,
        ]);

        // Authenticate as student
        $response = $this->actingAs($studentUser)->get(route('student.dashboard'));

        // Assert the response is successful
        $response->assertStatus(200);

        // Assert the view contains expected data
        $response->assertViewHas('student');
    }

    /**
     * Test that parent with multiple students sees student switcher
     */
    public function test_parent_with_multiple_students_sees_switcher(): void
    {
        // Create a parent user
        $parent = User::factory()->create([
            'role' => 'parent',
            'email' => 'parent@test.com',
        ]);

        // Create multiple students linked to the parent
        $student1 = Student::factory()->create([
            'parent_id' => $parent->id,
            'first_name' => 'John',
        ]);

        $student2 = Student::factory()->create([
            'parent_id' => $parent->id,
            'first_name' => 'Jane',
        ]);

        // Authenticate as parent
        $response = $this->actingAs($parent)->get(route('student.dashboard'));

        // Assert the response is successful
        $response->assertStatus(200);

        // Assert multiple students are passed to view
        $response->assertViewHas('students');
    }

    /**
     * Test that student sees only their own data
     */
    public function test_student_sees_only_their_own_data(): void
    {
        // Create student users
        $student1User = User::factory()->create([
            'role' => 'student',
            'email' => 'student1@test.com',
        ]);

        $student2User = User::factory()->create([
            'role' => 'student',
            'email' => 'student2@test.com',
        ]);

        // Create student records
        $student1 = Student::factory()->create([
            'parent_id' => $student1User->id,
            'first_name' => 'Alice',
        ]);

        $student2 = Student::factory()->create([
            'parent_id' => $student2User->id,
            'first_name' => 'Bob',
        ]);

        // Authenticate as student1
        $response = $this->actingAs($student1User)->get(route('student.dashboard'));

        // Assert the response is successful
        $response->assertStatus(200);

        // Assert only student1's data is visible
        $response->assertSee('Alice');
        $response->assertDontSee('Bob');
    }

    /**
     * Test that dashboard displays progress data correctly
     */
    public function test_dashboard_displays_progress_data(): void
    {
        // Create a parent user
        $parent = User::factory()->create([
            'role' => 'parent',
        ]);

        // Create a student
        $student = Student::factory()->create([
            'parent_id' => $parent->id,
            'roadmap_progress' => 60,
        ]);

        // Create progress items
        StudentProgress::factory()->count(3)->create([
            'student_id' => $student->id,
            'completed' => true,
        ]);

        StudentProgress::factory()->count(2)->create([
            'student_id' => $student->id,
            'completed' => false,
        ]);

        // Authenticate as parent
        $response = $this->actingAs($parent)->get(route('student.dashboard'));

        // Assert the response is successful
        $response->assertStatus(200);

        // Assert view has progress data
        $response->assertViewHas('completedMilestones');
        $response->assertViewHas('recentProgress');
    }

    /**
     * Test that unauthorized users cannot access dashboard
     */
    public function test_unauthorized_users_cannot_access_dashboard(): void
    {
        // Try to access dashboard without authentication
        $response = $this->get(route('student.dashboard'));

        // Assert redirected to login
        $response->assertRedirect(route('login'));
    }
}
