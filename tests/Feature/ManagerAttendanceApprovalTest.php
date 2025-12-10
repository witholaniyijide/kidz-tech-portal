<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\AttendanceRecord;
use App\Models\Student;
use App\Models\Tutor;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManagerAttendanceApprovalTest extends TestCase
{
    use RefreshDatabase;

    protected $manager;
    protected $student;
    protected $tutor;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a manager user
        $this->manager = User::factory()->create([
            'role' => 'manager',
            'email' => 'manager@example.com',
        ]);

        // Create a student
        $this->student = Student::factory()->create([
            'completed_periods' => 5,
            'total_periods' => 20,
        ]);

        // Create a tutor
        $this->tutor = Tutor::factory()->create();
    }

    /** @test */
    public function manager_can_view_pending_attendance_page()
    {
        $this->actingAs($this->manager);

        $response = $this->get(route('manager.attendance.pending'));

        $response->assertStatus(200);
        $response->assertViewIs('attendance.pending');
    }

    /** @test */
    public function manager_can_approve_attendance_record()
    {
        $this->actingAs($this->manager);

        // Create a pending attendance record
        $attendance = AttendanceRecord::factory()->create([
            'student_id' => $this->student->id,
            'tutor_id' => $this->tutor->id,
            'status' => 'pending',
            'duration_minutes' => 60,
        ]);

        $initialCompletedPeriods = $this->student->completed_periods;

        $response = $this->post(route('manager.attendance.approve', $attendance));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Check attendance record was updated
        $attendance->refresh();
        $this->assertEquals('approved', $attendance->status);
        $this->assertEquals($this->manager->id, $attendance->approved_by);
        $this->assertNotNull($attendance->approved_at);

        // Check student's completed periods was incremented
        $this->student->refresh();
        $this->assertEquals($initialCompletedPeriods + 1, $this->student->completed_periods);
    }

    /** @test */
    public function manager_can_reject_attendance_record()
    {
        $this->actingAs($this->manager);

        $attendance = AttendanceRecord::factory()->create([
            'student_id' => $this->student->id,
            'tutor_id' => $this->tutor->id,
            'status' => 'pending',
        ]);

        $initialCompletedPeriods = $this->student->completed_periods;

        $response = $this->post(route('manager.attendance.reject', $attendance));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Check attendance record was updated
        $attendance->refresh();
        $this->assertEquals('rejected', $attendance->status);
        $this->assertEquals($this->manager->id, $attendance->approved_by);
        $this->assertNotNull($attendance->approved_at);

        // Check student's completed periods was NOT incremented
        $this->student->refresh();
        $this->assertEquals($initialCompletedPeriods, $this->student->completed_periods);
    }

    /** @test */
    public function manager_can_bulk_approve_attendance_records()
    {
        $this->actingAs($this->manager);

        // Create multiple pending attendance records
        $attendance1 = AttendanceRecord::factory()->create([
            'student_id' => $this->student->id,
            'tutor_id' => $this->tutor->id,
            'status' => 'pending',
        ]);

        $attendance2 = AttendanceRecord::factory()->create([
            'student_id' => $this->student->id,
            'tutor_id' => $this->tutor->id,
            'status' => 'pending',
        ]);

        $initialCompletedPeriods = $this->student->completed_periods;

        $response = $this->post(route('manager.attendance.bulkApprove'), [
            'ids' => [$attendance1->id, $attendance2->id],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Check both records were approved
        $attendance1->refresh();
        $attendance2->refresh();
        $this->assertEquals('approved', $attendance1->status);
        $this->assertEquals('approved', $attendance2->status);

        // Check student's completed periods was incremented by 2
        $this->student->refresh();
        $this->assertEquals($initialCompletedPeriods + 2, $this->student->completed_periods);
    }

    /** @test */
    public function non_manager_cannot_approve_attendance()
    {
        $regularUser = User::factory()->create(['role' => 'tutor']);
        $this->actingAs($regularUser);

        $attendance = AttendanceRecord::factory()->create([
            'student_id' => $this->student->id,
            'tutor_id' => $this->tutor->id,
            'status' => 'pending',
        ]);

        $response = $this->post(route('manager.attendance.approve', $attendance));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_access_pending_attendance()
    {
        $response = $this->get(route('manager.attendance.pending'));

        $response->assertRedirect(route('login'));
    }
}
