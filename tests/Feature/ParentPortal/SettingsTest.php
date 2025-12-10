<?php

namespace Tests\Feature\ParentPortal;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $parent;
    protected Role $parentRole;

    protected function setUp(): void
    {
        parent::setUp();

        // Create parent role
        $this->parentRole = Role::create(['name' => 'parent']);

        // Create a parent user
        $this->parent = User::factory()->create([
            'name' => 'Test Parent',
            'email' => 'parent@example.com',
            'password' => Hash::make('password'),
            'phone' => '08012345678',
            'notify_email' => true,
            'notify_in_app' => true,
            'notify_daily_summary' => false,
        ]);

        $this->parent->roles()->attach($this->parentRole);
    }

    /** @test */
    public function parent_can_view_settings_page()
    {
        $response = $this->actingAs($this->parent)
            ->get(route('parent.settings.index'));

        $response->assertOk();
        $response->assertViewIs('parent.settings.index');
        $response->assertViewHas('user', $this->parent);
    }

    /** @test */
    public function parent_can_update_profile_successfully()
    {
        $response = $this->actingAs($this->parent)
            ->put(route('parent.settings.profile'), [
                'name' => 'Updated Parent Name',
                'email' => 'updated@example.com',
                'phone' => '08098765432',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Profile updated successfully.');

        $this->assertDatabaseHas('users', [
            'id' => $this->parent->id,
            'name' => 'Updated Parent Name',
            'email' => 'updated@example.com',
            'phone' => '08098765432',
        ]);
    }

    /** @test */
    public function parent_profile_update_validates_required_fields()
    {
        $response = $this->actingAs($this->parent)
            ->put(route('parent.settings.profile'), [
                'name' => '',
                'email' => '',
            ]);

        $response->assertSessionHasErrors(['name', 'email']);
    }

    /** @test */
    public function parent_profile_update_validates_unique_email()
    {
        // Create another user with an email
        $otherUser = User::factory()->create([
            'email' => 'other@example.com',
        ]);

        $response = $this->actingAs($this->parent)
            ->put(route('parent.settings.profile'), [
                'name' => 'Test Parent',
                'email' => 'other@example.com',
            ]);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function parent_profile_update_validates_nigerian_phone_number()
    {
        $response = $this->actingAs($this->parent)
            ->put(route('parent.settings.profile'), [
                'name' => 'Test Parent',
                'email' => 'parent@example.com',
                'phone' => '1234567890', // Invalid Nigerian format
            ]);

        $response->assertSessionHasErrors(['phone']);
    }

    /** @test */
    public function parent_can_change_password_successfully()
    {
        $response = $this->actingAs($this->parent)
            ->put(route('parent.settings.password'), [
                'current_password' => 'password',
                'new_password' => 'newpassword123',
                'new_password_confirmation' => 'newpassword123',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Password changed successfully.');

        // Verify password was changed
        $this->parent->refresh();
        $this->assertTrue(Hash::check('newpassword123', $this->parent->password));
    }

    /** @test */
    public function parent_password_change_validates_current_password()
    {
        $response = $this->actingAs($this->parent)
            ->put(route('parent.settings.password'), [
                'current_password' => 'wrongpassword',
                'new_password' => 'newpassword123',
                'new_password_confirmation' => 'newpassword123',
            ]);

        $response->assertSessionHasErrors(['current_password']);
    }

    /** @test */
    public function parent_password_change_validates_confirmation()
    {
        $response = $this->actingAs($this->parent)
            ->put(route('parent.settings.password'), [
                'current_password' => 'password',
                'new_password' => 'newpassword123',
                'new_password_confirmation' => 'differentpassword',
            ]);

        $response->assertSessionHasErrors(['new_password']);
    }

    /** @test */
    public function parent_can_update_notification_preferences()
    {
        $response = $this->actingAs($this->parent)
            ->put(route('parent.settings.notifications'), [
                'notify_email' => '1',
                'notify_in_app' => '0',
                'notify_daily_summary' => '1',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Notification preferences updated successfully.');

        $this->assertDatabaseHas('users', [
            'id' => $this->parent->id,
            'notify_email' => true,
            'notify_in_app' => false,
            'notify_daily_summary' => true,
        ]);
    }

    /** @test */
    public function parent_notification_preferences_default_to_false_when_unchecked()
    {
        // Submit with no checkboxes checked (they won't be in the request)
        $response = $this->actingAs($this->parent)
            ->put(route('parent.settings.notifications'), []);

        $response->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $this->parent->id,
            'notify_email' => false,
            'notify_in_app' => false,
            'notify_daily_summary' => false,
        ]);
    }

    /** @test */
    public function non_parent_cannot_access_parent_settings()
    {
        $studentRole = Role::create(['name' => 'student']);
        $student = User::factory()->create();
        $student->roles()->attach($studentRole);

        $response = $this->actingAs($student)
            ->get(route('parent.settings.index'));

        $response->assertStatus(403);
    }

    /** @test */
    public function unauthenticated_user_cannot_access_parent_settings()
    {
        $response = $this->get(route('parent.settings.index'));

        $response->assertRedirect(route('login'));
    }
}
