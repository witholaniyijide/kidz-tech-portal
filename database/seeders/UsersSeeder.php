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

<<<<<<< HEAD
        // Create Director (YOUR REAL CREDENTIALS)
        $director = User::firstOrCreate(
            ['email' => 'olaniyi@edubeta.net.ng'],
            [
                'name' => 'Olaniyi Olajide',
                'password' => Hash::make('password'), // Change this to your real password if different
                'phone' => '08138433544',
                'address' => 'Lagos, Nigeria',
                'date_of_birth' => '1990-01-01', // Update if you want
                'gender' => 'male',
=======
        // Create Director
        $director = User::firstOrCreate(
            ['email' => 'olaniyi@edubeta.net.ng'],
            [
                'name' => 'Olaniyi Jide',
                'password' => Hash::make('Motherslove100%'),
>>>>>>> d968725e447fdd7a736ae82a7908a05ea96bae2e
                'email_verified_at' => now(),
            ]
        );
        if ($directorRole) {
            $director->roles()->sync([$directorRole->id]);
        }

        // Create Managers
        for ($i = 1; $i <= 2; $i++) {
            $manager = User::firstOrCreate(
<<<<<<< HEAD
                ['email' => $managerData['email']],
                array_merge($managerData, [
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ])
=======
                ['email' => "manager{$i}@kidztech.com"],
                [
                    'name' => "Manager {$i}",
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
>>>>>>> d968725e447fdd7a736ae82a7908a05ea96bae2e
            );
            if ($managerRole) {
                $manager->roles()->sync([$managerRole->id]);
            }
        }

        // Create Admins
<<<<<<< HEAD
        $admins = [
            [
                'name' => 'Alice Admin',
                'email' => 'alice.admin@kidztech.com',
                'phone' => '08045678901',
                'address' => '101 Admin Street, Lagos',
                'date_of_birth' => '1987-05-14',
                'gender' => 'female',
            ],
            [
                'name' => 'Bob Admin',
                'email' => 'bob.admin@kidztech.com',
                'phone' => '08056789012',
                'address' => '202 Admin Avenue, Abuja',
                'date_of_birth' => '1989-08-25',
                'gender' => 'male',
            ],
        ];

        foreach ($admins as $adminData) {
            $admin = User::firstOrCreate(
                ['email' => $adminData['email']],
                array_merge($adminData, [
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ])
=======
        for ($i = 1; $i <= 2; $i++) {
            $admin = User::firstOrCreate(
                ['email' => "admin{$i}@kidztech.com"],
                [
                    'name' => "Admin {$i}",
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
>>>>>>> d968725e447fdd7a736ae82a7908a05ea96bae2e
            );
            if ($adminRole) {
                $admin->roles()->sync([$adminRole->id]);
            }
        }
<<<<<<< HEAD

        // Create Tutors (as users)
        $tutors = [
            [
                'name' => 'David Okonkwo',
                'email' => 'david.okonkwo@kidztech.com',
                'phone' => '08045678901',
                'address' => '12 Coding Lane, Lagos',
                'date_of_birth' => '1990-05-14',
                'gender' => 'male',
            ],
            [
                'name' => 'Chioma Adebayo',
                'email' => 'chioma.adebayo@kidztech.com',
                'phone' => '08056789012',
                'address' => '34 Python Street, Lagos',
                'date_of_birth' => '1992-08-25',
                'gender' => 'female',
            ],
            [
                'name' => 'Emmanuel Johnson',
                'email' => 'emmanuel.johnson@kidztech.com',
                'phone' => '08067890123',
                'address' => '56 JavaScript Ave, Abuja',
                'date_of_birth' => '1988-11-30',
                'gender' => 'male',
            ],
            [
                'name' => 'Blessing Okoro',
                'email' => 'blessing.okoro@kidztech.com',
                'phone' => '08078901234',
                'address' => '78 HTML Boulevard, Port Harcourt',
                'date_of_birth' => '1994-02-18',
                'gender' => 'female',
            ],
            [
                'name' => 'Ibrahim Musa',
                'email' => 'ibrahim.musa@kidztech.com',
                'phone' => '08089012345',
                'address' => '90 CSS Circle, Kano',
                'date_of_birth' => '1991-09-05',
                'gender' => 'male',
            ],
        ];
=======
>>>>>>> d968725e447fdd7a736ae82a7908a05ea96bae2e

        // Create Tutors
        for ($i = 1; $i <= 5; $i++) {
            $tutor = User::firstOrCreate(
<<<<<<< HEAD
                ['email' => $tutorData['email']],
                array_merge($tutorData, [
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ])
=======
                ['email' => "tutor{$i}@kidztech.com"],
                [
                    'name' => "Tutor {$i}",
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
>>>>>>> d968725e447fdd7a736ae82a7908a05ea96bae2e
            );
            if ($tutorRole) {
                $tutor->roles()->sync([$tutorRole->id]);
            }
        }

        // Create Parents
        for ($i = 1; $i <= 10; $i++) {
            $parent = User::firstOrCreate(
<<<<<<< HEAD
                ['email' => $parentData['email']],
                array_merge($parentData, [
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ])
=======
                ['email' => "parent{$i}@kidztech.com"],
                [
                    'name' => "Parent {$i}",
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
>>>>>>> d968725e447fdd7a736ae82a7908a05ea96bae2e
            );
            if ($parentRole) {
                $parent->roles()->sync([$parentRole->id]);
            }
        }

        $this->command->info('Users seeded successfully!');
    }
}
