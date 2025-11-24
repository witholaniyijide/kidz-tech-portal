<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\Tutor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentFormTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an admin user
        $this->admin = User::factory()->create();
        // Assuming spatie/laravel-permission is used
        // $this->admin->assignRole('admin');
    }

    /** @test */
    public function it_validates_required_fields_for_student_creation()
    {
        $tutor = Tutor::factory()->create();

        $response = $this->actingAs($this->admin)->post(route('students.store'), []);

        $response->assertSessionHasErrors([
            'first_name',
            'last_name',
            'date_of_birth',
            'gender',
            'google_classroom_link',
            'tutor_id',
        ]);
    }

    /** @test */
    public function it_creates_student_with_valid_data()
    {
        $tutor = Tutor::factory()->create();

        $studentData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'other_name' => 'Michael',
            'date_of_birth' => '2010-05-15',
            'age' => 13,
            'gender' => 'male',
            'email' => 'john.doe@example.com',
            'coding_experience' => 'Basic HTML/CSS',
            'career_interest' => 'Web Development',
            'status' => 'active',
            'google_classroom_link' => 'https://classroom.google.com/test',
            'class_link' => 'https://zoom.us/test',
            'tutor_id' => $tutor->id,
            'class_schedule' => [
                ['day' => 'Monday', 'time' => '10:00'],
                ['day' => 'Wednesday', 'time' => '14:00'],
            ],
            'classes_per_week' => 2,
            'total_periods' => 24,
            'father_name' => 'James Doe',
            'father_phone' => '08012345678',
            'father_email' => 'james@example.com',
            'mother_name' => 'Jane Doe',
            'mother_phone' => '08087654321',
        ];

        $response = $this->actingAs($this->admin)->post(route('students.store'), $studentData);

        $response->assertRedirect();
        $this->assertDatabaseHas('students', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'tutor_id' => $tutor->id,
        ]);
    }

    /** @test */
    public function it_validates_nigerian_phone_format()
    {
        $tutor = Tutor::factory()->create();

        $response = $this->actingAs($this->admin)->post(route('students.store'), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'date_of_birth' => '2010-05-15',
            'gender' => 'male',
            'google_classroom_link' => 'https://classroom.google.com/test',
            'tutor_id' => $tutor->id,
            'father_phone' => '1234567890', // Invalid format
        ]);

        $response->assertSessionHasErrors('father_phone');
    }

    /** @test */
    public function it_validates_class_schedule_structure()
    {
        $tutor = Tutor::factory()->create();

        $response = $this->actingAs($this->admin)->post(route('students.store'), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'date_of_birth' => '2010-05-15',
            'gender' => 'male',
            'google_classroom_link' => 'https://classroom.google.com/test',
            'tutor_id' => $tutor->id,
            'class_schedule' => [
                ['day' => 'InvalidDay', 'time' => '10:00'], // Invalid day
            ],
        ]);

        $response->assertSessionHasErrors('class_schedule.0.day');
    }

    /** @test */
    public function it_updates_student_with_valid_data()
    {
        $tutor = Tutor::factory()->create();
        $student = Student::factory()->create(['tutor_id' => $tutor->id]);

        $updateData = [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'date_of_birth' => '2011-03-20',
            'gender' => 'female',
            'google_classroom_link' => 'https://classroom.google.com/updated',
            'tutor_id' => $tutor->id,
            'status' => 'active',
        ];

        $response = $this->actingAs($this->admin)->put(route('students.update', $student), $updateData);

        $response->assertRedirect();
        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'first_name' => 'Jane',
            'last_name' => 'Smith',
        ]);
    }

    /** @test */
    public function it_handles_save_and_add_another_action()
    {
        $tutor = Tutor::factory()->create();

        $studentData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'date_of_birth' => '2010-05-15',
            'gender' => 'male',
            'google_classroom_link' => 'https://classroom.google.com/test',
            'tutor_id' => $tutor->id,
            'action' => 'save_and_add',
        ];

        $response = $this->actingAs($this->admin)->post(route('students.store'), $studentData);

        $response->assertRedirect(route('students.create'));
        $response->assertSessionHas('success');
    }
}
