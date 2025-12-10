<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\TutorReport;
use App\Models\Tutor;
use App\Models\Student;
use App\Models\AuditLog;
use App\Models\TutorNotification;
use App\Models\ManagerNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DirectorReportApprovalTest extends TestCase
{
    use RefreshDatabase;

    protected $director;
    protected $manager;
    protected $tutorUser;
    protected $tutor;
    protected $student;
    protected $report;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        $directorRole = Role::firstOrCreate(['name' => 'director'], [
            'description' => 'Director role',
            'permissions' => json_encode(['view_all', 'approve_reports']),
        ]);

        $managerRole = Role::firstOrCreate(['name' => 'manager'], [
            'description' => 'Manager role',
            'permissions' => json_encode(['view_reports', 'review_reports']),
        ]);

        $tutorRole = Role::firstOrCreate(['name' => 'tutor'], [
            'description' => 'Tutor role',
            'permissions' => json_encode(['create_reports']),
        ]);

        // Create users
        $this->director = User::factory()->create([
            'name' => 'Director User',
            'email' => 'director@test.com',
        ]);
        $this->director->roles()->attach($directorRole);

        $this->manager = User::factory()->create([
            'name' => 'Manager User',
            'email' => 'manager@test.com',
        ]);
        $this->manager->roles()->attach($managerRole);

        $this->tutorUser = User::factory()->create([
            'name' => 'Tutor User',
            'email' => 'tutor@test.com',
        ]);
        $this->tutorUser->roles()->attach($tutorRole);

        // Create tutor
        $this->tutor = Tutor::factory()->create([
            'email' => 'tutor@test.com',
            'first_name' => 'Test',
            'last_name' => 'Tutor',
            'status' => 'active',
        ]);

        // Create student
        $this->student = Student::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'Student',
            'status' => 'active',
            'date_of_birth' => now()->subYears(10),
        ]);

        // Create a manager-approved report
        $this->report = TutorReport::factory()->create([
            'tutor_id' => $this->tutor->id,
            'student_id' => $this->student->id,
            'month' => now()->format('Y-m'),
            'status' => 'approved-by-manager',
            'progress_summary' => 'Good progress this month',
            'strengths' => 'Strong understanding of concepts',
            'weaknesses' => 'Needs more practice',
            'next_steps' => 'Continue with current curriculum',
            'attendance_score' => 85,
            'performance_rating' => 'good',
            'manager_comment' => 'Approved by manager',
            'submitted_at' => now()->subDays(2),
            'approved_by_manager_at' => now()->subDay(),
        ]);
    }

    /** @test */
    public function director_can_view_manager_approved_reports()
    {
        $response = $this->actingAs($this->director)
            ->get(route('director.reports.index'));

        $response->assertStatus(200);
        $response->assertSee($this->student->first_name);
        $response->assertSee($this->tutor->first_name);
    }

    /** @test */
    public function director_can_approve_report_changes_status_and_creates_audit_and_notifications()
    {
        $this->actingAs($this->director);

        $response = $this->post(route('director.reports.approve', $this->report), [
            'director_comment' => 'Excellent work, approved!',
        ]);

        $response->assertRedirect(route('director.reports.index'));
        $response->assertSessionHas('success');

        // Check report was updated
        $this->report->refresh();
        $this->assertEquals('approved-by-director', $this->report->status);
        $this->assertEquals('Excellent work, approved!', $this->report->director_comment);
        $this->assertNotNull($this->report->approved_by_director_at);

        // Check audit log was created
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $this->director->id,
            'action' => 'report.approve.director',
            'auditable_type' => TutorReport::class,
            'auditable_id' => $this->report->id,
        ]);

        // Check notifications were created
        $this->assertDatabaseHas('tutor_notifications', [
            'tutor_id' => $this->tutor->id,
            'type' => 'system',
        ]);

        $this->assertDatabaseHas('manager_notifications', [
            'user_id' => $this->manager->id,
            'type' => 'report',
        ]);
    }

    /** @test */
    public function director_must_provide_comment_when_rejecting_report()
    {
        $this->actingAs($this->director);

        // Try to reject without comment
        $response = $this->post(route('director.reports.reject', $this->report), [
            'director_comment' => '',
        ]);

        $response->assertSessionHasErrors('director_comment');

        // Reject with comment
        $response = $this->post(route('director.reports.reject', $this->report), [
            'director_comment' => 'Please revise and resubmit',
        ]);

        $response->assertRedirect(route('director.reports.index'));
        $response->assertSessionHas('success');

        // Check report was updated
        $this->report->refresh();
        $this->assertEquals('rejected', $this->report->status);
        $this->assertEquals('Please revise and resubmit', $this->report->director_comment);

        // Check audit log was created
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $this->director->id,
            'action' => 'report.reject.director',
            'auditable_type' => TutorReport::class,
            'auditable_id' => $this->report->id,
        ]);
    }

    /** @test */
    public function unauthorized_user_cannot_access_director_routes()
    {
        // Try as unauthenticated user
        $response = $this->get(route('director.reports.index'));
        $response->assertRedirect(route('login'));

        // Try as manager
        $response = $this->actingAs($this->manager)
            ->get(route('director.reports.index'));
        $response->assertStatus(403);

        // Try as tutor
        $response = $this->actingAs($this->tutorUser)
            ->get(route('director.reports.index'));
        $response->assertStatus(403);
    }

    /** @test */
    public function director_cannot_approve_report_not_in_manager_approved_status()
    {
        $this->actingAs($this->director);

        // Change report status to submitted
        $this->report->update(['status' => 'submitted']);

        $response = $this->post(route('director.reports.approve', $this->report), [
            'director_comment' => 'Approved',
        ]);

        $response->assertRedirect(route('director.reports.index'));
        $response->assertSessionHas('error');

        // Check report was not updated
        $this->report->refresh();
        $this->assertEquals('submitted', $this->report->status);
    }

    /** @test */
    public function director_can_view_single_report_with_audit_trail()
    {
        // Create an audit log entry
        AuditLog::create([
            'user_id' => $this->manager->id,
            'action' => 'report.approve.manager',
            'auditable_type' => TutorReport::class,
            'auditable_id' => $this->report->id,
            'meta' => [
                'manager_comment' => 'Approved by manager',
                'previous_status' => 'submitted',
            ],
        ]);

        $this->actingAs($this->director);

        $response = $this->get(route('director.reports.show', $this->report));

        $response->assertStatus(200);
        $response->assertSee($this->report->progress_summary);
        $response->assertSee('Audit Trail');
    }

    /** @test */
    public function director_approval_prevents_idempotent_operations()
    {
        $this->actingAs($this->director);

        // First approval
        $this->post(route('director.reports.approve', $this->report), [
            'director_comment' => 'First approval',
        ]);

        $this->report->refresh();
        $this->assertEquals('approved-by-director', $this->report->status);

        // Try to approve again
        $response = $this->post(route('director.reports.approve', $this->report), [
            'director_comment' => 'Second approval attempt',
        ]);

        $response->assertRedirect(route('director.reports.index'));
        $response->assertSessionHas('error');
    }
}
