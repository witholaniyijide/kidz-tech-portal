<?php

namespace Tests\Feature\Director\UI;

use App\Models\User;
use App\Models\Role;
use App\Models\TutorAssessment;
use App\Models\Tutor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DirectorAssessmentPagesTest extends TestCase
{
    use RefreshDatabase;

    protected $director;
    protected $assessment;

    protected function setUp(): void
    {
        parent::setUp();

        $directorRole = Role::firstOrCreate(['name' => 'director'], ['description' => 'Director']);

        $this->director = User::factory()->create();
        $this->director->roles()->attach($directorRole);

        $tutor = Tutor::factory()->create(['status' => 'active']);

        $this->assessment = TutorAssessment::factory()->create([
            'tutor_id' => $tutor->id,
            'status' => 'approved-by-manager',
            'approved_by_manager_at' => now(),
        ]);
    }

    /** @test */
    public function director_can_access_assessments_index_page()
    {
        $response = $this->actingAs($this->director)
            ->get(route('director.assessments.index'));

        $response->assertStatus(200);
        $response->assertSee('Tutor Assessments');
    }

    /** @test */
    public function director_can_view_assessment_show_page()
    {
        $response = $this->actingAs($this->director)
            ->get(route('director.assessments.show', $this->assessment));

        $response->assertStatus(200);
        $response->assertSee($this->assessment->tutor->fullName());
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
    }
}
