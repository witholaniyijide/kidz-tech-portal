<?php

namespace Tests\Feature\Director;

use App\Models\User;
use App\Models\Role;
use App\Models\TutorReport;
use App\Models\Tutor;
use App\Models\Student;
use App\Models\DirectorActivityLog;
use App\Models\TutorNotification;
use App\Models\ManagerNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DirectorReportWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected $director;
    protected $manager;
    protected $tutor;
    protected $student;
    protected $report;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        $directorRole = Role::firstOrCreate(['name' => 'director'], [
            'description' => 'Director role',
        ]);

        $managerRole = Role::firstOrCreate(['name' => 'manager'], [
            'description' => 'Manager role',
        ]);

        // Create users
        $this->director = User::factory()->create(['email' => 'director@test.com']);
        $this->director->roles()->attach($directorRole);

        $this->manager = User::factory()->create(['email' => 'manager@test.com']);
        $this->manager->roles()->attach($managerRole);

        // Create tutor
        $this->tutor = Tutor::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'Tutor',
            'status' => 'active',
        ]);

        // Create student
        $this->student = Student::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'Student',
            'status' => 'active',
        ]);

        // Create a manager-approved report
        $this->report = TutorReport::factory()->create([
            'tutor_id' => $this->tutor->id,
            'student_id' => $this->student->id,
            'month' => now()->format('Y-m'),
            'status' => 'approved-by-manager',
            'progress_summary' => 'Good progress',
            'manager_comment' => 'Approved by manager',
            'approved_by_manager_at' => now()->subDay(),
        ]);
    }

    /** @test */
    public function director_can_view_pending_reports()
    {
        $response = $this->actingAs($this->director)
            ->get(route('director.reports.index'));

        $response->assertStatus(200);
        $response->assertSee($this->student->first_name);
    }

    /** @test */
    public function director_can_approve_report()
    {
        $response = $this->actingAs($this->director)
            ->post(route('director.reports.approve', $this->report), [
                'director_comment' => 'Approved',
                'director_signature' => 'Director Name',
            ]);

        $response->assertRedirect(route('director.reports.index'));
        $response->assertSessionHas('success');

        $this->report->refresh();
        $this->assertEquals('approved-by-director', $this->report->status);
        $this->assertNotNull($this->report->approved_by_director_at);
    }

    /** @test */
    public function director_cannot_approve_draft_reports()
    {
        $draftReport = TutorReport::factory()->create([
            'tutor_id' => $this->tutor->id,
            'student_id' => $this->student->id,
            'status' => 'draft',
        ]);

        $response = $this->actingAs($this->director)
            ->post(route('director.reports.approve', $draftReport), [
                'director_comment' => 'Approved',
            ]);

        $response->assertRedirect(route('director.reports.index'));
        $response->assertSessionHas('error');

        $draftReport->refresh();
        $this->assertEquals('draft', $draftReport->status);
    }

    /** @test */
    public function director_cannot_approve_manager_unapproved_reports()
    {
        $submittedReport = TutorReport::factory()->create([
            'tutor_id' => $this->tutor->id,
            'student_id' => $this->student->id,
            'status' => 'submitted',
        ]);

        $response = $this->actingAs($this->director)
            ->post(route('director.reports.approve', $submittedReport), [
                'director_comment' => 'Approved',
            ]);

        $response->assertRedirect(route('director.reports.index'));
        $response->assertSessionHas('error');

        $submittedReport->refresh();
        $this->assertEquals('submitted', $submittedReport->status);
    }

    /** @test */
    public function director_can_add_comment_to_report()
    {
        $response = $this->actingAs($this->director)
            ->post(route('director.reports.comment', $this->report), [
                'comment' => 'This is a test comment',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('tutor_report_comments', [
            'report_id' => $this->report->id,
            'user_id' => $this->director->id,
            'comment' => 'This is a test comment',
        ]);
    }

    /** @test */
    public function director_activity_is_logged()
    {
        $this->actingAs($this->director)
            ->post(route('director.reports.approve', $this->report), [
                'director_comment' => 'Approved',
            ]);

        $this->assertDatabaseHas('director_activity_logs', [
            'director_id' => $this->director->id,
            'action_type' => 'approved_report',
            'model_type' => TutorReport::class,
            'model_id' => $this->report->id,
        ]);
    }

    /** @test */
    public function unauthorized_users_cannot_access_director_routes()
    {
        $response = $this->get(route('director.reports.index'));
        $response->assertRedirect(route('login'));

        $response = $this->actingAs($this->manager)
            ->get(route('director.reports.index'));
        $response->assertStatus(403);
    }
}
