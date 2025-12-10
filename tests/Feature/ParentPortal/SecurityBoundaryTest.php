<?php

namespace Tests\Feature\ParentPortal;

use App\Models\Role;
use App\Models\Student;
use App\Models\TutorReport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityBoundaryTest extends TestCase
{
    use RefreshDatabase;

    protected User $parent1;
    protected User $parent2;
    protected Student $student1;
    protected Student $student2;
    protected Role $parentRole;

    protected function setUp(): void
    {
        parent::setUp();

        // Create parent role
        $this->parentRole = Role::create(['name' => 'parent']);

        // Create two parents
        $this->parent1 = User::factory()->create(['email' => 'parent1@example.com']);
        $this->parent1->roles()->attach($this->parentRole);

        $this->parent2 = User::factory()->create(['email' => 'parent2@example.com']);
        $this->parent2->roles()->attach($this->parentRole);

        // Create two students
        $this->student1 = Student::factory()->create([
            'first_name' => 'Student',
            'last_name' => 'One',
            'email' => 'student1@example.com',
        ]);

        $this->student2 = Student::factory()->create([
            'first_name' => 'Student',
            'last_name' => 'Two',
            'email' => 'student2@example.com',
        ]);

        // Link parent1 to student1 via guardian relationship
        $this->student1->guardians()->attach($this->parent1->id, [
            'relationship' => 'parent',
            'primary_contact' => true,
        ]);

        // Link parent2 to student2 via guardian relationship
        $this->student2->guardians()->attach($this->parent2->id, [
            'relationship' => 'parent',
            'primary_contact' => true,
        ]);
    }

    /** @test */
    public function parent_cannot_view_unlinked_student_reports()
    {
        // Parent1 tries to view student2's reports
        $response = $this->actingAs($this->parent1)
            ->get(route('parent.students.reports', $this->student2));

        $response->assertStatus(403);
    }

    /** @test */
    public function parent_can_view_own_students_reports()
    {
        // Parent1 views student1's reports
        $response = $this->actingAs($this->parent1)
            ->get(route('parent.students.reports', $this->student1));

        $response->assertOk();
    }

    /** @test */
    public function parent_cannot_view_another_parents_student_report_details()
    {
        // Create a report for student2
        $report = TutorReport::factory()->create([
            'student_id' => $this->student2->id,
            'status' => 'approved-by-director',
        ]);

        // Parent1 tries to view student2's report
        $response = $this->actingAs($this->parent1)
            ->get(route('parent.students.reports.show', [$this->student2, $report]));

        $response->assertStatus(403);
    }

    /** @test */
    public function parent_can_view_own_students_report_details()
    {
        // Create a report for student1
        $report = TutorReport::factory()->create([
            'student_id' => $this->student1->id,
            'status' => 'approved-by-director',
        ]);

        // Parent1 views student1's report
        $response = $this->actingAs($this->parent1)
            ->get(route('parent.students.reports.show', [$this->student1, $report]));

        $response->assertOk();
    }

    /** @test */
    public function parent_cannot_access_student_settings()
    {
        // Parents should not be able to access the student settings route
        $studentRole = Role::create(['name' => 'student']);
        $studentUser = User::factory()->create(['email' => $this->student1->email]);
        $studentUser->roles()->attach($studentRole);

        // Parent tries to access student settings
        $response = $this->actingAs($this->parent1)
            ->get(route('student.settings.index'));

        $response->assertStatus(403);
    }

    /** @test */
    public function parent_can_only_view_director_approved_reports()
    {
        // Create a non-approved report for student1
        $pendingReport = TutorReport::factory()->create([
            'student_id' => $this->student1->id,
            'status' => 'pending',
        ]);

        // Create an approved report for student1
        $approvedReport = TutorReport::factory()->create([
            'student_id' => $this->student1->id,
            'status' => 'approved-by-director',
        ]);

        // Parent1 tries to view pending report - should fail
        $response = $this->actingAs($this->parent1)
            ->get(route('parent.students.reports.show', [$this->student1, $pendingReport]));

        $response->assertStatus(403);

        // Parent1 views approved report - should succeed
        $response = $this->actingAs($this->parent1)
            ->get(route('parent.students.reports.show', [$this->student1, $approvedReport]));

        $response->assertOk();
    }

    /** @test */
    public function parent_cannot_view_student_profile_of_unlinked_student()
    {
        // Parent1 tries to view student2's profile
        $response = $this->actingAs($this->parent1)
            ->get(route('parent.students.show', $this->student2));

        $response->assertStatus(403);
    }

    /** @test */
    public function parent_can_view_own_students_profile()
    {
        // Parent1 views student1's profile
        $response = $this->actingAs($this->parent1)
            ->get(route('parent.students.show', $this->student1));

        $response->assertOk();
    }

    /** @test */
    public function parent_cannot_view_progress_of_unlinked_student()
    {
        // Parent1 tries to view student2's progress
        $response = $this->actingAs($this->parent1)
            ->get(route('parent.students.progress', $this->student2));

        $response->assertStatus(403);
    }

    /** @test */
    public function parent_with_multiple_children_can_access_all_their_students()
    {
        // Link student2 to parent1 as well
        $this->student2->guardians()->attach($this->parent1->id, [
            'relationship' => 'parent',
            'primary_contact' => false,
        ]);

        // Parent1 should now be able to access both students
        $response1 = $this->actingAs($this->parent1)
            ->get(route('parent.students.show', $this->student1));
        $response1->assertOk();

        $response2 = $this->actingAs($this->parent1)
            ->get(route('parent.students.show', $this->student2));
        $response2->assertOk();
    }
}
