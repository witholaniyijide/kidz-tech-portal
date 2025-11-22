<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        // Get roles
        $directorRole = Role::where('name', 'director')->first();
        $managerRole = Role::where('name', 'manager')->first();
        $adminRole = Role::where('name', 'admin')->first();
        $tutorRole = Role::where('name', 'tutor')->first();
        $parentRole = Role::where('name', 'parent')->first();

        // Create Director
        $director = User::firstOrCreate(
            ['email' => 'olaniyi@edubeta.net.ng'],
            [
                'name' => 'Olaniyi Jide',
                'password' => Hash::make('Motherslove100%'),
                'email_verified_at' => now(),
            ]
        );
        if ($directorRole) {
            $director->roles()->sync([$directorRole->id]);
        }

        // Create Managers
        for ($i = 1; $i <= 2; $i++) {
            $manager = User::firstOrCreate(
                ['email' => "manager{$i}@kidztech.com"],
                [
                    'name' => "Manager {$i}",
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
            if ($managerRole) {
                $manager->roles()->sync([$managerRole->id]);
            }
        }

        // Create Admins
        for ($i = 1; $i <= 2; $i++) {
            $admin = User::firstOrCreate(
                ['email' => "admin{$i}@kidztech.com"],
                [
                    'name' => "Admin {$i}",
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
            if ($adminRole) {
                $admin->roles()->sync([$adminRole->id]);
            }
        }

        // Create Tutors
        for ($i = 1; $i <= 5; $i++) {
            $tutor = User::firstOrCreate(
                ['email' => "tutor{$i}@kidztech.com"],
                [
                    'name' => "Tutor {$i}",
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
            if ($tutorRole) {
                $tutor->roles()->sync([$tutorRole->id]);
            }
        }

        // Create Parents
        for ($i = 1; $i <= 10; $i++) {
            $parent = User::firstOrCreate(
                ['email' => "parent{$i}@kidztech.com"],
                [
                    'name' => "Parent {$i}",
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
            if ($parentRole) {
                $parent->roles()->sync([$parentRole->id]);
            }
        }

        $this->command->info('Users seeded successfully!');
    }
}
