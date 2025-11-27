<?php

namespace Tests\Feature\ParentPortal;

use App\Models\User;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\AttendanceRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class AttendanceControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test attendance chart returns JSON with correct structure
     */
    public function test_attendance_chart_returns_json(): void
    {
        $parent = User::factory()->create(['role' => 'parent']);
        $student = Student::factory()->create(['parent_id' => $parent->id, 'user_id' => $parent->id]);
        $tutor = Tutor::factory()->create();

        // Create attendance records for current month
        $currentMonth = Carbon::now()->format('Y-m');

        AttendanceRecord::factory()->create([
            'student_id' => $student->id,
            'tutor_id' => $tutor->id,
            'class_date' => Carbon::now()->subDays(5),
            'status' => 'present',
        ]);

        AttendanceRecord::factory()->create([
            'student_id' => $student->id,
            'tutor_id' => $tutor->id,
            'class_date' => Carbon::now()->subDays(3),
            'status' => 'absent',
        ]);

        AttendanceRecord::factory()->create([
            'student_id' => $student->id,
            'tutor_id' => $tutor->id,
            'class_date' => Carbon::now()->subDays(1),
            'status' => 'present',
        ]);

        $response = $this->actingAs($parent)->get(route('student.attendance.chart', [
            'student' => $student->id,
            'month' => $currentMonth
        ]));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'labels',
            'datasets' => [
                '*' => [
                    'label',
                    'data',
                    'backgroundColor',
                    'borderColor',
                    'borderWidth',
                ]
            ]
        ]);

        $data = $response->json();
        $this->assertIsArray($data['labels']);
        $this->assertIsArray($data['datasets']);
    }

    /**
     * Test attendance index page loads with statistics
     */
    public function test_attendance_index_loads_with_statistics(): void
    {
        $parent = User::factory()->create(['role' => 'parent']);
        $student = Student::factory()->create(['parent_id' => $parent->id, 'user_id' => $parent->id]);
        $tutor = Tutor::factory()->create();

        // Create attendance records
        AttendanceRecord::factory()->count(10)->create([
            'student_id' => $student->id,
            'tutor_id' => $tutor->id,
            'status' => 'present',
        ]);

        AttendanceRecord::factory()->count(2)->create([
            'student_id' => $student->id,
            'tutor_id' => $tutor->id,
            'status' => 'absent',
        ]);

        $response = $this->actingAs($parent)->get(route('student.attendance.index'));

        $response->assertStatus(200);
        $response->assertViewHas(['attendanceRate', 'completedClasses', 'missedClasses', 'currentStreak']);

        $attendanceRate = $response->viewData('attendanceRate');
        $this->assertGreaterThan(0, $attendanceRate);
        $this->assertLessThanOrEqual(100, $attendanceRate);
    }

    /**
     * Test attendance detail page loads correctly
     */
    public function test_attendance_detail_page_loads(): void
    {
        $parent = User::factory()->create(['role' => 'parent']);
        $student = Student::factory()->create(['parent_id' => $parent->id, 'user_id' => $parent->id]);
        $tutor = Tutor::factory()->create();

        $record = AttendanceRecord::factory()->create([
            'student_id' => $student->id,
            'tutor_id' => $tutor->id,
            'status' => 'present',
            'topic' => 'Introduction to Python',
        ]);

        $response = $this->actingAs($parent)->get(route('student.attendance.show', $record->id));

        $response->assertStatus(200);
        $response->assertViewHas('record');
        $response->assertSee($record->topic);
    }

    /**
     * Test student cannot access another student's attendance
     */
    public function test_student_cannot_access_other_student_attendance(): void
    {
        $student1User = User::factory()->create(['role' => 'student']);
        $student1 = Student::factory()->create(['parent_id' => $student1User->id, 'user_id' => $student1User->id]);

        $student2User = User::factory()->create(['role' => 'student']);
        $student2 = Student::factory()->create(['parent_id' => $student2User->id, 'user_id' => $student2User->id]);

        $tutor = Tutor::factory()->create();

        $record = AttendanceRecord::factory()->create([
            'student_id' => $student2->id,
            'tutor_id' => $tutor->id,
            'status' => 'present',
        ]);

        // Student1 tries to access student2's attendance
        $response = $this->actingAs($student1User)->get(route('student.attendance.show', $record->id));

        $response->assertStatus(403);
    }

    /**
     * Test attendance rate calculation is accurate
     */
    public function test_attendance_rate_calculation_accurate(): void
    {
        $parent = User::factory()->create(['role' => 'parent']);
        $student = Student::factory()->create(['parent_id' => $parent->id, 'user_id' => $parent->id]);
        $tutor = Tutor::factory()->create();

        // Create 8 present and 2 absent (80% attendance rate)
        AttendanceRecord::factory()->count(8)->create([
            'student_id' => $student->id,
            'tutor_id' => $tutor->id,
            'status' => 'present',
        ]);

        AttendanceRecord::factory()->count(2)->create([
            'student_id' => $student->id,
            'tutor_id' => $tutor->id,
            'status' => 'absent',
        ]);

        $response = $this->actingAs($parent)->get(route('student.attendance.index'));

        $response->assertStatus(200);
        $attendanceRate = $response->viewData('attendanceRate');

        // Should be 80%
        $this->assertEquals(80, $attendanceRate);
    }

    /**
     * Test current streak calculation
     */
    public function test_current_streak_calculation(): void
    {
        $parent = User::factory()->create(['role' => 'parent']);
        $student = Student::factory()->create(['parent_id' => $parent->id, 'user_id' => $parent->id]);
        $tutor = Tutor::factory()->create();

        // Create a streak of 5 consecutive present records
        for ($i = 5; $i >= 1; $i--) {
            AttendanceRecord::factory()->create([
                'student_id' => $student->id,
                'tutor_id' => $tutor->id,
                'class_date' => Carbon::now()->subDays($i),
                'status' => 'present',
            ]);
        }

        // Add an absent record before the streak
        AttendanceRecord::factory()->create([
            'student_id' => $student->id,
            'tutor_id' => $tutor->id,
            'class_date' => Carbon::now()->subDays(6),
            'status' => 'absent',
        ]);

        $response = $this->actingAs($parent)->get(route('student.attendance.index'));

        $response->assertStatus(200);
        $currentStreak = $response->viewData('currentStreak');

        // Should be 5
        $this->assertEquals(5, $currentStreak);
    }

    /**
     * Test unauthorized user cannot access attendance
     */
    public function test_unauthorized_user_cannot_access_attendance(): void
    {
        $student = Student::factory()->create();
        $tutor = Tutor::factory()->create();

        $record = AttendanceRecord::factory()->create([
            'student_id' => $student->id,
            'tutor_id' => $tutor->id,
            'status' => 'present',
        ]);

        // Unauthenticated user
        $response = $this->get(route('student.attendance.show', $record->id));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test monthly chart data filtering works
     */
    public function test_monthly_chart_data_filtering(): void
    {
        $parent = User::factory()->create(['role' => 'parent']);
        $student = Student::factory()->create(['parent_id' => $parent->id, 'user_id' => $parent->id]);
        $tutor = Tutor::factory()->create();

        // Create records for different months
        AttendanceRecord::factory()->create([
            'student_id' => $student->id,
            'tutor_id' => $tutor->id,
            'class_date' => Carbon::parse('2025-01-15'),
            'status' => 'present',
        ]);

        AttendanceRecord::factory()->create([
            'student_id' => $student->id,
            'tutor_id' => $tutor->id,
            'class_date' => Carbon::parse('2025-02-15'),
            'status' => 'present',
        ]);

        // Request chart for January only
        $response = $this->actingAs($parent)->get(route('student.attendance.chart', [
            'student' => $student->id,
            'month' => '2025-01'
        ]));

        $response->assertStatus(200);
        $data = $response->json();

        // Should only include January data
        $this->assertIsArray($data['labels']);
    }
}
