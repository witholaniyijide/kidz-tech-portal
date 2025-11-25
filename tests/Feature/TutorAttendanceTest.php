<?php

namespace Tests\Feature;

use App\Models\AttendanceRecord;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TutorAttendanceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $tutorUser;
    protected Tutor $tutor;
    protected Student $student;
    protected Student $otherStudent;

    protected function setUp(): void
    {
        parent::setUp();

        // Create tutor user
        $this->tutorUser = User::factory()->create([
            'email' => 'tutor@test.com',
            'role' => 'tutor',
        ]);

        // Create tutor profile
        $this->tutor = Tutor::factory()->create([
            'email' => 'tutor@test.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        // Create student assigned to this tutor
        $this->student = Student::factory()->create([
            'tutor_id' => $this->tutor->id,
            'status' => 'active',
        ]);

        // Create student assigned to another tutor
        $this->otherStudent = Student::factory()->create([
            'tutor_id' => null,
            'status' => 'active',
        ]);
    }

    /** @test */
    public function tutor_can_access_attendance_create_page()
    {
        $response = $this->actingAs($this->tutorUser)
            ->get(route('tutor.attendance.create'));

        $response->assertStatus(200);
        $response->assertSee('Submit Attendance');
    }

    /** @test */
    public function tutor_can_submit_attendance_for_assigned_student()
    {
        $attendanceData = [
            'student_id' => $this->student->id,
            'class_date' => now()->format('Y-m-d'),
            'class_time' => '14:00',
            'duration_minutes' => 60,
            'topic' => 'Introduction to Python',
            'notes' => 'Student did well in the lesson',
        ];

        $response = $this->actingAs($this->tutorUser)
            ->post(route('tutor.attendance.store'), $attendanceData);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('attendance_records', [
            'student_id' => $this->student->id,
            'tutor_id' => $this->tutor->id,
            'topic' => 'Introduction to Python',
            'status' => 'pending',
        ]);
    }

    /** @test */
    public function tutor_cannot_submit_attendance_for_unassigned_student()
    {
        $attendanceData = [
            'student_id' => $this->otherStudent->id,
            'class_date' => now()->format('Y-m-d'),
            'class_time' => '14:00',
            'duration_minutes' => 60,
            'topic' => 'Test',
        ];

        $response = $this->actingAs($this->tutorUser)
            ->post(route('tutor.attendance.store'), $attendanceData);

        $response->assertStatus(403);
    }

    /** @test */
    public function attendance_validation_requires_required_fields()
    {
        $response = $this->actingAs($this->tutorUser)
            ->post(route('tutor.attendance.store'), []);

        $response->assertSessionHasErrors(['student_id', 'class_date', 'class_time', 'duration_minutes']);
    }

    /** @test */
    public function attendance_class_date_cannot_be_in_future()
    {
        $attendanceData = [
            'student_id' => $this->student->id,
            'class_date' => now()->addDay()->format('Y-m-d'),
            'class_time' => '14:00',
            'duration_minutes' => 60,
        ];

        $response = $this->actingAs($this->tutorUser)
            ->post(route('tutor.attendance.store'), $attendanceData);

        $response->assertSessionHasErrors(['class_date']);
    }

    /** @test */
    public function attendance_duration_must_be_at_least_15_minutes()
    {
        $attendanceData = [
            'student_id' => $this->student->id,
            'class_date' => now()->format('Y-m-d'),
            'class_time' => '14:00',
            'duration_minutes' => 10,
        ];

        $response = $this->actingAs($this->tutorUser)
            ->post(route('tutor.attendance.store'), $attendanceData);

        $response->assertSessionHasErrors(['duration_minutes']);
    }

    /** @test */
    public function tutor_can_view_their_submitted_attendance()
    {
        $attendance = AttendanceRecord::factory()->create([
            'tutor_id' => $this->tutor->id,
            'student_id' => $this->student->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->tutorUser)
            ->get(route('tutor.attendance.show', $attendance));

        $response->assertStatus(200);
        $response->assertSee($this->student->fullName());
    }

    /** @test */
    public function tutor_cannot_view_other_tutors_attendance()
    {
        $otherTutor = Tutor::factory()->create();
        $attendance = AttendanceRecord::factory()->create([
            'tutor_id' => $otherTutor->id,
            'student_id' => $this->student->id,
        ]);

        $response = $this->actingAs($this->tutorUser)
            ->get(route('tutor.attendance.show', $attendance));

        $response->assertStatus(403);
    }

    /** @test */
    public function guest_cannot_access_tutor_attendance_routes()
    {
        $response = $this->get(route('tutor.attendance.create'));
        $response->assertRedirect(route('login'));

        $attendance = AttendanceRecord::factory()->create([
            'tutor_id' => $this->tutor->id,
            'student_id' => $this->student->id,
        ]);

        $response = $this->get(route('tutor.attendance.show', $attendance));
        $response->assertRedirect(route('login'));
    }
}
