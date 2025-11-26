<?php

namespace Tests\Feature\Director;

use App\Models\User;
use App\Models\Role;
use App\Models\TutorAssessment;
use App\Models\Tutor;
use App\Models\DirectorActivityLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DirectorAssessmentWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected $director;
    protected $manager;
    protected $tutor;
    protected $assessment;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        $directorRole = Role::firstOrCreate(['name' => 'director'], [
            'description' => 'Director role',
        ]);

        $managerRole = Role::firstOrCreate(['name' => 'manager'], [
            'description' => 'Manager role',
        ]);

        // Create users
        $this->director = User::factory()->create(['email' => 'director@test.com']);
        $this->director->roles()->attach($directorRole);

        $this->manager = User::factory()->create(['email' => 'manager@test.com']);
        $this->manager->roles()->attach($managerRole);

        // Create tutor
        $this->tutor = Tutor::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'Tutor',
            'status' => 'active',
        ]);

        // Create a manager-approved assessment
        $this->assessment = TutorAssessment::factory()->create([
            'tutor_id' => $this->tutor->id,
            'manager_id' => $this->manager->id,
            'assessment_month' => now()->format('Y-m'),
            'status' => 'approved-by-manager',
            'strengths' => 'Good teaching skills',
            'weaknesses' => 'Time management',
            'recommendations' => 'Continue professional development',
            'manager_comment' => 'Approved by manager',
            'approved_by_manager_at' => now()->subDay(),
        ]);
    }

    /** @test */
    public function director_can_view_pending_assessments()
    {
        $response = $this->actingAs($this->director)
            ->get(route('director.assessments.index'));

        $response->assertStatus(200);
        $response->assertSee($this->tutor->first_name);
    }

    /** @test */
    public function director_can_view_single_assessment()
    {
        $response = $this->actingAs($this->director)
            ->get(route('director.assessments.show', $this->assessment));

        $response->assertStatus(200);
        $response->assertSee($this->assessment->strengths);
    }

    /** @test */
    public function director_can_approve_assessment()
    {
        $response = $this->actingAs($this->director)
            ->post(route('director.assessments.approve', $this->assessment), [
                'director_comment' => 'Approved',
            ]);

        $response->assertRedirect(route('director.assessments.index'));
        $response->assertSessionHas('success');

        $this->assessment->refresh();
        $this->assertEquals('approved-by-director', $this->assessment->status);
        $this->assertNotNull($this->assessment->approved_by_director_at);
    }

    /** @test */
    public function director_can_approve_submitted_assessment()
    {
        $submittedAssessment = TutorAssessment::factory()->create([
            'tutor_id' => $this->tutor->id,
            'manager_id' => $this->manager->id,
            'status' => 'submitted',
        ]);

        $response = $this->actingAs($this->director)
            ->post(route('director.assessments.approve', $submittedAssessment), [
                'director_comment' => 'Approved',
            ]);

        $response->assertRedirect(route('director.assessments.index'));
        $response->assertSessionHas('success');

        $submittedAssessment->refresh();
        $this->assertEquals('approved-by-director', $submittedAssessment->status);
    }

    /** @test */
    public function director_cannot_approve_draft_assessments()
    {
        $draftAssessment = TutorAssessment::factory()->create([
            'tutor_id' => $this->tutor->id,
            'manager_id' => $this->manager->id,
            'status' => 'draft',
        ]);

        $response = $this->actingAs($this->director)
            ->post(route('director.assessments.approve', $draftAssessment), [
                'director_comment' => 'Approved',
            ]);

        $response->assertRedirect(route('director.assessments.index'));
        $response->assertSessionHas('error');

        $draftAssessment->refresh();
        $this->assertEquals('draft', $draftAssessment->status);
    }

    /** @test */
    public function director_can_add_comment_to_assessment()
    {
        $response = $this->actingAs($this->director)
            ->post(route('director.assessments.comment', $this->assessment), [
                'comment' => 'This is a test comment',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assessment->refresh();
        $this->assertStringContainsString('This is a test comment', $this->assessment->director_comment);
    }

    /** @test */
    public function director_activity_is_logged_for_assessments()
    {
        $this->actingAs($this->director)
            ->post(route('director.assessments.approve', $this->assessment), [
                'director_comment' => 'Approved',
            ]);

        $this->assertDatabaseHas('director_activity_logs', [
            'director_id' => $this->director->id,
            'action_type' => 'approved_assessment',
            'model_type' => TutorAssessment::class,
            'model_id' => $this->assessment->id,
        ]);
    }
}
