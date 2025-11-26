<?php

namespace Tests\Feature\ParentStudent;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;

class GuardianLinkTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create parent role
        Role::create(['name' => 'parent', 'description' => 'Parent role']);
    }

    /**
     * Test that a user with parent role can be linked to a student
     */
    public function test_parent_user_can_be_linked_to_student(): void
    {
        // Create a parent user
        $parent = User::create([
            'name' => 'Test Parent',
            'email' => 'parent@test.com',
            'password' => Hash::make('password'),
            'status' => 'active',
        ]);

        $parentRole = Role::where('name', 'parent')->first();
        $parent->roles()->attach($parentRole->id);

        // Create a student
        $student = Student::create([
            'student_id' => 'STU-TEST-001',
            'first_name' => 'Test',
            'last_name' => 'Student',
            'email' => 'student@test.com',
            'date_of_birth' => now()->subYears(10),
            'gender' => 'male',
            'status' => 'active',
        ]);

        // Link parent to student
        $parent->guardiansOf()->attach($student->id, [
            'relationship' => 'father',
            'primary_contact' => true,
        ]);

        // Assert the relationship exists
        $this->assertTrue($parent->guardiansOf()->where('student_id', $student->id)->exists());
        $this->assertEquals(1, $parent->guardiansOf()->count());
    }

    /**
     * Test that student->guardians returns correct user
     */
    public function test_student_guardians_returns_correct_user(): void
    {
        // Create parent and student
        $parent = User::create([
            'name' => 'Test Parent',
            'email' => 'parent@test.com',
            'password' => Hash::make('password'),
            'status' => 'active',
        ]);

        $parentRole = Role::where('name', 'parent')->first();
        $parent->roles()->attach($parentRole->id);

        $student = Student::create([
            'student_id' => 'STU-TEST-002',
            'first_name' => 'Test',
            'last_name' => 'Student',
            'email' => 'student2@test.com',
            'date_of_birth' => now()->subYears(10),
            'gender' => 'female',
            'status' => 'active',
        ]);

        // Link them
        $student->guardians()->attach($parent->id, [
            'relationship' => 'mother',
            'primary_contact' => true,
        ]);

        // Assert student->guardians returns the parent
        $this->assertEquals(1, $student->guardians()->count());
        $this->assertEquals($parent->id, $student->guardians()->first()->id);
        $this->assertEquals('mother', $student->guardians()->first()->pivot->relationship);
        $this->assertTrue($student->guardians()->first()->pivot->primary_contact);
    }

    /**
     * Test that user->guardiansOf returns correct students
     */
    public function test_user_guardians_of_returns_correct_students(): void
    {
        // Create parent
        $parent = User::create([
            'name' => 'Test Parent',
            'email' => 'parent@test.com',
            'password' => Hash::make('password'),
            'status' => 'active',
        ]);

        $parentRole = Role::where('name', 'parent')->first();
        $parent->roles()->attach($parentRole->id);

        // Create multiple students
        $student1 = Student::create([
            'student_id' => 'STU-TEST-003',
            'first_name' => 'Alice',
            'last_name' => 'Student',
            'email' => 'alice@test.com',
            'date_of_birth' => now()->subYears(10),
            'gender' => 'female',
            'status' => 'active',
        ]);

        $student2 = Student::create([
            'student_id' => 'STU-TEST-004',
            'first_name' => 'Bob',
            'last_name' => 'Student',
            'email' => 'bob@test.com',
            'date_of_birth' => now()->subYears(12),
            'gender' => 'male',
            'status' => 'active',
        ]);

        // Link parent to both students
        $parent->guardiansOf()->attach($student1->id, ['relationship' => 'father', 'primary_contact' => true]);
        $parent->guardiansOf()->attach($student2->id, ['relationship' => 'father', 'primary_contact' => false]);

        // Assert parent->guardiansOf returns both students
        $this->assertEquals(2, $parent->guardiansOf()->count());
        $this->assertTrue($parent->guardiansOf->contains($student1));
        $this->assertTrue($parent->guardiansOf->contains($student2));
    }

    /**
     * Test that isParent helper method works correctly
     */
    public function test_is_parent_helper_method(): void
    {
        $parent = User::create([
            'name' => 'Test Parent',
            'email' => 'parent@test.com',
            'password' => Hash::make('password'),
            'status' => 'active',
        ]);

        $parentRole = Role::where('name', 'parent')->first();
        $parent->roles()->attach($parentRole->id);

        $this->assertTrue($parent->isParent());

        // Create a non-parent user
        $nonParent = User::create([
            'name' => 'Test User',
            'email' => 'user@test.com',
            'password' => Hash::make('password'),
            'status' => 'active',
        ]);

        $this->assertFalse($nonParent->isParent());
    }
}
