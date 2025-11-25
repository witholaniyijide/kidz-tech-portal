<?php

namespace Tests\Feature;

use App\Models\Tutor;
use App\Models\TutorAvailability;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TutorAvailabilityTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $tutorUser;
    protected Tutor $tutor;

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
    }

    /** @test */
    public function tutor_can_access_availability_page()
    {
        $response = $this->actingAs($this->tutorUser)
            ->get(route('tutor.availability.index'));

        $response->assertStatus(200);
        $response->assertSee('My Availability');
    }

    /** @test */
    public function tutor_can_create_availability()
    {
        $availabilityData = [
            'day' => 'Monday',
            'start_time' => '09:00',
            'end_time' => '12:00',
            'notes' => 'Morning sessions',
            'is_active' => true,
        ];

        $response = $this->actingAs($this->tutorUser)
            ->post(route('tutor.availability.store'), $availabilityData);

        $response->assertRedirect(route('tutor.availability.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('tutor_availabilities', [
            'tutor_id' => $this->tutor->id,
            'day' => 'Monday',
            'start_time' => '09:00:00',
            'end_time' => '12:00:00',
            'notes' => 'Morning sessions',
        ]);
    }

    /** @test */
    public function availability_validation_enforces_start_before_end()
    {
        $availabilityData = [
            'day' => 'Monday',
            'start_time' => '12:00',
            'end_time' => '09:00', // End before start
        ];

        $response = $this->actingAs($this->tutorUser)
            ->post(route('tutor.availability.store'), $availabilityData);

        $response->assertSessionHasErrors(['end_time']);
    }

    /** @test */
    public function availability_validation_requires_valid_day()
    {
        $availabilityData = [
            'day' => 'InvalidDay',
            'start_time' => '09:00',
            'end_time' => '12:00',
        ];

        $response = $this->actingAs($this->tutorUser)
            ->post(route('tutor.availability.store'), $availabilityData);

        $response->assertSessionHasErrors(['day']);
    }

    /** @test */
    public function tutor_can_update_availability()
    {
        $availability = TutorAvailability::factory()->create([
            'tutor_id' => $this->tutor->id,
            'day' => 'Monday',
            'start_time' => '09:00',
            'end_time' => '12:00',
            'is_active' => true,
        ]);

        $updateData = [
            'day' => 'Tuesday',
            'start_time' => '10:00',
            'end_time' => '14:00',
            'notes' => 'Updated notes',
            'is_active' => false,
        ];

        $response = $this->actingAs($this->tutorUser)
            ->put(route('tutor.availability.update', $availability), $updateData);

        $response->assertRedirect(route('tutor.availability.index'));

        $availability->refresh();
        $this->assertEquals('Tuesday', $availability->day);
        $this->assertEquals('10:00:00', $availability->start_time);
        $this->assertFalse($availability->is_active);
    }

    /** @test */
    public function tutor_can_delete_availability()
    {
        $availability = TutorAvailability::factory()->create([
            'tutor_id' => $this->tutor->id,
        ]);

        $response = $this->actingAs($this->tutorUser)
            ->delete(route('tutor.availability.destroy', $availability));

        $response->assertRedirect(route('tutor.availability.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('tutor_availabilities', [
            'id' => $availability->id,
        ]);
    }

    /** @test */
    public function tutor_cannot_update_other_tutors_availability()
    {
        $otherTutor = Tutor::factory()->create();
        $availability = TutorAvailability::factory()->create([
            'tutor_id' => $otherTutor->id,
        ]);

        $updateData = [
            'day' => 'Tuesday',
            'start_time' => '10:00',
            'end_time' => '14:00',
        ];

        $response = $this->actingAs($this->tutorUser)
            ->put(route('tutor.availability.update', $availability), $updateData);

        $response->assertStatus(403);
    }

    /** @test */
    public function tutor_cannot_delete_other_tutors_availability()
    {
        $otherTutor = Tutor::factory()->create();
        $availability = TutorAvailability::factory()->create([
            'tutor_id' => $otherTutor->id,
        ]);

        $response = $this->actingAs($this->tutorUser)
            ->delete(route('tutor.availability.destroy', $availability));

        $response->assertStatus(403);
    }

    /** @test */
    public function availability_validation_requires_required_fields()
    {
        $response = $this->actingAs($this->tutorUser)
            ->post(route('tutor.availability.store'), []);

        $response->assertSessionHasErrors(['day', 'start_time', 'end_time']);
    }

    /** @test */
    public function guest_cannot_access_tutor_availability_routes()
    {
        $response = $this->get(route('tutor.availability.index'));
        $response->assertRedirect(route('login'));

        $availability = TutorAvailability::factory()->create([
            'tutor_id' => $this->tutor->id,
        ]);

        $response = $this->delete(route('tutor.availability.destroy', $availability));
        $response->assertRedirect(route('login'));
    }
}
