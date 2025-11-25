<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\Tutor;
use App\Models\TutorNotification;
use App\Models\TutorReport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TutorReportTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $tutorUser;
    protected Tutor $tutor;
    protected Student $student;

    protected function setUp(): void
    {
        parent::setUp();

        // Create tutor user
        $this->tutorUser = User::factory()->create([
            'email' => 'tutor@test.com',
            'role' => 'tutor',
        ]);

        // Create tutor profile
        $this->tutor = Tutor::factory()->create([
            'email' => 'tutor@test.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        // Create student assigned to this tutor
        $this->student = Student::factory()->create([
            'tutor_id' => $this->tutor->id,
            'status' => 'active',
        ]);
    }

    /** @test */
    public function tutor_can_create_report_as_draft()
    {
        $reportData = [
            'student_id' => $this->student->id,
            'title' => 'October 2024 Progress Report',
            'month' => 'October 2024',
            'content' => 'Student has shown great improvement this month.',
            'rating' => 8,
            'status' => 'draft',
        ];

        $response = $this->actingAs($this->tutorUser)
            ->post(route('tutor.reports.store'), $reportData);

        $response->assertRedirect(route('tutor.reports.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('tutor_reports', [
            'tutor_id' => $this->tutor->id,
            'student_id' => $this->student->id,
            'title' => 'October 2024 Progress Report',
            'status' => 'draft',
            'created_by' => $this->tutorUser->id,
        ]);
    }

    /** @test */
    public function tutor_can_submit_report_directly()
    {
        $reportData = [
            'student_id' => $this->student->id,
            'title' => 'October 2024 Progress Report',
            'month' => 'October 2024',
            'content' => 'Student has shown great improvement this month.',
            'rating' => 8,
            'status' => 'submitted',
        ];

        $response = $this->actingAs($this->tutorUser)
            ->post(route('tutor.reports.store'), $reportData);

        $response->assertRedirect(route('tutor.reports.index'));

        $this->assertDatabaseHas('tutor_reports', [
            'tutor_id' => $this->tutor->id,
            'title' => 'October 2024 Progress Report',
            'status' => 'submitted',
        ]);

        // Verify notification was created
        $this->assertDatabaseHas('tutor_notifications', [
            'tutor_id' => $this->tutor->id,
            'type' => 'system',
        ]);
    }

    /** @test */
    public function tutor_can_submit_draft_report()
    {
        $report = TutorReport::factory()->create([
            'tutor_id' => $this->tutor->id,
            'student_id' => $this->student->id,
            'status' => 'draft',
            'created_by' => $this->tutorUser->id,
        ]);

        $response = $this->actingAs($this->tutorUser)
            ->post(route('tutor.reports.submit', $report));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $report->refresh();
        $this->assertEquals('submitted', $report->status);
        $this->assertNotNull($report->submitted_at);

        // Verify notification was created
        $this->assertDatabaseHas('tutor_notifications', [
            'tutor_id' => $this->tutor->id,
        ]);
    }

    /** @test */
    public function tutor_can_update_draft_report()
    {
        $report = TutorReport::factory()->create([
            'tutor_id' => $this->tutor->id,
            'student_id' => $this->student->id,
            'status' => 'draft',
            'title' => 'Old Title',
            'created_by' => $this->tutorUser->id,
        ]);

        $updateData = [
            'student_id' => $this->student->id,
            'title' => 'Updated Title',
            'month' => 'November 2024',
            'content' => 'Updated content',
        ];

        $response = $this->actingAs($this->tutorUser)
            ->put(route('tutor.reports.update', $report), $updateData);

        $response->assertRedirect();

        $report->refresh();
        $this->assertEquals('Updated Title', $report->title);
    }

    /** @test */
    public function tutor_cannot_update_submitted_report()
    {
        $report = TutorReport::factory()->create([
            'tutor_id' => $this->tutor->id,
            'student_id' => $this->student->id,
            'status' => 'submitted',
            'created_by' => $this->tutorUser->id,
        ]);

        $updateData = [
            'student_id' => $this->student->id,
            'title' => 'Updated Title',
            'month' => 'November 2024',
            'content' => 'Updated content',
        ];

        $response = $this->actingAs($this->tutorUser)
            ->put(route('tutor.reports.update', $report), $updateData);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    /** @test */
    public function tutor_can_delete_draft_report()
    {
        $report = TutorReport::factory()->create([
            'tutor_id' => $this->tutor->id,
            'student_id' => $this->student->id,
            'status' => 'draft',
            'created_by' => $this->tutorUser->id,
        ]);

        $response = $this->actingAs($this->tutorUser)
            ->delete(route('tutor.reports.destroy', $report));

        $response->assertRedirect(route('tutor.reports.index'));

        $this->assertDatabaseMissing('tutor_reports', [
            'id' => $report->id,
        ]);
    }

    /** @test */
    public function tutor_cannot_delete_submitted_report()
    {
        $report = TutorReport::factory()->create([
            'tutor_id' => $this->tutor->id,
            'student_id' => $this->student->id,
            'status' => 'submitted',
            'created_by' => $this->tutorUser->id,
        ]);

        $response = $this->actingAs($this->tutorUser)
            ->delete(route('tutor.reports.destroy', $report));

        $response->assertRedirect();
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('tutor_reports', [
            'id' => $report->id,
        ]);
    }

    /** @test */
    public function report_validation_requires_required_fields()
    {
        $response = $this->actingAs($this->tutorUser)
            ->post(route('tutor.reports.store'), []);

        $response->assertSessionHasErrors(['student_id', 'title', 'month', 'content']);
    }

    /** @test */
    public function tutor_can_add_comment_to_report()
    {
        $report = TutorReport::factory()->create([
            'tutor_id' => $this->tutor->id,
            'student_id' => $this->student->id,
            'created_by' => $this->tutorUser->id,
        ]);

        $response = $this->actingAs($this->tutorUser)
            ->post(route('tutor.reports.comments.store', $report), [
                'comment' => 'This is a test comment',
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('tutor_report_comments', [
            'report_id' => $report->id,
            'user_id' => $this->tutorUser->id,
            'comment' => 'This is a test comment',
            'role' => 'tutor',
        ]);
    }

    /** @test */
    public function guest_cannot_access_tutor_report_routes()
    {
        $response = $this->get(route('tutor.reports.index'));
        $response->assertRedirect(route('login'));

        $response = $this->get(route('tutor.reports.create'));
        $response->assertRedirect(route('login'));
    }
}
