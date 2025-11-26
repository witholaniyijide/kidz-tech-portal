<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\TutorReport;
use App\Models\Tutor;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ParentReportAccessTest extends TestCase
{
    use RefreshDatabase;

    protected $parent;
    protected $otherParent;
    protected $student;
    protected $otherStudent;
    protected $tutor;
    protected $draftReport;
    protected $submittedReport;
    protected $managerApprovedReport;
    protected $directorApprovedReport;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        $parentRole = Role::firstOrCreate(['name' => 'parent'], [
            'description' => 'Parent role',
            'permissions' => json_encode(['view_children']),
        ]);

        $tutorRole = Role::firstOrCreate(['name' => 'tutor'], [
            'description' => 'Tutor role',
            'permissions' => json_encode(['create_reports']),
        ]);

        // Create parents
        $this->parent = User::factory()->create([
            'name' => 'Parent User',
            'email' => 'parent@test.com',
        ]);
        $this->parent->roles()->attach($parentRole);

        $this->otherParent = User::factory()->create([
            'name' => 'Other Parent',
            'email' => 'otherparent@test.com',
        ]);
        $this->otherParent->roles()->attach($parentRole);

        // Create tutor
        $this->tutor = Tutor::factory()->create([
            'email' => 'tutor@test.com',
            'first_name' => 'Test',
            'last_name' => 'Tutor',
            'status' => 'active',
        ]);

        // Create students
        $this->student = Student::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'Student',
            'parent_id' => $this->parent->id,
            'tutor_id' => $this->tutor->id,
            'status' => 'active',
            'date_of_birth' => now()->subYears(10),
        ]);

        $this->otherStudent = Student::factory()->create([
            'first_name' => 'Other',
            'last_name' => 'Student',
            'parent_id' => $this->otherParent->id,
            'tutor_id' => $this->tutor->id,
            'status' => 'active',
            'date_of_birth' => now()->subYears(10),
        ]);

        // Create reports with different statuses
        $this->draftReport = TutorReport::factory()->create([
            'tutor_id' => $this->tutor->id,
            'student_id' => $this->student->id,
            'month' => now()->format('Y-m'),
            'status' => 'draft',
            'progress_summary' => 'Draft report',
            'strengths' => 'None yet',
            'weaknesses' => 'Not submitted',
            'next_steps' => 'Submit report',
        ]);

        $this->submittedReport = TutorReport::factory()->create([
            'tutor_id' => $this->tutor->id,
            'student_id' => $this->student->id,
            'month' => now()->subMonth()->format('Y-m'),
            'status' => 'submitted',
            'progress_summary' => 'Submitted report',
            'strengths' => 'Submitted',
            'weaknesses' => 'Awaiting review',
            'next_steps' => 'Manager review',
            'submitted_at' => now()->subDays(5),
        ]);

        $this->managerApprovedReport = TutorReport::factory()->create([
            'tutor_id' => $this->tutor->id,
            'student_id' => $this->student->id,
            'month' => now()->subMonths(2)->format('Y-m'),
            'status' => 'approved-by-manager',
            'progress_summary' => 'Manager approved report',
            'strengths' => 'Good progress',
            'weaknesses' => 'Some areas to improve',
            'next_steps' => 'Continue',
            'manager_comment' => 'Approved by manager',
            'submitted_at' => now()->subDays(10),
            'approved_by_manager_at' => now()->subDays(8),
        ]);

        $this->directorApprovedReport = TutorReport::factory()->create([
            'tutor_id' => $this->tutor->id,
            'student_id' => $this->student->id,
            'month' => now()->subMonths(3)->format('Y-m'),
            'status' => 'approved-by-director',
            'progress_summary' => 'Director approved report',
            'strengths' => 'Excellent progress',
            'weaknesses' => 'Minor improvements needed',
            'next_steps' => 'Keep up the good work',
            'manager_comment' => 'Approved by manager',
            'director_comment' => 'Final approval granted',
            'submitted_at' => now()->subDays(15),
            'approved_by_manager_at' => now()->subDays(13),
            'approved_by_director_at' => now()->subDays(10),
        ]);
    }

    /** @test */
    public function parent_can_see_only_director_approved_reports()
    {
        $this->actingAs($this->parent);

        $response = $this->get(route('parent.reports.index', $this->student));

        $response->assertStatus(200);
        $response->assertSee($this->directorApprovedReport->progress_summary);
        $response->assertDontSee($this->draftReport->progress_summary);
        $response->assertDontSee($this->submittedReport->progress_summary);
        $response->assertDontSee($this->managerApprovedReport->progress_summary);
    }

    /** @test */
    public function parent_cannot_see_draft_reports()
    {
        $this->actingAs($this->parent);

        $response = $this->get(route('parent.reports.show', [$this->student, $this->draftReport]));

        $response->assertStatus(403);
    }

    /** @test */
    public function parent_cannot_see_submitted_reports()
    {
        $this->actingAs($this->parent);

        $response = $this->get(route('parent.reports.show', [$this->student, $this->submittedReport]));

        $response->assertStatus(403);
    }

    /** @test */
    public function parent_cannot_see_manager_approved_reports()
    {
        $this->actingAs($this->parent);

        $response = $this->get(route('parent.reports.show', [$this->student, $this->managerApprovedReport]));

        $response->assertStatus(403);
    }

    /** @test */
    public function parent_can_see_director_approved_report_details()
    {
        $this->actingAs($this->parent);

        $response = $this->get(route('parent.reports.show', [$this->student, $this->directorApprovedReport]));

        $response->assertStatus(200);
        $response->assertSee($this->directorApprovedReport->progress_summary);
        $response->assertSee($this->directorApprovedReport->strengths);
        $response->assertSee($this->directorApprovedReport->weaknesses);
        $response->assertSee($this->directorApprovedReport->next_steps);
    }

    /** @test */
    public function parent_cannot_access_reports_of_other_children()
    {
        $this->actingAs($this->parent);

        // Create a report for the other student
        $otherReport = TutorReport::factory()->create([
            'tutor_id' => $this->tutor->id,
            'student_id' => $this->otherStudent->id,
            'month' => now()->format('Y-m'),
            'status' => 'approved-by-director',
            'progress_summary' => 'Other student report',
            'strengths' => 'Good',
            'weaknesses' => 'Some',
            'next_steps' => 'Continue',
            'approved_by_director_at' => now(),
        ]);

        // Try to access other student's reports
        $response = $this->get(route('parent.reports.index', $this->otherStudent));
        $response->assertStatus(403);

        // Try to access specific report
        $response = $this->get(route('parent.reports.show', [$this->otherStudent, $otherReport]));
        $response->assertStatus(403);
    }

    /** @test */
    public function parent_cannot_access_report_not_belonging_to_student()
    {
        $this->actingAs($this->parent);

        // Try to view a report that belongs to another student
        $otherReport = TutorReport::factory()->create([
            'tutor_id' => $this->tutor->id,
            'student_id' => $this->otherStudent->id,
            'month' => now()->format('Y-m'),
            'status' => 'approved-by-director',
            'progress_summary' => 'Other student report',
            'strengths' => 'Good',
            'weaknesses' => 'Some',
            'next_steps' => 'Continue',
            'approved_by_director_at' => now(),
        ]);

        // Try to access it through their student's URL (mismatch)
        $response = $this->get(route('parent.reports.show', [$this->student, $otherReport]));
        $response->assertStatus(403);
    }

    /** @test */
    public function unauthenticated_user_cannot_access_parent_reports()
    {
        $response = $this->get(route('parent.reports.index', $this->student));
        $response->assertRedirect(route('login'));

        $response = $this->get(route('parent.reports.show', [$this->student, $this->directorApprovedReport]));
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function approved_reports_relationship_only_returns_director_approved()
    {
        $approvedReports = $this->student->approvedReports()->get();

        $this->assertCount(1, $approvedReports);
        $this->assertTrue($approvedReports->contains($this->directorApprovedReport));
        $this->assertFalse($approvedReports->contains($this->draftReport));
        $this->assertFalse($approvedReports->contains($this->submittedReport));
        $this->assertFalse($approvedReports->contains($this->managerApprovedReport));
    }
}
