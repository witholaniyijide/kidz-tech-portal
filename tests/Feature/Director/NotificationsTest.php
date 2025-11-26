<?php

namespace Tests\Feature\Director;

use App\Models\User;
use App\Models\Student;
use App\Models\TutorReport;
use App\Models\TutorAssessment;
use App\Services\DirectorApprovalService;
use App\Notifications\TutorReportApprovedNotification;
use App\Notifications\AssessmentApprovedNotification;
use App\Notifications\ParentReportAvailableNotification;
use App\Mail\DirectorFinalApprovalMail;
use App\Mail\ParentReportReadyMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationsTest extends TestCase
{
    use RefreshDatabase;

    protected $director;
    protected $tutor;
    protected $manager;
    protected $parent;
    protected $student;
    protected $approvalService;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test users with roles
        $this->director = User::factory()->create(['notify_email' => true]);
        $this->director->roles()->attach(\App\Models\Role::factory()->create(['name' => 'director']));

        $this->tutor = User::factory()->create(['notify_email' => true]);
        $this->tutor->roles()->attach(\App\Models\Role::factory()->create(['name' => 'tutor']));

        $this->manager = User::factory()->create(['notify_email' => true]);
        $this->manager->roles()->attach(\App\Models\Role::factory()->create(['name' => 'manager']));

        $this->parent = User::factory()->create(['email' => 'parent@example.com', 'notify_email' => true]);
        $this->parent->roles()->attach(\App\Models\Role::factory()->create(['name' => 'parent']));

        // Create student linked to parent
        $this->student = Student::factory()->create([
            'parent_id' => $this->parent->id,
            'father_email' => 'father@example.com',
            'mother_email' => 'mother@example.com',
        ]);

        $this->approvalService = new DirectorApprovalService();
    }

    /** @test */
    public function director_approval_triggers_tutor_notification()
    {
        Notification::fake();

        // Create a report ready for director approval
        $report = TutorReport::factory()->create([
            'tutor_id' => $this->tutor->id,
            'student_id' => $this->student->id,
            'status' => 'approved-by-manager',
        ]);

        // Approve the report
        $this->approvalService->approveTutorReport(
            $report,
            $this->director,
            'Great work!',
            'Director Signature'
        );

        // Assert tutor received notification
        Notification::assertSentTo(
            $this->tutor,
            TutorReportApprovedNotification::class,
            function ($notification) use ($report) {
                return $notification->report->id === $report->id;
            }
        );
    }

    /** @test */
    public function director_approval_triggers_manager_notification()
    {
        Notification::fake();

        $report = TutorReport::factory()->create([
            'tutor_id' => $this->tutor->id,
            'student_id' => $this->student->id,
            'status' => 'approved-by-manager',
        ]);

        $this->approvalService->approveTutorReport(
            $report,
            $this->director,
            'Excellent progress'
        );

        // Assert manager received notification
        Notification::assertSentTo(
            $this->manager,
            TutorReportApprovedNotification::class
        );
    }

    /** @test */
    public function parent_receives_email_when_report_is_approved()
    {
        Mail::fake();

        $report = TutorReport::factory()->create([
            'tutor_id' => $this->tutor->id,
            'student_id' => $this->student->id,
            'status' => 'approved-by-manager',
        ]);

        $this->approvalService->approveTutorReport(
            $report,
            $this->director,
            'Well done!'
        );

        // Assert parent received email
        Mail::assertSent(ParentReportReadyMail::class, function ($mail) use ($report) {
            return $mail->hasTo('parent@example.com') &&
                   $mail->report->id === $report->id;
        });

        // Assert father and mother emails received
        Mail::assertSent(ParentReportReadyMail::class, function ($mail) {
            return $mail->hasTo('father@example.com');
        });

        Mail::assertSent(ParentReportReadyMail::class, function ($mail) {
            return $mail->hasTo('mother@example.com');
        });
    }

    /** @test */
    public function parent_receives_notification_when_report_is_approved()
    {
        Notification::fake();

        $report = TutorReport::factory()->create([
            'tutor_id' => $this->tutor->id,
            'student_id' => $this->student->id,
            'status' => 'approved-by-manager',
        ]);

        $this->approvalService->approveTutorReport(
            $report,
            $this->director
        );

        // Assert parent received notification
        Notification::assertSentTo(
            $this->parent,
            ParentReportAvailableNotification::class,
            function ($notification) use ($report) {
                return $notification->report->id === $report->id;
            }
        );
    }

    /** @test */
    public function tutor_receives_email_with_correct_metadata()
    {
        Mail::fake();

        $report = TutorReport::factory()->create([
            'tutor_id' => $this->tutor->id,
            'student_id' => $this->student->id,
            'status' => 'approved-by-manager',
            'month' => '2025-01',
            'attendance_score' => 95,
            'performance_rating' => 'excellent',
        ]);

        $this->approvalService->approveTutorReport(
            $report,
            $this->director,
            'Outstanding work this month!'
        );

        Mail::assertSent(DirectorFinalApprovalMail::class, function ($mail) use ($report) {
            return $mail->hasTo($this->tutor->email) &&
                   $mail->report->id === $report->id &&
                   $mail->report->month === '2025-01' &&
                   $mail->report->attendance_score === 95;
        });
    }

    /** @test */
    public function assessment_approval_triggers_correct_notifications()
    {
        Notification::fake();

        $assessment = TutorAssessment::factory()->create([
            'tutor_id' => $this->tutor->id,
            'manager_id' => $this->manager->id,
            'status' => 'approved-by-manager',
        ]);

        $this->approvalService->approveTutorAssessment(
            $assessment,
            $this->director,
            'Keep up the good work!'
        );

        // Assert tutor received notification
        Notification::assertSentTo(
            $this->tutor,
            AssessmentApprovedNotification::class,
            function ($notification) use ($assessment) {
                return $notification->assessment->id === $assessment->id;
            }
        );

        // Assert manager received notification
        Notification::assertSentTo(
            $this->manager,
            AssessmentApprovedNotification::class
        );
    }

    /** @test */
    public function notification_preferences_are_respected()
    {
        Notification::fake();
        Mail::fake();

        // Disable email notifications for tutor
        $this->tutor->update(['notify_email' => false]);

        $report = TutorReport::factory()->create([
            'tutor_id' => $this->tutor->id,
            'student_id' => $this->student->id,
            'status' => 'approved-by-manager',
        ]);

        $this->approvalService->approveTutorReport(
            $report,
            $this->director
        );

        // Database notification should still be sent
        Notification::assertSentTo(
            $this->tutor,
            TutorReportApprovedNotification::class
        );

        // But the notification's via() method should exclude 'mail' channel
        // (This would need to be tested at the notification class level)
    }

    /** @test */
    public function report_approval_creates_audit_log()
    {
        $report = TutorReport::factory()->create([
            'tutor_id' => $this->tutor->id,
            'student_id' => $this->student->id,
            'status' => 'approved-by-manager',
        ]);

        $this->approvalService->approveTutorReport(
            $report,
            $this->director,
            'Excellent progress'
        );

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $this->director->id,
            'action' => 'report.approve.director',
            'auditable_type' => TutorReport::class,
            'auditable_id' => $report->id,
        ]);
    }

    /** @test */
    public function report_approval_creates_director_activity_log()
    {
        $report = TutorReport::factory()->create([
            'tutor_id' => $this->tutor->id,
            'student_id' => $this->student->id,
            'status' => 'approved-by-manager',
        ]);

        $this->approvalService->approveTutorReport(
            $report,
            $this->director
        );

        $this->assertDatabaseHas('director_activity_logs', [
            'director_id' => $this->director->id,
            'action_type' => 'approved_report',
            'model_type' => TutorReport::class,
            'model_id' => $report->id,
        ]);
    }

    /** @test */
    public function notification_contains_correct_data_structure()
    {
        Notification::fake();

        $report = TutorReport::factory()->create([
            'tutor_id' => $this->tutor->id,
            'student_id' => $this->student->id,
            'status' => 'approved-by-manager',
            'month' => '2025-01',
            'performance_rating' => 'excellent',
            'attendance_score' => 95,
        ]);

        $this->approvalService->approveTutorReport(
            $report,
            $this->director,
            'Great work!'
        );

        Notification::assertSentTo(
            $this->tutor,
            TutorReportApprovedNotification::class,
            function ($notification) use ($report) {
                $array = $notification->toArray($this->tutor);
                return $array['type'] === 'report_approved' &&
                       $array['report_id'] === $report->id &&
                       $array['month'] === '2025-01' &&
                       $array['performance_rating'] === 'excellent' &&
                       $array['attendance_score'] === 95 &&
                       isset($array['link']);
            }
        );
    }

    /** @test */
    public function emails_are_queued_for_background_processing()
    {
        Mail::fake();

        $report = TutorReport::factory()->create([
            'tutor_id' => $this->tutor->id,
            'student_id' => $this->student->id,
            'status' => 'approved-by-manager',
        ]);

        $this->approvalService->approveTutorReport(
            $report,
            $this->director
        );

        // Assert mails are queued (implement ShouldQueue interface)
        Mail::assertQueued(DirectorFinalApprovalMail::class);
        Mail::assertQueued(ParentReportReadyMail::class);
    }
}
