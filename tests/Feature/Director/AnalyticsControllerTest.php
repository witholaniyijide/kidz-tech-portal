<?php

namespace Tests\Feature\Director;

use App\Models\User;
use App\Models\Role;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\TutorReport;
use App\Models\TutorAssessment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class AnalyticsControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $director;
    protected $tutor;
    protected $student;

    protected function setUp(): void
    {
        parent::setUp();

        // Create director role and user
        $directorRole = Role::firstOrCreate(['name' => 'director'], ['description' => 'Director']);
        $this->director = User::factory()->create();
        $this->director->roles()->attach($directorRole);

        // Create test data
        $this->tutor = Tutor::factory()->create(['status' => 'active']);
        $this->student = Student::factory()->create(['status' => 'active']);
    }

    /** @test */
    public function director_can_access_analytics_index_page()
    {
        $response = $this->actingAs($this->director)
            ->get(route('director.analytics.index'));

        $response->assertStatus(200);
        $response->assertSee('Director Analytics Dashboard');
        $response->assertSee('Total Students');
        $response->assertSee('Active Students');
    }

    /** @test */
    public function unauthenticated_users_cannot_access_analytics()
    {
        $response = $this->get(route('director.analytics.index'));
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function non_director_users_cannot_access_analytics()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('director.analytics.index'));

        $response->assertStatus(403);
    }

    /** @test */
    public function enrollments_endpoint_returns_json_data()
    {
        // Create students with different created_at dates
        Student::factory()->count(5)->create([
            'created_at' => now()->subMonths(1),
            'status' => 'active'
        ]);

        $response = $this->actingAs($this->director)
            ->get(route('director.analytics.enrollments'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'labels',
            'datasets' => [
                '*' => ['label', 'data', 'borderColor', 'backgroundColor', 'tension']
            ],
            'table'
        ]);
    }

    /** @test */
    public function enrollments_data_is_cached()
    {
        Cache::flush();

        // First request
        $this->actingAs($this->director)
            ->get(route('director.analytics.enrollments'));

        $this->assertTrue(Cache::has('director.analytics.enrollments'));
    }

    /** @test */
    public function reports_endpoint_returns_json_data()
    {
        TutorReport::factory()->count(3)->create([
            'tutor_id' => $this->tutor->id,
            'student_id' => $this->student->id,
            'status' => 'approved-by-manager',
            'month' => now()->format('Y-m')
        ]);

        $response = $this->actingAs($this->director)
            ->get(route('director.analytics.reports'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'monthly' => [
                'labels',
                'datasets'
            ],
            'byStatus' => [
                'labels',
                'datasets'
            ]
        ]);
    }

    /** @test */
    public function tutor_performance_endpoint_returns_json_data()
    {
        TutorReport::factory()->count(2)->create([
            'tutor_id' => $this->tutor->id,
            'student_id' => $this->student->id,
            'status' => 'approved-by-director'
        ]);

        $response = $this->actingAs($this->director)
            ->get(route('director.analytics.tutors'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'studentsPerTutor' => [
                'labels',
                'datasets'
            ],
            'attendanceByTutor' => [
                'labels',
                'datasets'
            ]
        ]);
    }

    /** @test */
    public function assessment_endpoint_returns_json_data()
    {
        TutorAssessment::factory()->count(3)->create([
            'tutor_id' => $this->tutor->id,
            'status' => 'approved-by-manager'
        ]);

        $response = $this->actingAs($this->director)
            ->get(route('director.analytics.assessments'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'performanceTrend' => [
                'labels',
                'datasets'
            ],
            'ratingDistribution' => [
                'labels',
                'datasets'
            ]
        ]);
    }

    /** @test */
    public function export_reports_csv_requires_valid_month()
    {
        $response = $this->actingAs($this->director)
            ->get(route('director.analytics.reports.export'));

        $response->assertSessionHasErrors('month');
    }

    /** @test */
    public function export_reports_csv_downloads_file_with_valid_month()
    {
        $month = now()->format('Y-m');

        TutorReport::factory()->count(5)->create([
            'tutor_id' => $this->tutor->id,
            'student_id' => $this->student->id,
            'month' => $month,
            'status' => 'approved-by-director'
        ]);

        $response = $this->actingAs($this->director)
            ->get(route('director.analytics.reports.export', ['month' => $month]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $response->assertHeader('Content-Disposition', 'attachment; filename="tutor-reports-' . $month . '.csv"');
    }

    /** @test */
    public function export_reports_csv_logs_activity()
    {
        $month = now()->format('Y-m');

        $response = $this->actingAs($this->director)
            ->get(route('director.analytics.reports.export', ['month' => $month]));

        $this->assertDatabaseHas('director_activity_logs', [
            'director_id' => $this->director->id,
            'action_type' => 'exported_reports_csv'
        ]);
    }

    /** @test */
    public function export_tutors_csv_downloads_file()
    {
        Tutor::factory()->count(10)->create(['status' => 'active']);

        $response = $this->actingAs($this->director)
            ->get(route('director.analytics.tutors.export'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $response->assertHeader('Content-Disposition', 'attachment; filename="tutor-performance-' . now()->format('Y-m-d') . '.csv"');
    }

    /** @test */
    public function export_tutors_csv_logs_activity()
    {
        $response = $this->actingAs($this->director)
            ->get(route('director.analytics.tutors.export'));

        $this->assertDatabaseHas('director_activity_logs', [
            'director_id' => $this->director->id,
            'action_type' => 'exported_tutors_csv'
        ]);
    }

    /** @test */
    public function dashboard_stats_are_cached()
    {
        Cache::flush();

        // Access index page which calls getDashboardStats()
        $this->actingAs($this->director)
            ->get(route('director.analytics.index'));

        $this->assertTrue(Cache::has('director.analytics.dashboard.stats'));
    }

    /** @test */
    public function cache_ttl_is_appropriate_for_different_endpoints()
    {
        Cache::flush();

        // Test enrollments cache (1 hour = 3600 seconds)
        $this->actingAs($this->director)
            ->get(route('director.analytics.enrollments'));

        $enrollmentsTtl = Cache::get('director.analytics.enrollments');
        $this->assertNotNull($enrollmentsTtl);

        // Test dashboard stats cache (5 minutes = 300 seconds)
        Cache::flush();
        $this->actingAs($this->director)
            ->get(route('director.analytics.index'));

        $statsTtl = Cache::get('director.analytics.dashboard.stats');
        $this->assertNotNull($statsTtl);
    }
}
