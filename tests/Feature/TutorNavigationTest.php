<?php

namespace Tests\Feature;

use App\Models\Tutor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TutorNavigationTest extends TestCase
{
    use RefreshDatabase;

    protected User $tutorUser;
    protected Tutor $tutor;

    protected function setUp(): void
    {
        parent::setUp();

        // Create tutor user
        $this->tutorUser = User::factory()->create();
        $this->tutorUser->assignRole('tutor');
        $this->tutor = Tutor::factory()->create(['email' => $this->tutorUser->email]);
    }

    /** @test */
    public function tutor_can_access_dashboard()
    {
        $response = $this->actingAs($this->tutorUser)->get(route('tutor.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('tutor.dashboard');
    }

    /** @test */
    public function tutor_can_access_reports_index()
    {
        $response = $this->actingAs($this->tutorUser)->get(route('tutor.reports.index'));

        $response->assertStatus(200);
        $response->assertViewIs('tutor.reports.index');
    }

    /** @test */
    public function tutor_can_access_reports_create_page()
    {
        $response = $this->actingAs($this->tutorUser)->get(route('tutor.reports.create'));

        $response->assertStatus(200);
        $response->assertViewIs('tutor.reports.create');
    }

    /** @test */
    public function tutor_can_access_students_index()
    {
        $response = $this->actingAs($this->tutorUser)->get(route('tutor.students.index'));

        $response->assertStatus(200);
        $response->assertViewIs('tutor.students.index');
    }

    /** @test */
    public function tutor_can_access_attendance_create_page()
    {
        $response = $this->actingAs($this->tutorUser)->get(route('tutor.attendance.create'));

        $response->assertStatus(200);
        $response->assertViewIs('tutor.attendance.create');
    }

    /** @test */
    public function tutor_can_access_schedule_today_page()
    {
        $response = $this->actingAs($this->tutorUser)->get(route('tutor.schedule.today'));

        $response->assertStatus(200);
        $response->assertViewIs('tutor.schedule.today');
    }

    /** @test */
    public function tutor_can_access_profile_edit_page()
    {
        $response = $this->actingAs($this->tutorUser)->get(route('tutor.profile.edit'));

        $response->assertStatus(200);
        $response->assertViewIs('tutor.profile.edit');
    }

    /** @test */
    public function all_tutor_navigation_links_are_accessible()
    {
        $routes = [
            'tutor.dashboard',
            'tutor.students.index',
            'tutor.reports.index',
            'tutor.reports.create',
            'tutor.attendance.create',
            'tutor.schedule.today',
            'tutor.profile.edit',
        ];

        foreach ($routes as $routeName) {
            $response = $this->actingAs($this->tutorUser)->get(route($routeName));
            $response->assertStatus(200, "Failed to access route: {$routeName}");
        }
    }

    /** @test */
    public function tutor_navigation_sidebar_contains_correct_links()
    {
        $response = $this->actingAs($this->tutorUser)->get(route('tutor.dashboard'));

        $response->assertStatus(200);

        // Check that navigation contains expected text/links
        $response->assertSee('Dashboard');
        $response->assertSee('My Students');
        $response->assertSee('My Reports');
        $response->assertSee('Create Report');
        $response->assertSee('Submit Attendance');
        $response->assertSee('Today\'s Schedule');
        $response->assertSee('Profile Settings');
    }

    /** @test */
    public function tutor_navigation_does_not_contain_admin_links()
    {
        $response = $this->actingAs($this->tutorUser)->get(route('tutor.dashboard'));

        $response->assertStatus(200);

        // Ensure admin/manager links are not present
        $response->assertDontSee('Manage Tutors');
        $response->assertDontSee('Manage Students');
        $response->assertDontSee('Payments');
        $response->assertDontSee('Finance');
        $response->assertDontSee('User Management');
    }
}
