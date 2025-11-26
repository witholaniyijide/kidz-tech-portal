<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Role;
use App\Models\TutorReport;
use App\Models\TutorAssessment;
use App\Models\Tutor;
use App\Models\Student;
use App\Models\DirectorActivityLog;
use App\Services\DirectorApprovalService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DirectorApprovalServiceTest extends TestCase
{
    use RefreshDatabase;

    protected DirectorApprovalService $service;
    protected $director;
    protected $tutor;
    protected $student;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new DirectorApprovalService();

        // Create roles
        $directorRole = Role::firstOrCreate(['name' => 'director'], [
            'description' => 'Director role',
        ]);

        // Create director user
        $this->director = User::factory()->create(['email' => 'director@test.com']);
        $this->director->roles()->attach($directorRole);

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
    }

    /** @test */
    public function service_can_approve_tutor_report()
    {
        $report = TutorReport::factory()->create([
            'tutor_id' => $this->tutor->id,
            'student_id' => $this->student->id,
            'status' => 'approved-by-manager',
        ]);

        $result = $this->service->approveTutorReport(
            $report,
            $this->director,
            'Test comment',
            'Test signature'
        );

        $this->assertTrue($result);

        $report->refresh();
        $this->assertEquals('approved-by-director', $report->status);
        $this->assertEquals('Test comment', $report->director_comment);
        $this->assertEquals('Test signature', $report->director_signature);
        $this->assertNotNull($report->approved_by_director_at);
    }

    /** @test */
    public function service_logs_activity_when_approving_report()
    {
        $report = TutorReport::factory()->create([
            'tutor_id' => $this->tutor->id,
            'student_id' => $this->student->id,
            'status' => 'approved-by-manager',
        ]);

        $this->service->approveTutorReport(
            $report,
            $this->director,
            'Test comment'
        );

        $this->assertDatabaseHas('director_activity_logs', [
            'director_id' => $this->director->id,
            'action_type' => 'approved_report',
            'model_type' => TutorReport::class,
            'model_id' => $report->id,
        ]);
    }

    /** @test */
    public function service_creates_audit_log_when_approving_report()
    {
        $report = TutorReport::factory()->create([
            'tutor_id' => $this->tutor->id,
            'student_id' => $this->student->id,
            'status' => 'approved-by-manager',
        ]);

        $this->service->approveTutorReport(
            $report,
            $this->director,
            'Test comment'
        );

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $this->director->id,
            'action' => 'report.approve.director',
            'auditable_type' => TutorReport::class,
            'auditable_id' => $report->id,
        ]);
    }

    /** @test */
    public function service_can_approve_tutor_assessment()
    {
        $assessment = TutorAssessment::factory()->create([
            'tutor_id' => $this->tutor->id,
            'status' => 'approved-by-manager',
        ]);

        $result = $this->service->approveTutorAssessment(
            $assessment,
            $this->director,
            'Test comment'
        );

        $this->assertTrue($result);

        $assessment->refresh();
        $this->assertEquals('approved-by-director', $assessment->status);
        $this->assertEquals('Test comment', $assessment->director_comment);
        $this->assertNotNull($assessment->approved_by_director_at);
    }

    /** @test */
    public function service_logs_activity_when_approving_assessment()
    {
        $assessment = TutorAssessment::factory()->create([
            'tutor_id' => $this->tutor->id,
            'status' => 'approved-by-manager',
        ]);

        $this->service->approveTutorAssessment(
            $assessment,
            $this->director,
            'Test comment'
        );

        $this->assertDatabaseHas('director_activity_logs', [
            'director_id' => $this->director->id,
            'action_type' => 'approved_assessment',
            'model_type' => TutorAssessment::class,
            'model_id' => $assessment->id,
        ]);
    }

    /** @test */
    public function service_can_log_director_action()
    {
        $log = $this->service->logDirectorAction(
            $this->director,
            'test_action',
            TutorReport::class,
            123
        );

        $this->assertInstanceOf(DirectorActivityLog::class, $log);
        $this->assertEquals($this->director->id, $log->director_id);
        $this->assertEquals('test_action', $log->action_type);
        $this->assertEquals(TutorReport::class, $log->model_type);
        $this->assertEquals(123, $log->model_id);
    }
}
