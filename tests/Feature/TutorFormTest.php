<?php

namespace Tests\Feature;

use App\Models\Tutor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TutorFormTest extends TestCase
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
    public function it_validates_required_fields_for_tutor_creation()
    {
        $response = $this->actingAs($this->admin)->post(route('tutors.store'), []);

        $response->assertSessionHasErrors([
            'first_name',
            'last_name',
            'email',
            'phone',
        ]);
    }

    /** @test */
    public function it_creates_tutor_with_valid_data()
    {
        $tutorData = [
            'first_name' => 'Samuel',
            'last_name' => 'Johnson',
            'email' => 'samuel@example.com',
            'phone' => '08012345678',
            'date_of_birth' => '1990-06-15',
            'gender' => 'male',
            'location' => 'Lagos',
            'occupation' => 'Software Developer',
            'bio' => 'Experienced developer with 5 years in coding education',
            'status' => 'active',
            'contact_person_name' => 'Mary Johnson',
            'contact_person_phone' => '08087654321',
            'bank_name' => 'GTBank',
            'account_number' => '0123456789',
            'account_name' => 'Samuel Johnson',
        ];

        $response = $this->actingAs($this->admin)->post(route('tutors.store'), $tutorData);

        $response->assertRedirect();
        $this->assertDatabaseHas('tutors', [
            'first_name' => 'Samuel',
            'last_name' => 'Johnson',
            'email' => 'samuel@example.com',
        ]);
    }

    /** @test */
    public function it_validates_nigerian_phone_format_for_tutors()
    {
        $response = $this->actingAs($this->admin)->post(route('tutors.store'), [
            'first_name' => 'Samuel',
            'last_name' => 'Johnson',
            'email' => 'samuel@example.com',
            'phone' => '1234567890', // Invalid format
        ]);

        $response->assertSessionHasErrors('phone');
    }

    /** @test */
    public function it_validates_unique_email_for_tutors()
    {
        $existingTutor = Tutor::factory()->create(['email' => 'existing@example.com']);

        $response = $this->actingAs($this->admin)->post(route('tutors.store'), [
            'first_name' => 'Samuel',
            'last_name' => 'Johnson',
            'email' => 'existing@example.com', // Duplicate email
            'phone' => '08012345678',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function it_uploads_profile_photo_successfully()
    {
        Storage::fake('public');

        $tutorData = [
            'first_name' => 'Samuel',
            'last_name' => 'Johnson',
            'email' => 'samuel@example.com',
            'phone' => '08012345678',
            'profile_photo' => UploadedFile::fake()->image('profile.jpg', 400, 400),
        ];

        $response = $this->actingAs($this->admin)->post(route('tutors.store'), $tutorData);

        $response->assertRedirect();

        $tutor = Tutor::where('email', 'samuel@example.com')->first();
        $this->assertNotNull($tutor->profile_photo);

        Storage::disk('public')->assertExists($tutor->profile_photo);
    }

    /** @test */
    public function it_validates_profile_photo_file_type()
    {
        Storage::fake('public');

        $tutorData = [
            'first_name' => 'Samuel',
            'last_name' => 'Johnson',
            'email' => 'samuel@example.com',
            'phone' => '08012345678',
            'profile_photo' => UploadedFile::fake()->create('document.pdf', 1000), // Invalid type
        ];

        $response = $this->actingAs($this->admin)->post(route('tutors.store'), $tutorData);

        $response->assertSessionHasErrors('profile_photo');
    }

    /** @test */
    public function it_validates_profile_photo_size()
    {
        Storage::fake('public');

        $tutorData = [
            'first_name' => 'Samuel',
            'last_name' => 'Johnson',
            'email' => 'samuel@example.com',
            'phone' => '08012345678',
            'profile_photo' => UploadedFile::fake()->image('large.jpg')->size(3000), // Too large (3MB)
        ];

        $response = $this->actingAs($this->admin)->post(route('tutors.store'), $tutorData);

        $response->assertSessionHasErrors('profile_photo');
    }

    /** @test */
    public function it_creates_user_account_when_checkbox_is_checked()
    {
        $tutorData = [
            'first_name' => 'Samuel',
            'last_name' => 'Johnson',
            'email' => 'samuel@example.com',
            'phone' => '08012345678',
            'create_user_account' => '1',
        ];

        $response = $this->actingAs($this->admin)->post(route('tutors.store'), $tutorData);

        $response->assertRedirect();
        $this->assertDatabaseHas('tutors', ['email' => 'samuel@example.com']);
        $this->assertDatabaseHas('users', ['email' => 'samuel@example.com']);

        // Check temp password is flashed
        $response->assertSessionHas('temp_password');
    }

    /** @test */
    public function it_updates_tutor_and_replaces_profile_photo()
    {
        Storage::fake('public');

        $oldPhoto = UploadedFile::fake()->image('old.jpg');
        $tutor = Tutor::factory()->create([
            'profile_photo' => $oldPhoto->store('profile_photos', 'public'),
        ]);

        $newPhoto = UploadedFile::fake()->image('new.jpg');
        $updateData = [
            'first_name' => $tutor->first_name,
            'last_name' => $tutor->last_name,
            'email' => $tutor->email,
            'phone' => $tutor->phone,
            'status' => 'active',
            'profile_photo' => $newPhoto,
        ];

        $response = $this->actingAs($this->admin)->put(route('tutors.update', $tutor), $updateData);

        $response->assertRedirect();

        $tutor->refresh();
        Storage::disk('public')->assertExists($tutor->profile_photo);
    }
}
