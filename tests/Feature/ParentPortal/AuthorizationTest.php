<?php

namespace Tests\Feature\ParentPortal;

use App\Models\User;
use App\Models\Student;
use App\Models\StudentProgress;
use App\Models\ParentNotification;
use App\Models\TutorReport;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        Role::create(['name' => 'parent', 'description' => 'Parent']);
        Role::create(['name' => 'student', 'description' => 'Student']);
        Role::create(['name' => 'admin', 'description' => 'Admin']);
        Role::create(['name' => 'manager', 'description' => 'Manager']);
        Role::create(['name' => 'director', 'description' => 'Director']);
        Role::create(['name' => 'tutor', 'description' => 'Tutor']);
    }

    /** @test */
    public function parent_can_view_only_linked_students()
    {
        // Create parent user
        $parent = User::factory()->create();
        $parent->roles()->attach(Role::where('name', 'parent')->first());

        // Create two students
        $linkedStudent = Student::factory()->create(['email' => 'linked@student.com']);
        $unlinkedStudent = Student::factory()->create(['email' => 'unlinked@student.com']);

        // Link parent to first student
        $parent->guardiansOf()->attach($linkedStudent, [
            'relationship' => 'parent',
            'primary_contact' => true
        ]);

        // Parent can view linked student
        $this->assertTrue($parent->can('view', $linkedStudent));

        // Parent cannot view unlinked student
        $this->assertFalse($parent->can('view', $unlinkedStudent));
    }

    /** @test */
    public function parent_cannot_access_unlinked_student()
    {
        $parent = User::factory()->create();
        $parent->roles()->attach(Role::where('name', 'parent')->first());

        $unlinkedStudent = Student::factory()->create();

        $this->actingAs($parent)
            ->get(route('parent.students.show', $unlinkedStudent))
            ->assertForbidden();
    }

    /** @test */
    public function student_can_only_see_themself()
    {
        // Create student user
        $student = User::factory()->create(['email' => 'student@test.com']);
        $student->roles()->attach(Role::where('name', 'student')->first());

        // Create student record with same email
        $studentRecord = Student::factory()->create(['email' => 'student@test.com']);
        $otherStudent = Student::factory()->create(['email' => 'other@test.com']);

        // Student can view their own profile
        $this->assertTrue($student->can('view', $studentRecord));

        // Student cannot view other student
        $this->assertFalse($student->can('view', $otherStudent));
    }

    /** @test */
    public function parent_can_view_only_director_approved_reports()
    {
        $parent = User::factory()->create();
        $parent->roles()->attach(Role::where('name', 'parent')->first());

        $student = Student::factory()->create();
        $parent->guardiansOf()->attach($student, [
            'relationship' => 'parent',
            'primary_contact' => true
        ]);

        // Create reports with different statuses
        $draftReport = TutorReport::factory()->create([
            'student_id' => $student->id,
            'status' => 'draft'
        ]);

        $submittedReport = TutorReport::factory()->create([
            'student_id' => $student->id,
            'status' => 'submitted'
        ]);

        $approvedReport = TutorReport::factory()->create([
            'student_id' => $student->id,
            'status' => 'approved-by-director'
        ]);

        // Parent can only view director-approved reports
        $this->assertFalse($parent->can('view', $draftReport));
        $this->assertFalse($parent->can('view', $submittedReport));
        $this->assertTrue($parent->can('view', $approvedReport));
    }

    /** @test */
    public function student_progress_visibility_works()
    {
        $parent = User::factory()->create();
        $parent->roles()->attach(Role::where('name', 'parent')->first());

        $student = Student::factory()->create();
        $parent->guardiansOf()->attach($student, [
            'relationship' => 'parent',
            'primary_contact' => true
        ]);

        $progress = StudentProgress::factory()->create([
            'student_id' => $student->id,
            'title' => 'Complete Module 1',
            'completed' => false
        ]);

        // Parent can view progress for their linked student
        $this->assertTrue($parent->can('view', $progress));
    }

    /** @test */
    public function parent_notifications_visible_only_to_owner()
    {
        $parent1 = User::factory()->create();
        $parent1->roles()->attach(Role::where('name', 'parent')->first());

        $parent2 = User::factory()->create();
        $parent2->roles()->attach(Role::where('name', 'parent')->first());

        $notification1 = ParentNotification::factory()->create([
            'parent_id' => $parent1->id,
            'type' => 'report_ready',
            'data' => ['message' => 'New report available']
        ]);

        $notification2 = ParentNotification::factory()->create([
            'parent_id' => $parent2->id,
            'type' => 'report_ready',
            'data' => ['message' => 'New report available']
        ]);

        // Parent can view their own notification
        $this->assertTrue($parent1->can('view', $notification1));

        // Parent cannot view other parent's notification
        $this->assertFalse($parent1->can('view', $notification2));
    }

    /** @test */
    public function dashboard_loads_for_parent_and_student()
    {
        // Test parent dashboard
        $parent = User::factory()->create();
        $parent->roles()->attach(Role::where('name', 'parent')->first());

        $this->actingAs($parent)
            ->get(route('parent.dashboard'))
            ->assertOk();

        // Test student dashboard
        $student = User::factory()->create(['email' => 'student@test.com']);
        $student->roles()->attach(Role::where('name', 'student')->first());

        Student::factory()->create(['email' => 'student@test.com']);

        $this->actingAs($student)
            ->get(route('student.dashboard'))
            ->assertOk();
    }

    /** @test */
    public function policies_are_enforced_via_authorize()
    {
        $parent = User::factory()->create();
        $parent->roles()->attach(Role::where('name', 'parent')->first());

        $student = Student::factory()->create();

        // Not linked, so should get 403 Forbidden
        $this->actingAs($parent)
            ->get(route('parent.students.show', $student))
            ->assertForbidden();
    }

    /** @test */
    public function unauthenticated_users_blocked()
    {
        $student = Student::factory()->create();

        // Unauthenticated access to parent dashboard
        $this->get(route('parent.dashboard'))
            ->assertRedirect(route('login'));

        // Unauthenticated access to student dashboard
        $this->get(route('student.dashboard'))
            ->assertRedirect(route('login'));

        // Unauthenticated access to student profile
        $this->get(route('parent.students.show', $student))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function non_parent_cannot_access_parent_routes()
    {
        // Create a non-parent user (admin)
        $admin = User::factory()->create();
        $admin->roles()->attach(Role::where('name', 'admin')->first());

        // Admin trying to access parent dashboard should be forbidden
        $this->actingAs($admin)
            ->get(route('parent.dashboard'))
            ->assertForbidden();
    }

    /** @test */
    public function non_student_cannot_access_student_routes()
    {
        // Create a non-student user (admin)
        $admin = User::factory()->create();
        $admin->roles()->attach(Role::where('name', 'admin')->first());

        // Admin trying to access student dashboard should be forbidden
        $this->actingAs($admin)
            ->get(route('student.dashboard'))
            ->assertForbidden();
    }

    /** @test */
    public function tutor_cannot_access_student_parent_portal_reports()
    {
        $tutor = User::factory()->create();
        $tutor->roles()->attach(Role::where('name', 'tutor')->first());

        $student = Student::factory()->create();
        $report = TutorReport::factory()->create([
            'student_id' => $student->id,
            'status' => 'approved-by-director'
        ]);

        // Tutor cannot access via StudentReportPolicy
        $this->assertFalse($tutor->can('view', $report));
    }
}
