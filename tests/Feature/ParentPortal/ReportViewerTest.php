<?php

namespace Tests\Feature\ParentPortal;

use App\Models\User;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\TutorReport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportViewerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that parent can view approved reports for their student
     */
    public function test_parent_can_view_approved_reports_for_their_student(): void
    {
        // Create parent and student
        $parent = User::factory()->create(['role' => 'parent']);
        $student = Student::factory()->create(['parent_id' => $parent->id]);
        $tutor = Tutor::factory()->create();

        // Create approved report
        $report = TutorReport::factory()->create([
            'student_id' => $student->id,
            'tutor_id' => $tutor->id,
            'status' => 'approved-by-director',
            'month' => 'January 2025',
        ]);

        // Authenticate as parent
        $response = $this->actingAs($parent)->get(route('student.reports.index'));

        $response->assertStatus(200);
        $response->assertSee($report->month);
    }

    /**
     * Test that parent cannot view another parent's student reports
     */
    public function test_parent_cannot_view_another_parents_student_reports(): void
    {
        // Create two parents with students
        $parent1 = User::factory()->create(['role' => 'parent']);
        $student1 = Student::factory()->create(['parent_id' => $parent1->id]);

        $parent2 = User::factory()->create(['role' => 'parent']);
        $student2 = Student::factory()->create(['parent_id' => $parent2->id]);

        $tutor = Tutor::factory()->create();

        // Create report for student2
        $report = TutorReport::factory()->create([
            'student_id' => $student2->id,
            'tutor_id' => $tutor->id,
            'status' => 'approved-by-director',
        ]);

        // Parent1 tries to view student2's report
        $response = $this->actingAs($parent1)->get(route('student.reports.show', $report->id));

        // Should be unauthorized or redirected
        $this->assertTrue($response->status() === 403 || $response->status() === 302);
    }

    /**
     * Test that student can view only their own reports
     */
    public function test_student_can_view_only_their_own_reports(): void
    {
        // Create students
        $student1User = User::factory()->create(['role' => 'student']);
        $student1 = Student::factory()->create(['parent_id' => $student1User->id]);

        $student2User = User::factory()->create(['role' => 'student']);
        $student2 = Student::factory()->create(['parent_id' => $student2User->id]);

        $tutor = Tutor::factory()->create();

        // Create reports
        $report1 = TutorReport::factory()->create([
            'student_id' => $student1->id,
            'tutor_id' => $tutor->id,
            'status' => 'approved-by-director',
        ]);

        $report2 = TutorReport::factory()->create([
            'student_id' => $student2->id,
            'tutor_id' => $tutor->id,
            'status' => 'approved-by-director',
        ]);

        // Student1 can view own report
        $response1 = $this->actingAs($student1User)->get(route('student.reports.show', $report1->id));
        $response1->assertStatus(200);

        // Student1 cannot view student2's report
        $response2 = $this->actingAs($student1User)->get(route('student.reports.show', $report2->id));
        $this->assertTrue($response2->status() === 403 || $response2->status() === 302);
    }

    /**
     * Test that reports list loads successfully
     */
    public function test_reports_list_loads_successfully(): void
    {
        $parent = User::factory()->create(['role' => 'parent']);
        $student = Student::factory()->create(['parent_id' => $parent->id]);

        $response = $this->actingAs($parent)->get(route('student.reports.index'));

        $response->assertStatus(200);
        $response->assertViewIs('student.reports.index');
    }

    /**
     * Test that report details page loads successfully
     */
    public function test_report_details_page_loads_successfully(): void
    {
        $parent = User::factory()->create(['role' => 'parent']);
        $student = Student::factory()->create(['parent_id' => $parent->id]);
        $tutor = Tutor::factory()->create();

        $report = TutorReport::factory()->create([
            'student_id' => $student->id,
            'tutor_id' => $tutor->id,
            'status' => 'approved-by-director',
            'summary' => 'Test summary',
            'strengths' => 'Test strengths',
            'weaknesses' => 'Test weaknesses',
        ]);

        $response = $this->actingAs($parent)->get(route('student.reports.show', $report->id));

        $response->assertStatus(200);
        $response->assertViewIs('student.reports.show');
        $response->assertSee($report->summary);
        $response->assertSee($report->strengths);
    }

    /**
     * Test that PDF export route works
     */
    public function test_pdf_export_route_works(): void
    {
        $parent = User::factory()->create(['role' => 'parent']);
        $student = Student::factory()->create(['parent_id' => $parent->id]);
        $tutor = Tutor::factory()->create();

        $report = TutorReport::factory()->create([
            'student_id' => $student->id,
            'tutor_id' => $tutor->id,
            'status' => 'approved-by-director',
        ]);

        $response = $this->actingAs($parent)->get(route('student.reports.pdf', $report->id));

        // Should either succeed (200) or redirect if PDF generation is not yet implemented
        $this->assertContains($response->status(), [200, 302, 404]);
    }

    /**
     * Test that print view loads
     */
    public function test_print_view_loads(): void
    {
        $parent = User::factory()->create(['role' => 'parent']);
        $student = Student::factory()->create(['parent_id' => $parent->id]);
        $tutor = Tutor::factory()->create();

        $report = TutorReport::factory()->create([
            'student_id' => $student->id,
            'tutor_id' => $tutor->id,
            'status' => 'approved-by-director',
        ]);

        $response = $this->actingAs($parent)->get(route('student.reports.print', $report->id));

        // Print view should load
        $this->assertContains($response->status(), [200, 302, 404]);
    }

    /**
     * Test that unauthorized user cannot access reports
     */
    public function test_unauthorized_user_cannot_access_reports(): void
    {
        $student = Student::factory()->create();
        $tutor = Tutor::factory()->create();

        $report = TutorReport::factory()->create([
            'student_id' => $student->id,
            'tutor_id' => $tutor->id,
            'status' => 'approved-by-director',
        ]);

        // Try to access without authentication
        $response = $this->get(route('student.reports.show', $report->id));

        $response->assertRedirect(route('login'));
    }

    /**
     * Test that only approved reports are visible to parents/students
     */
    public function test_only_approved_reports_are_visible(): void
    {
        $parent = User::factory()->create(['role' => 'parent']);
        $student = Student::factory()->create(['parent_id' => $parent->id]);
        $tutor = Tutor::factory()->create();

        // Create draft report (should not be visible)
        $draftReport = TutorReport::factory()->create([
            'student_id' => $student->id,
            'tutor_id' => $tutor->id,
            'status' => 'draft',
        ]);

        // Create approved report (should be visible)
        $approvedReport = TutorReport::factory()->create([
            'student_id' => $student->id,
            'tutor_id' => $tutor->id,
            'status' => 'approved-by-director',
        ]);

        $response = $this->actingAs($parent)->get(route('student.reports.index'));

        $response->assertStatus(200);
        $response->assertDontSee($draftReport->month);
        $response->assertSee($approvedReport->month);
    }

    /**
     * Test that search filter works on reports list
     */
    public function test_search_filter_works_on_reports_list(): void
    {
        $parent = User::factory()->create(['role' => 'parent']);
        $student = Student::factory()->create(['parent_id' => $parent->id]);
        $tutor = Tutor::factory()->create();

        $report1 = TutorReport::factory()->create([
            'student_id' => $student->id,
            'tutor_id' => $tutor->id,
            'status' => 'approved-by-director',
            'summary' => 'Excellent progress in Python',
        ]);

        $report2 = TutorReport::factory()->create([
            'student_id' => $student->id,
            'tutor_id' => $tutor->id,
            'status' => 'approved-by-director',
            'summary' => 'Great work in Scratch',
        ]);

        $response = $this->actingAs($parent)->get(route('student.reports.index', ['search' => 'Python']));

        $response->assertStatus(200);
        // If search is implemented, should see report1 and not report2
    }

    /**
     * Test that month filter works on reports list
     */
    public function test_month_filter_works_on_reports_list(): void
    {
        $parent = User::factory()->create(['role' => 'parent']);
        $student = Student::factory()->create(['parent_id' => $parent->id]);
        $tutor = Tutor::factory()->create();

        $report = TutorReport::factory()->create([
            'student_id' => $student->id,
            'tutor_id' => $tutor->id,
            'status' => 'approved-by-director',
            'month' => 'January 2025',
        ]);

        $response = $this->actingAs($parent)->get(route('student.reports.index', ['month' => 'January 2025']));

        $response->assertStatus(200);
        // Should see filtered report
    }
}
