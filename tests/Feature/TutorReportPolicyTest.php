<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\TutorReport;
use App\Models\Tutor;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TutorReportPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected $tutorUser;
    protected $managerUser;
    protected $directorUser;
    protected $adminUser;
    protected $tutorRole;
    protected $managerRole;
    protected $directorRole;
    protected $adminRole;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        $this->tutorRole = Role::create(['name' => 'tutor', 'display_name' => 'Tutor']);
        $this->managerRole = Role::create(['name' => 'manager', 'display_name' => 'Manager']);
        $this->directorRole = Role::create(['name' => 'director', 'display_name' => 'Director']);
        $this->adminRole = Role::create(['name' => 'admin', 'display_name' => 'Admin']);

        // Create users with different roles
        $this->tutorUser = User::factory()->create();
        $this->tutorUser->roles()->attach($this->tutorRole);

        $this->managerUser = User::factory()->create();
        $this->managerUser->roles()->attach($this->managerRole);

        $this->directorUser = User::factory()->create();
        $this->directorUser->roles()->attach($this->directorRole);

        $this->adminUser = User::factory()->create();
        $this->adminUser->roles()->attach($this->adminRole);
    }

    /** @test */
    public function tutor_can_view_own_reports()
    {
        $tutor = Tutor::factory()->create();
        $report = TutorReport::factory()->create([
            'tutor_id' => $tutor->id,
            'created_by' => $this->tutorUser->id,
        ]);

        $this->assertTrue($this->tutorUser->can('view', $report));
    }

    /** @test */
    public function tutor_cannot_view_other_tutors_reports()
    {
        $otherUser = User::factory()->create();
        $otherUser->roles()->attach($this->tutorRole);

        $report = TutorReport::factory()->create([
            'created_by' => $otherUser->id,
        ]);

        $this->assertFalse($this->tutorUser->can('view', $report));
    }

    /** @test */
    public function manager_can_view_all_reports()
    {
        $report = TutorReport::factory()->create([
            'created_by' => $this->tutorUser->id,
        ]);

        $this->assertTrue($this->managerUser->can('view', $report));
    }

    /** @test */
    public function director_can_view_all_reports()
    {
        $report = TutorReport::factory()->create([
            'created_by' => $this->tutorUser->id,
        ]);

        $this->assertTrue($this->directorUser->can('view', $report));
    }

    /** @test */
    public function admin_can_view_all_reports()
    {
        $report = TutorReport::factory()->create([
            'created_by' => $this->tutorUser->id,
        ]);

        $this->assertTrue($this->adminUser->can('view', $report));
    }

    /** @test */
    public function tutor_can_create_reports()
    {
        $this->assertTrue($this->tutorUser->can('create', TutorReport::class));
    }

    /** @test */
    public function admin_can_create_reports()
    {
        $this->assertTrue($this->adminUser->can('create', TutorReport::class));
    }

    /** @test */
    public function manager_cannot_create_reports()
    {
        $this->assertFalse($this->managerUser->can('create', TutorReport::class));
    }

    /** @test */
    public function tutor_can_update_own_draft_report()
    {
        $report = TutorReport::factory()->create([
            'created_by' => $this->tutorUser->id,
            'status' => 'draft',
        ]);

        $this->assertTrue($this->tutorUser->can('update', $report));
    }

    /** @test */
    public function tutor_cannot_update_submitted_report()
    {
        $report = TutorReport::factory()->create([
            'created_by' => $this->tutorUser->id,
            'status' => 'submitted',
        ]);

        $this->assertFalse($this->tutorUser->can('update', $report));
    }

    /** @test */
    public function tutor_can_submit_own_draft_report()
    {
        $report = TutorReport::factory()->create([
            'created_by' => $this->tutorUser->id,
            'status' => 'draft',
        ]);

        $this->assertTrue($this->tutorUser->can('submit', $report));
    }

    /** @test */
    public function tutor_cannot_submit_already_submitted_report()
    {
        $report = TutorReport::factory()->create([
            'created_by' => $this->tutorUser->id,
            'status' => 'submitted',
        ]);

        $this->assertFalse($this->tutorUser->can('submit', $report));
    }

    /** @test */
    public function tutor_can_comment_on_own_report()
    {
        $report = TutorReport::factory()->create([
            'created_by' => $this->tutorUser->id,
        ]);

        $this->assertTrue($this->tutorUser->can('comment', $report));
    }

    /** @test */
    public function manager_can_comment_on_any_report()
    {
        $report = TutorReport::factory()->create([
            'created_by' => $this->tutorUser->id,
        ]);

        $this->assertTrue($this->managerUser->can('comment', $report));
    }

    /** @test */
    public function director_can_comment_on_any_report()
    {
        $report = TutorReport::factory()->create([
            'created_by' => $this->tutorUser->id,
        ]);

        $this->assertTrue($this->directorUser->can('comment', $report));
    }

    /** @test */
    public function manager_can_approve_submitted_report()
    {
        $report = TutorReport::factory()->create([
            'created_by' => $this->tutorUser->id,
            'status' => 'submitted',
        ]);

        $this->assertTrue($this->managerUser->can('approve', $report));
    }

    /** @test */
    public function manager_cannot_approve_draft_report()
    {
        $report = TutorReport::factory()->create([
            'created_by' => $this->tutorUser->id,
            'status' => 'draft',
        ]);

        $this->assertFalse($this->managerUser->can('approve', $report));
    }

    /** @test */
    public function director_can_approve_manager_reviewed_report()
    {
        $report = TutorReport::factory()->create([
            'created_by' => $this->tutorUser->id,
            'status' => 'manager_review',
        ]);

        $this->assertTrue($this->directorUser->can('approve', $report));
    }

    /** @test */
    public function director_cannot_approve_submitted_report()
    {
        $report = TutorReport::factory()->create([
            'created_by' => $this->tutorUser->id,
            'status' => 'submitted',
        ]);

        $this->assertFalse($this->directorUser->can('approve', $report));
    }

    /** @test */
    public function tutor_can_delete_own_draft_report()
    {
        $report = TutorReport::factory()->create([
            'created_by' => $this->tutorUser->id,
            'status' => 'draft',
        ]);

        $this->assertTrue($this->tutorUser->can('delete', $report));
    }

    /** @test */
    public function tutor_cannot_delete_submitted_report()
    {
        $report = TutorReport::factory()->create([
            'created_by' => $this->tutorUser->id,
            'status' => 'submitted',
        ]);

        $this->assertFalse($this->tutorUser->can('delete', $report));
    }

    /** @test */
    public function admin_can_delete_any_report()
    {
        $report = TutorReport::factory()->create([
            'created_by' => $this->tutorUser->id,
            'status' => 'submitted',
        ]);

        $this->assertTrue($this->adminUser->can('delete', $report));
    }

    /** @test */
    public function tutor_create_report_gate_works()
    {
        $this->actingAs($this->tutorUser);
        $this->assertTrue(\Gate::allows('tutor-create-report'));
    }

    /** @test */
    public function manager_approve_report_gate_works()
    {
        $this->actingAs($this->managerUser);
        $this->assertTrue(\Gate::allows('tutor-approve-report'));
    }

    /** @test */
    public function attendance_approve_gate_works_for_manager()
    {
        $this->actingAs($this->managerUser);
        $this->assertTrue(\Gate::allows('attendance-approve'));
    }
}
