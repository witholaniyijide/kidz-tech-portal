<?php

namespace Tests\Feature\Director\UI;

use App\Models\User;
use App\Models\Role;
use App\Models\TutorReport;
use App\Models\Tutor;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DirectorReportPagesTest extends TestCase
{
    use RefreshDatabase;

    protected $director;
    protected $report;

    protected function setUp(): void
    {
        parent::setUp();

        $directorRole = Role::firstOrCreate(['name' => 'director'], ['description' => 'Director']);

        $this->director = User::factory()->create();
        $this->director->roles()->attach($directorRole);

        $tutor = Tutor::factory()->create(['status' => 'active']);
        $student = Student::factory()->create(['status' => 'active']);

        $this->report = TutorReport::factory()->create([
            'tutor_id' => $tutor->id,
            'student_id' => $student->id,
            'status' => 'approved-by-manager',
            'approved_by_manager_at' => now(),
        ]);
    }

    /** @test */
    public function director_can_access_reports_index_page()
    {
        $response = $this->actingAs($this->director)
            ->get(route('director.reports.index'));

        $response->assertStatus(200);
        $response->assertSee('Manager Approved Reports');
        $response->assertSee($this->report->student->first_name);
    }

    /** @test */
    public function director_can_view_report_show_page()
    {
        $response = $this->actingAs($this->director)
            ->get(route('director.reports.show', $this->report));

        $response->assertStatus(200);
        $response->assertSee($this->report->student->fullName());
        $response->assertSee('Director Final Approval');
    }

    /** @test */
    public function director_can_approve_report_from_show_page()
    {
        $response = $this->actingAs($this->director)
            ->post(route('director.reports.approve', $this->report), [
                'director_comment' => 'Approved',
            ]);

        $response->assertRedirect(route('director.reports.index'));
        $response->assertSessionHas('success');

        $this->report->refresh();
        $this->assertEquals('approved-by-director', $this->report->status);
    }

    /** @test */
    public function approve_flow_requires_manager_approved_status()
    {
        $this->report->update(['status' => 'submitted']);

        $response = $this->actingAs($this->director)
            ->post(route('director.reports.approve', $this->report), [
                'director_comment' => 'Trying to approve',
            ]);

        $response->assertSessionHas('error');
        $this->report->refresh();
        $this->assertEquals('submitted', $this->report->status);
    }

    /** @test */
    public function unauthenticated_users_cannot_access_director_pages()
    {
        $response = $this->get(route('director.reports.index'));
        $response->assertRedirect(route('login'));

        $response = $this->get(route('director.reports.show', $this->report));
        $response->assertRedirect(route('login'));
    }
}
