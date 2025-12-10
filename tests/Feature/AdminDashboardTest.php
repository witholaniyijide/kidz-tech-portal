<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\AttendanceRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        Role::create(['name' => 'admin']);

        // Create admin user
        $this->adminUser = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
        ]);
        $this->adminUser->assignRole('admin');
    }

    /** @test */
    public function admin_dashboard_route_is_accessible_by_admin()
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('dashboard.admin'));

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_dashboard_displays_stat_cards()
    {
        // Create test data
        Student::factory()->count(5)->create(['status' => 'active']);
        Student::factory()->count(2)->create(['status' => 'inactive']);
        Tutor::factory()->count(3)->create(['status' => 'active']);

        $response = $this->actingAs($this->adminUser)
            ->get(route('dashboard.admin'));

        $response->assertStatus(200);

        // Assert stat cards data is present
        $response->assertViewHas('stats', function ($stats) {
            return $stats['totalStudents'] === 7
                && $stats['activeStudents'] === 5
                && $stats['totalTutors'] === 3;
        });
    }

    /** @test */
    public function admin_dashboard_displays_recent_students()
    {
        // Create test students
        Student::factory()->count(5)->create([
            'status' => 'active',
            'first_name' => 'Test',
            'last_name' => 'Student',
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('dashboard.admin'));

        $response->assertStatus(200);

        // Assert students data is present
        $response->assertViewHas('students', function ($students) {
            return count($students) > 0;
        });
    }

    /** @test */
    public function admin_dashboard_displays_recent_tutors()
    {
        // Create test tutors
        Tutor::factory()->count(3)->create([
            'status' => 'active',
            'name' => 'Test Tutor',
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('dashboard.admin'));

        $response->assertStatus(200);

        // Assert tutors data is present
        $response->assertViewHas('tutors', function ($tutors) {
            return count($tutors) > 0;
        });
    }

    /** @test */
    public function admin_dashboard_displays_pending_attendance_count()
    {
        // Create pending attendance records
        AttendanceRecord::factory()->count(5)->create(['status' => 'pending']);

        $response = $this->actingAs($this->adminUser)
            ->get(route('dashboard.admin'));

        $response->assertStatus(200);

        // Assert pending attendance stat is present
        $response->assertViewHas('stats', function ($stats) {
            return $stats['pendingAttendance'] === 5;
        });
    }

    /** @test */
    public function admin_dashboard_contains_key_components()
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('dashboard.admin'));

        $response->assertStatus(200);

        // Assert the page contains key text elements
        $response->assertSee('Welcome back');
        $response->assertSee('Total Students');
        $response->assertSee('Total Tutors');
        $response->assertSee('Pending Attendance');
    }

    /** @test */
    public function non_admin_cannot_access_admin_dashboard()
    {
        $regularUser = User::factory()->create();

        $response = $this->actingAs($regularUser)
            ->get(route('dashboard.admin'));

        // Should redirect or return 403
        $response->assertStatus(403);
    }

    /** @test */
    public function unauthenticated_user_cannot_access_admin_dashboard()
    {
        $response = $this->get(route('dashboard.admin'));

        // Should redirect to login
        $response->assertRedirect(route('login'));
    }
}
