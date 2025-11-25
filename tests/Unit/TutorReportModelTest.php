<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\TutorReport;
use App\Models\TutorReportComment;
use App\Models\Tutor;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TutorReportModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a tutor report can be created.
     */
    public function test_tutor_report_can_be_created(): void
    {
        $user = User::factory()->create();
        $tutor = Tutor::factory()->create();
        $student = Student::factory()->create();

        $report = TutorReport::create([
            'tutor_id' => $tutor->id,
            'student_id' => $student->id,
            'title' => 'Test Report',
            'month' => 'November 2025',
            'period_from' => '2025-11-01',
            'period_to' => '2025-11-30',
            'content' => 'This is a test report content.',
            'summary' => 'Test summary',
            'rating' => 8,
            'status' => 'draft',
            'created_by' => $user->id,
        ]);

        $this->assertInstanceOf(TutorReport::class, $report);
        $this->assertEquals('Test Report', $report->title);
        $this->assertEquals('draft', $report->status);
        $this->assertEquals(8, $report->rating);
    }

    /**
     * Test that a tutor report belongs to a tutor.
     */
    public function test_tutor_report_belongs_to_tutor(): void
    {
        $user = User::factory()->create();
        $tutor = Tutor::factory()->create();

        $report = TutorReport::factory()->create([
            'tutor_id' => $tutor->id,
            'created_by' => $user->id,
        ]);

        $this->assertInstanceOf(Tutor::class, $report->tutor);
        $this->assertEquals($tutor->id, $report->tutor->id);
    }

    /**
     * Test that a tutor report belongs to a student.
     */
    public function test_tutor_report_belongs_to_student(): void
    {
        $user = User::factory()->create();
        $student = Student::factory()->create();

        $report = TutorReport::factory()->create([
            'student_id' => $student->id,
            'created_by' => $user->id,
        ]);

        $this->assertInstanceOf(Student::class, $report->student);
        $this->assertEquals($student->id, $report->student->id);
    }

    /**
     * Test that a tutor report belongs to an author (user).
     */
    public function test_tutor_report_belongs_to_author(): void
    {
        $user = User::factory()->create();

        $report = TutorReport::factory()->create([
            'created_by' => $user->id,
        ]);

        $this->assertInstanceOf(User::class, $report->author);
        $this->assertEquals($user->id, $report->author->id);
    }

    /**
     * Test that a tutor report has many comments.
     */
    public function test_tutor_report_has_many_comments(): void
    {
        $user = User::factory()->create();
        $report = TutorReport::factory()->create([
            'created_by' => $user->id,
        ]);

        $comment1 = TutorReportComment::factory()->create([
            'report_id' => $report->id,
            'user_id' => $user->id,
        ]);

        $comment2 = TutorReportComment::factory()->create([
            'report_id' => $report->id,
            'user_id' => $user->id,
        ]);

        $this->assertCount(2, $report->comments);
        $this->assertTrue($report->comments->contains($comment1));
        $this->assertTrue($report->comments->contains($comment2));
    }

    /**
     * Test draft scope.
     */
    public function test_draft_scope_returns_only_draft_reports(): void
    {
        $user = User::factory()->create();

        TutorReport::factory()->create([
            'status' => 'draft',
            'created_by' => $user->id,
        ]);

        TutorReport::factory()->create([
            'status' => 'submitted',
            'created_by' => $user->id,
        ]);

        TutorReport::factory()->create([
            'status' => 'draft',
            'created_by' => $user->id,
        ]);

        $draftReports = TutorReport::draft()->get();

        $this->assertCount(2, $draftReports);
        foreach ($draftReports as $report) {
            $this->assertEquals('draft', $report->status);
        }
    }

    /**
     * Test submitted scope.
     */
    public function test_submitted_scope_returns_only_submitted_reports(): void
    {
        $user = User::factory()->create();

        TutorReport::factory()->create([
            'status' => 'draft',
            'created_by' => $user->id,
        ]);

        TutorReport::factory()->create([
            'status' => 'submitted',
            'created_by' => $user->id,
        ]);

        TutorReport::factory()->create([
            'status' => 'submitted',
            'created_by' => $user->id,
        ]);

        $submittedReports = TutorReport::submitted()->get();

        $this->assertCount(2, $submittedReports);
        foreach ($submittedReports as $report) {
            $this->assertEquals('submitted', $report->status);
        }
    }

    /**
     * Test forTutor scope.
     */
    public function test_for_tutor_scope_returns_reports_for_specific_tutor(): void
    {
        $user = User::factory()->create();
        $tutor1 = Tutor::factory()->create();
        $tutor2 = Tutor::factory()->create();

        TutorReport::factory()->create([
            'tutor_id' => $tutor1->id,
            'created_by' => $user->id,
        ]);

        TutorReport::factory()->create([
            'tutor_id' => $tutor1->id,
            'created_by' => $user->id,
        ]);

        TutorReport::factory()->create([
            'tutor_id' => $tutor2->id,
            'created_by' => $user->id,
        ]);

        $tutor1Reports = TutorReport::forTutor($tutor1->id)->get();

        $this->assertCount(2, $tutor1Reports);
        foreach ($tutor1Reports as $report) {
            $this->assertEquals($tutor1->id, $report->tutor_id);
        }
    }
}
