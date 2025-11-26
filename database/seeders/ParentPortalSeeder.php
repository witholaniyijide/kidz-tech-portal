<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;

class ParentPortalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find or create the parent role
        $parentRole = Role::firstOrCreate(
            ['name' => 'parent'],
            ['description' => 'Parent/Guardian role for student portal access']
        );

        // Create a sample parent user
        $parent = User::firstOrCreate(
            ['email' => 'parent@example.com'],
            [
                'name' => 'Jane Doe',
                'password' => Hash::make('password'),
                'phone' => '+234-801-234-5678',
                'status' => 'active',
                'notify_email' => true,
                'notify_in_app' => true,
                'notify_daily_summary' => false,
            ]
        );

        // Attach parent role if not already attached
        if (!$parent->hasRole('parent')) {
            $parent->roles()->attach($parentRole->id);
        }

        // Create two sample students
        $student1 = Student::firstOrCreate(
            ['email' => 'student1@example.com'],
            [
                'student_id' => 'STU-' . strtoupper(uniqid()),
                'first_name' => 'Alice',
                'last_name' => 'Doe',
                'date_of_birth' => now()->subYears(10),
                'gender' => 'female',
                'address' => '123 Main Street, Lagos',
                'state' => 'Lagos',
                'country' => 'Nigeria',
                'enrollment_date' => now()->subMonths(6),
                'coding_experience' => 'Beginner',
                'career_interest' => 'Game Development',
                'status' => 'active',
                'location' => 'Lagos',
                'roadmap_stage' => 'scratch-beginner',
                'roadmap_progress' => 35,
                'roadmap_next_milestone' => 'Complete Scratch Animation Project',
                'learning_notes' => 'Alice is doing great! She loves building games and shows strong problem-solving skills.',
                'allow_parent_notifications' => true,
                'preferred_contact_method' => 'email',
                'visible_to_parent' => true,
            ]
        );

        $student2 = Student::firstOrCreate(
            ['email' => 'student2@example.com'],
            [
                'student_id' => 'STU-' . strtoupper(uniqid()),
                'first_name' => 'Bob',
                'last_name' => 'Doe',
                'date_of_birth' => now()->subYears(12),
                'gender' => 'male',
                'address' => '123 Main Street, Lagos',
                'state' => 'Lagos',
                'country' => 'Nigeria',
                'enrollment_date' => now()->subMonths(3),
                'coding_experience' => 'Intermediate',
                'career_interest' => 'Web Development',
                'status' => 'active',
                'location' => 'Lagos',
                'roadmap_stage' => 'python-basics',
                'roadmap_progress' => 60,
                'roadmap_next_milestone' => 'Build a Simple Calculator',
                'learning_notes' => 'Bob is progressing well with Python. He needs more practice with loops.',
                'allow_parent_notifications' => true,
                'preferred_contact_method' => 'email',
                'visible_to_parent' => true,
            ]
        );

        // Link parent to students via guardian_student pivot
        // Check if relationship already exists before syncing
        if (!$parent->guardiansOf()->where('student_id', $student1->id)->exists()) {
            $parent->guardiansOf()->attach($student1->id, [
                'relationship' => 'mother',
                'primary_contact' => true,
            ]);
        }

        if (!$parent->guardiansOf()->where('student_id', $student2->id)->exists()) {
            $parent->guardiansOf()->attach($student2->id, [
                'relationship' => 'mother',
                'primary_contact' => true,
            ]);
        }

        $this->command->info('✓ Parent portal seeder completed: Created parent user and linked to 2 students');
    }
}
