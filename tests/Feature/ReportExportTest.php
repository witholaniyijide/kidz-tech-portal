<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Tutor;
use App\Models\Student;
use App\Models\TutorReport;
use App\Mail\ReportApprovedMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ReportExportTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that tutors can export their own reports as PDF.
     */
    public function test_tutor_can_export_own_report_as_pdf(): void
    {
        // Create a tutor user
        $user = User::factory()->create(['role' => 'tutor']);
        $tutor = Tutor::factory()->create(['user_id' => $user->id]);

        // Create a student
        $student = Student::factory()->create(['tutor_id' => $tutor->id]);

        // Create a report
        $report = TutorReport::factory()->create([
            'tutor_id' => $tutor->id,
            'student_id' => $student->id,
            'status' => 'submitted',
            'month' => '2025-01',
            'progress_summary' => 'Student is doing well',
            'strengths' => 'Good communication skills',
            'weaknesses' => 'Needs to work on time management',
            'next_steps' => 'Continue with current curriculum',
            'attendance_score' => 95,
            'performance_rating' => 8,
        ]);

        // Authenticate as tutor
        $response = $this->actingAs($user)->get(route('tutor.reports.pdf', $report));

        // Assert PDF download response
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    /**
     * Test that tutors cannot export other tutors' reports.
     */
    public function test_tutor_cannot_export_other_tutors_report(): void
    {
        // Create first tutor and report
        $user1 = User::factory()->create(['role' => 'tutor']);
        $tutor1 = Tutor::factory()->create(['user_id' => $user1->id]);
        $student1 = Student::factory()->create(['tutor_id' => $tutor1->id]);
        $report1 = TutorReport::factory()->create([
            'tutor_id' => $tutor1->id,
            'student_id' => $student1->id,
        ]);

        // Create second tutor
        $user2 = User::factory()->create(['role' => 'tutor']);
        $tutor2 = Tutor::factory()->create(['user_id' => $user2->id]);

        // Try to access first tutor's report as second tutor
        $response = $this->actingAs($user2)->get(route('tutor.reports.pdf', $report1));

        // Assert forbidden
        $response->assertStatus(403);
    }

    /**
     * Test that print view renders correctly.
     */
    public function test_tutor_can_view_printable_report(): void
    {
        // Create a tutor user
        $user = User::factory()->create(['role' => 'tutor']);
        $tutor = Tutor::factory()->create(['user_id' => $user->id]);

        // Create a student
        $student = Student::factory()->create(['tutor_id' => $tutor->id]);

        // Create a report
        $report = TutorReport::factory()->create([
            'tutor_id' => $tutor->id,
            'student_id' => $student->id,
            'month' => '2025-01',
        ]);

        // Authenticate as tutor
        $response = $this->actingAs($user)->get(route('tutor.reports.print', $report));

        // Assert successful response
        $response->assertStatus(200);
        $response->assertViewIs('tutor.reports.print');
        $response->assertViewHas('report');
    }

    /**
     * Test that manager can export reports as PDF.
     */
    public function test_manager_can_export_report_as_pdf(): void
    {
        // Create a manager user
        $manager = User::factory()->create(['role' => 'manager']);

        // Create tutor and student
        $tutorUser = User::factory()->create(['role' => 'tutor']);
        $tutor = Tutor::factory()->create(['user_id' => $tutorUser->id]);
        $student = Student::factory()->create(['tutor_id' => $tutor->id]);

        // Create a report
        $report = TutorReport::factory()->create([
            'tutor_id' => $tutor->id,
            'student_id' => $student->id,
            'status' => 'submitted',
        ]);

        // Authenticate as manager
        $response = $this->actingAs($manager)->get(route('manager.tutor-reports.pdf', $report));

        // Assert PDF download response
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    /**
     * Test that report approved email can be sent.
     */
    public function test_report_approved_email_can_be_sent(): void
    {
        Mail::fake();

        // Create tutor and student
        $tutorUser = User::factory()->create(['role' => 'tutor', 'email' => 'tutor@test.com']);
        $tutor = Tutor::factory()->create(['user_id' => $tutorUser->id]);
        $student = Student::factory()->create(['tutor_id' => $tutor->id]);

        // Create a report
        $report = TutorReport::factory()->create([
            'tutor_id' => $tutor->id,
            'student_id' => $student->id,
            'status' => 'approved-by-director',
        ]);

        // Send the mail
        Mail::to($tutorUser->email)->send(new ReportApprovedMail($report));

        // Assert mail was sent
        Mail::assertSent(ReportApprovedMail::class, function ($mail) use ($tutorUser) {
            return $mail->hasTo($tutorUser->email);
        });
    }

    /**
     * Test that report approved email contains PDF attachment.
     */
    public function test_report_approved_email_has_pdf_attachment(): void
    {
        Mail::fake();

        // Create tutor and student
        $tutorUser = User::factory()->create(['role' => 'tutor', 'email' => 'tutor@test.com']);
        $tutor = Tutor::factory()->create(['user_id' => $tutorUser->id]);
        $student = Student::factory()->create(['tutor_id' => $tutor->id]);

        // Create a report
        $report = TutorReport::factory()->create([
            'tutor_id' => $tutor->id,
            'student_id' => $student->id,
            'status' => 'approved-by-director',
        ]);

        // Send the mail
        Mail::to($tutorUser->email)->send(new ReportApprovedMail($report));

        // Assert mail was sent with attachments
        Mail::assertSent(ReportApprovedMail::class, function ($mail) {
            $attachments = $mail->attachments();
            return count($attachments) > 0;
        });
    }
}
