<?php

namespace Tests\Feature\StudentPortal;

use App\Models\Role;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $studentUser;
    protected Student $student;
    protected Tutor $tutor;
    protected Role $studentRole;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        $this->studentRole = Role::create(['name' => 'student']);

        // Create a tutor
        $this->tutor = Tutor::factory()->create([
            'name' => 'Test Tutor',
            'email' => 'tutor@example.com',
            'phone' => '08012345678',
        ]);

        // Create a student user
        $this->studentUser = User::factory()->create([
            'name' => 'Test Student',
            'email' => 'student@example.com',
        ]);

        $this->studentUser->roles()->attach($this->studentRole);

        // Create a student record linked to the user by email
        $this->student = Student::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'Student',
            'email' => 'student@example.com',
            'tutor_id' => $this->tutor->id,
            'enrollment_date' => now()->subMonths(3),
            'roadmap_stage' => 'Intermediate',
            'roadmap_progress' => 65,
            'completed_periods' => 10,
            'total_periods' => 20,
        ]);
    }

    /** @test */
    public function student_can_view_settings_page()
    {
        $response = $this->actingAs($this->studentUser)
            ->get(route('student.settings.index'));

        $response->assertOk();
        $response->assertViewIs('student.settings.index');
        $response->assertViewHas('student');
        $response->assertViewHas('progressPercentage', 65);
    }

    /** @test */
    public function student_settings_page_displays_correct_student_data()
    {
        $response = $this->actingAs($this->studentUser)
            ->get(route('student.settings.index'));

        $response->assertOk();
        $response->assertSee('Test Student'); // Full name
        $response->assertSee('student@example.com'); // Email
        $response->assertSee('Intermediate'); // Roadmap stage
        $response->assertSee('Test Tutor'); // Tutor name
    }

    /** @test */
    public function student_cannot_modify_settings()
    {
        // Student settings are read-only, so there should be no POST/PUT/PATCH routes
        // Try to access a non-existent update route
        $response = $this->actingAs($this->studentUser)
            ->put('/student/settings', [
                'name' => 'Updated Name',
            ]);

        $response->assertStatus(405); // Method Not Allowed
    }

    /** @test */
    public function student_can_only_view_their_own_settings()
    {
        // Create another student
        $otherStudentUser = User::factory()->create([
            'email' => 'otherstudent@example.com',
        ]);
        $otherStudentUser->roles()->attach($this->studentRole);

        $otherStudent = Student::factory()->create([
            'email' => 'otherstudent@example.com',
            'tutor_id' => $this->tutor->id,
        ]);

        // First student tries to access settings (should see their own data)
        $response = $this->actingAs($this->studentUser)
            ->get(route('student.settings.index'));

        $response->assertOk();
        $response->assertSee('Test Student');
        $response->assertDontSee('otherstudent@example.com');
    }

    /** @test */
    public function student_settings_show_tutor_information()
    {
        $response = $this->actingAs($this->studentUser)
            ->get(route('student.settings.index'));

        $response->assertOk();
        $response->assertSee('Test Tutor');
        $response->assertSee('tutor@example.com');
        $response->assertSee('08012345678');
    }

    /** @test */
    public function student_settings_show_progress_information()
    {
        $response = $this->actingAs($this->studentUser)
            ->get(route('student.settings.index'));

        $response->assertOk();
        $response->assertSee('65%'); // Progress percentage
        $response->assertSee('10 / 20 classes'); // Completed periods
        $response->assertSee('Intermediate'); // Roadmap stage
    }

    /** @test */
    public function non_student_cannot_access_student_settings()
    {
        $parentRole = Role::create(['name' => 'parent']);
        $parent = User::factory()->create();
        $parent->roles()->attach($parentRole);

        $response = $this->actingAs($parent)
            ->get(route('student.settings.index'));

        $response->assertStatus(403);
    }

    /** @test */
    public function unauthenticated_user_cannot_access_student_settings()
    {
        $response = $this->get(route('student.settings.index'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function student_without_student_record_gets_404()
    {
        // Create a student user without a corresponding student record
        $orphanUser = User::factory()->create([
            'email' => 'orphan@example.com',
        ]);
        $orphanUser->roles()->attach($this->studentRole);

        $response = $this->actingAs($orphanUser)
            ->get(route('student.settings.index'));

        $response->assertStatus(404);
    }
}
