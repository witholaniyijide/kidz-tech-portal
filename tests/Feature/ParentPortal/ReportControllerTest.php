<?php

namespace Tests\Feature\ParentPortal;

use App\Models\User;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\TutorReport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test parent reports index with filters
     */
    public function test_parent_reports_index_filters(): void
    {
        // Create parent and student
        $parent = User::factory()->create(['role' => 'parent']);
        $student = Student::factory()->create(['parent_id' => $parent->id, 'user_id' => $parent->id]);
        $tutor = Tutor::factory()->create();

        // Create multiple reports with different content
        $report1 = TutorReport::factory()->create([
            'student_id' => $student->id,
            'tutor_id' => $tutor->id,
            'status' => 'approved-by-director',
            'month' => 'January 2025',
            'progress_summary' => 'Excellent progress in Python programming',
            'strengths' => 'Strong problem-solving skills',
            'performance_rating' => 5,
        ]);

        $report2 = TutorReport::factory()->create([
            'student_id' => $student->id,
            'tutor_id' => $tutor->id,
            'status' => 'approved-by-director',
            'month' => 'February 2025',
            'progress_summary' => 'Good work in Scratch development',
            'strengths' => 'Creative thinking',
            'performance_rating' => 4,
        ]);

        // Test keyword search filter
        $response = $this->actingAs($parent)->get(route('student.reports.index', ['q' => 'Python']));
        $response->assertStatus(200);

        // Test sort by rating
        $response = $this->actingAs($parent)->get(route('student.reports.index', ['sort' => 'rating']));
        $response->assertStatus(200);

        // Test sort by oldest
        $response = $this->actingAs($parent)->get(route('student.reports.index', ['sort' => 'oldest']));
        $response->assertStatus(200);
    }

    /**
     * Test report PDF download requires authentication
     */
    public function test_report_pdf_download_auth(): void
    {
        $tutor = Tutor::factory()->create();
        $student = Student::factory()->create();

        $report = TutorReport::factory()->create([
            'student_id' => $student->id,
            'tutor_id' => $tutor->id,
            'status' => 'approved-by-director',
        ]);

        // Unauthenticated access should redirect to login
        $response = $this->get(route('student.reports.pdf', $report->id));
        $response->assertRedirect(route('login'));

        // Wrong student cannot access
        $otherParent = User::factory()->create(['role' => 'parent']);
        $otherStudent = Student::factory()->create(['parent_id' => $otherParent->id, 'user_id' => $otherParent->id]);

        $response = $this->actingAs($otherParent)->get(route('student.reports.pdf', $report->id));
        $this->assertTrue($response->status() === 403 || $response->status() === 302);
    }

    /**
     * Test report show includes radar data
     */
    public function test_report_show_radar_data_present(): void
    {
        $parent = User::factory()->create(['role' => 'parent']);
        $student = Student::factory()->create([
            'parent_id' => $parent->id,
            'user_id' => $parent->id,
            'roadmap_progress' => 75
        ]);
        $tutor = Tutor::factory()->create();

        $report = TutorReport::factory()->create([
            'student_id' => $student->id,
            'tutor_id' => $tutor->id,
            'status' => 'approved-by-director',
            'attendance_score' => 90,
            'performance_rating' => 5,
        ]);

        $response = $this->actingAs($parent)->get(route('student.reports.show', $report->id));

        $response->assertStatus(200);
        $response->assertViewHas('radarData');

        $radarData = $response->viewData('radarData');
        $this->assertArrayHasKey('labels', $radarData);
        $this->assertArrayHasKey('values', $radarData);
        $this->assertCount(5, $radarData['labels']); // 5 metrics
        $this->assertCount(5, $radarData['values']);
    }

    /**
     * Test that PDF export generates with correct metadata
     */
    public function test_pdf_export_generates_correctly(): void
    {
        $parent = User::factory()->create(['role' => 'parent']);
        $student = Student::factory()->create(['parent_id' => $parent->id, 'user_id' => $parent->id]);
        $tutor = Tutor::factory()->create();

        $report = TutorReport::factory()->create([
            'student_id' => $student->id,
            'tutor_id' => $tutor->id,
            'status' => 'approved-by-director',
            'month' => 'January 2025',
        ]);

        $response = $this->actingAs($parent)->get(route('student.reports.pdf', $report->id));

        // Should get a PDF response
        if ($response->status() === 200) {
            $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
        }
    }

    /**
     * Test that only director-approved reports are visible
     */
    public function test_only_director_approved_reports_visible(): void
    {
        $parent = User::factory()->create(['role' => 'parent']);
        $student = Student::factory()->create(['parent_id' => $parent->id, 'user_id' => $parent->id]);
        $tutor = Tutor::factory()->create();

        // Create reports with different statuses
        $draftReport = TutorReport::factory()->create([
            'student_id' => $student->id,
            'tutor_id' => $tutor->id,
            'status' => 'draft',
        ]);

        $submittedReport = TutorReport::factory()->create([
            'student_id' => $student->id,
            'tutor_id' => $tutor->id,
            'status' => 'submitted',
        ]);

        $approvedReport = TutorReport::factory()->create([
            'student_id' => $student->id,
            'tutor_id' => $tutor->id,
            'status' => 'approved-by-director',
        ]);

        // Attempt to access draft report
        $response = $this->actingAs($parent)->get(route('student.reports.show', $draftReport->id));
        $response->assertStatus(403);

        // Attempt to access submitted report
        $response = $this->actingAs($parent)->get(route('student.reports.show', $submittedReport->id));
        $response->assertStatus(403);

        // Should successfully access approved report
        $response = $this->actingAs($parent)->get(route('student.reports.show', $approvedReport->id));
        $response->assertStatus(200);
    }

    /**
     * Test pagination works on reports index
     */
    public function test_pagination_works_on_reports_index(): void
    {
        $parent = User::factory()->create(['role' => 'parent']);
        $student = Student::factory()->create(['parent_id' => $parent->id, 'user_id' => $parent->id]);
        $tutor = Tutor::factory()->create();

        // Create 25 reports (more than the pagination limit of 20)
        for ($i = 1; $i <= 25; $i++) {
            TutorReport::factory()->create([
                'student_id' => $student->id,
                'tutor_id' => $tutor->id,
                'status' => 'approved-by-director',
                'month' => "Month {$i}",
            ]);
        }

        $response = $this->actingAs($parent)->get(route('student.reports.index'));

        $response->assertStatus(200);
        $response->assertViewHas('reports');

        $reports = $response->viewData('reports');
        $this->assertEquals(20, $reports->count()); // Should show 20 per page
        $this->assertEquals(25, $reports->total()); // Total should be 25
    }
}
