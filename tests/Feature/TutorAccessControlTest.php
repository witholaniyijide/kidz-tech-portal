<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\Tutor;
use App\Models\TutorReport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TutorAccessControlTest extends TestCase
{
    use RefreshDatabase;

    protected User $tutor1User;
    protected Tutor $tutor1;
    protected User $tutor2User;
    protected Tutor $tutor2;
    protected Student $student1;
    protected Student $student2;
    protected TutorReport $report1;
    protected TutorReport $report2;

    protected function setUp(): void
    {
        parent::setUp();

        // Create first tutor
        $this->tutor1User = User::factory()->create();
        $this->tutor1User->assignRole('tutor');
        $this->tutor1 = Tutor::factory()->create(['email' => $this->tutor1User->email]);

        // Create second tutor
        $this->tutor2User = User::factory()->create();
        $this->tutor2User->assignRole('tutor');
        $this->tutor2 = Tutor::factory()->create(['email' => $this->tutor2User->email]);

        // Create students
        $this->student1 = Student::factory()->create(['tutor_id' => $this->tutor1->id]);
        $this->student2 = Student::factory()->create(['tutor_id' => $this->tutor2->id]);

        // Create reports
        $this->report1 = TutorReport::factory()->create([
            'tutor_id' => $this->tutor1->id,
            'student_id' => $this->student1->id,
            'created_by' => $this->tutor1User->id,
        ]);

        $this->report2 = TutorReport::factory()->create([
            'tutor_id' => $this->tutor2->id,
            'student_id' => $this->student2->id,
            'created_by' => $this->tutor2User->id,
        ]);
    }

    /** @test */
    public function tutor_can_access_their_own_dashboard()
    {
        $response = $this->actingAs($this->tutor1User)->get(route('tutor.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('tutor.dashboard');
    }

    /** @test */
    public function tutor_can_view_their_own_reports()
    {
        $response = $this->actingAs($this->tutor1User)->get(route('tutor.reports.index'));

        $response->assertStatus(200);
        $response->assertSee($this->report1->title);
    }

    /** @test */
    public function tutor_cannot_view_other_tutors_reports()
    {
        $response = $this->actingAs($this->tutor1User)->get(route('tutor.reports.show', $this->report2));

        $response->assertStatus(403);
    }

    /** @test */
    public function tutor_cannot_edit_other_tutors_reports()
    {
        $response = $this->actingAs($this->tutor1User)->get(route('tutor.reports.edit', $this->report2));

        $response->assertStatus(403);
    }

    /** @test */
    public function tutor_can_only_see_their_assigned_students()
    {
        $response = $this->actingAs($this->tutor1User)->get(route('tutor.students.index'));

        $response->assertStatus(200);
        $response->assertSee($this->student1->first_name);
        $response->assertDontSee($this->student2->first_name);
    }

    /** @test */
    public function tutor_cannot_view_other_tutors_students()
    {
        $response = $this->actingAs($this->tutor1User)->get(route('tutor.students.show', $this->student2));

        $response->assertStatus(403);
    }

    /** @test */
    public function tutor_can_create_reports_for_their_assigned_students_only()
    {
        $reportData = [
            'student_id' => $this->student1->id,
            'month' => now()->format('Y-m'),
            'progress_summary' => 'Test progress summary',
            'strengths' => 'Test strengths',
            'weaknesses' => 'Test areas for improvement',
            'next_steps' => 'Test next steps',
            'attendance_score' => 90,
            'performance_rating' => 'excellent',
            'status' => 'draft',
        ];

        $response = $this->actingAs($this->tutor1User)->post(route('tutor.reports.store'), $reportData);

        $response->assertStatus(302);
        $this->assertDatabaseHas('tutor_reports', [
            'student_id' => $this->student1->id,
            'tutor_id' => $this->tutor1->id,
        ]);
    }

    /** @test */
    public function tutor_cannot_create_reports_for_other_tutors_students()
    {
        $reportData = [
            'student_id' => $this->student2->id,
            'month' => now()->format('Y-m'),
            'progress_summary' => 'Test progress summary',
            'strengths' => 'Test strengths',
            'weaknesses' => 'Test areas for improvement',
            'next_steps' => 'Test next steps',
            'attendance_score' => 90,
            'performance_rating' => 'excellent',
            'status' => 'draft',
        ];

        $response = $this->actingAs($this->tutor1User)->post(route('tutor.reports.store'), $reportData);

        $response->assertStatus(403);
    }

    /** @test */
    public function tutor_can_edit_their_own_draft_reports()
    {
        $this->report1->update(['status' => 'draft']);

        $response = $this->actingAs($this->tutor1User)->get(route('tutor.reports.edit', $this->report1));

        $response->assertStatus(200);
    }

    /** @test */
    public function tutor_cannot_edit_submitted_reports()
    {
        $this->report1->update(['status' => 'submitted']);

        $response = $this->actingAs($this->tutor1User)->get(route('tutor.reports.edit', $this->report1));

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    /** @test */
    public function tutor_can_delete_their_own_draft_reports()
    {
        $this->report1->update(['status' => 'draft']);

        $response = $this->actingAs($this->tutor1User)->delete(route('tutor.reports.destroy', $this->report1));

        $response->assertStatus(302);
        $this->assertDatabaseMissing('tutor_reports', [
            'id' => $this->report1->id,
        ]);
    }

    /** @test */
    public function tutor_cannot_delete_submitted_reports()
    {
        $this->report1->update(['status' => 'submitted']);

        $response = $this->actingAs($this->tutor1User)->delete(route('tutor.reports.destroy', $this->report1));

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('tutor_reports', [
            'id' => $this->report1->id,
        ]);
    }

    /** @test */
    public function tutor_can_export_their_own_reports_as_pdf()
    {
        $response = $this->actingAs($this->tutor1User)->get(route('tutor.reports.pdf', $this->report1));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    /** @test */
    public function tutor_cannot_export_other_tutors_reports_as_pdf()
    {
        $response = $this->actingAs($this->tutor1User)->get(route('tutor.reports.pdf', $this->report2));

        $response->assertStatus(403);
    }

    /** @test */
    public function non_tutor_user_cannot_access_tutor_routes()
    {
        $regularUser = User::factory()->create();
        $regularUser->assignRole('parent');

        $response = $this->actingAs($regularUser)->get(route('tutor.dashboard'));

        $response->assertStatus(403);
    }

    /** @test */
    public function guest_cannot_access_tutor_routes()
    {
        $response = $this->get(route('tutor.dashboard'));

        $response->assertRedirect(route('login'));
    }
}
