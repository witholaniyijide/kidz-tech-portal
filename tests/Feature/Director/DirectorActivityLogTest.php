<?php

namespace Tests\Feature\Director;

use App\Models\User;
use App\Models\Role;
use App\Models\DirectorActivityLog;
use App\Models\TutorReport;
use App\Models\Tutor;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DirectorActivityLogTest extends TestCase
{
    use RefreshDatabase;

    protected $director;
    protected $manager;

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
    }

    /** @test */
    public function director_can_view_activity_logs()
    {
        // Create some activity logs
        DirectorActivityLog::create([
            'director_id' => $this->director->id,
            'action_type' => 'approved_report',
            'model_type' => TutorReport::class,
            'model_id' => 1,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Test Browser',
            'created_at' => now(),
        ]);

        $response = $this->actingAs($this->director)
            ->get(route('director.activity-logs.index'));

        $response->assertStatus(200);
        $response->assertSee('approved_report');
    }

    /** @test */
    public function director_can_only_see_their_own_activity()
    {
        $otherDirector = User::factory()->create(['email' => 'other-director@test.com']);
        $otherDirector->roles()->attach(Role::where('name', 'director')->first());

        // Create activity log for current director
        DirectorActivityLog::create([
            'director_id' => $this->director->id,
            'action_type' => 'approved_report',
            'created_at' => now(),
        ]);

        // Create activity log for other director
        DirectorActivityLog::create([
            'director_id' => $otherDirector->id,
            'action_type' => 'approved_assessment',
            'created_at' => now(),
        ]);

        $response = $this->actingAs($this->director)
            ->get(route('director.activity-logs.index'));

        $response->assertStatus(200);
        $response->assertSee('approved_report');
        $response->assertDontSee('approved_assessment');
    }

    /** @test */
    public function unauthorized_users_cannot_access_activity_logs()
    {
        $response = $this->get(route('director.activity-logs.index'));
        $response->assertRedirect(route('login'));

        $response = $this->actingAs($this->manager)
            ->get(route('director.activity-logs.index'));
        $response->assertStatus(403);
    }

    /** @test */
    public function activity_log_is_created_with_correct_metadata()
    {
        $tutor = Tutor::factory()->create();
        $student = Student::factory()->create();
        $report = TutorReport::factory()->create([
            'tutor_id' => $tutor->id,
            'student_id' => $student->id,
        ]);

        $log = DirectorActivityLog::logAction(
            $this->director->id,
            'approved_report',
            TutorReport::class,
            $report->id,
            '192.168.1.1',
            'Test User Agent'
        );

        $this->assertDatabaseHas('director_activity_logs', [
            'director_id' => $this->director->id,
            'action_type' => 'approved_report',
            'model_type' => TutorReport::class,
            'model_id' => $report->id,
            'ip_address' => '192.168.1.1',
            'user_agent' => 'Test User Agent',
        ]);

        $this->assertEquals($this->director->id, $log->director_id);
        $this->assertEquals('approved_report', $log->action_type);
    }
}
