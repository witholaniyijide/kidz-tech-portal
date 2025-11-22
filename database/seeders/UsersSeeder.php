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
                'email_verified_at' => now(),
            ]
        );
        if ($directorRole) {
            $director->roles()->sync([$directorRole->id]);
        }

        // Create Managers
        $managers = [
            [
                'name' => 'Sarah Manager',
                'email' => 'sarah.manager@kidztech.com',
                'phone' => '08023456789',
                'address' => '456 Manager Ave, Lagos',
                'date_of_birth' => '1985-03-22',
                'gender' => 'female',
            ],
            [
                'name' => 'Michael Admin',
                'email' => 'michael.admin@kidztech.com',
                'phone' => '08034567890',
                'address' => '789 Admin Road, Abuja',
                'date_of_birth' => '1983-07-10',
                'gender' => 'male',
            ],
        ];

        foreach ($managers as $managerData) {
            $manager = User::firstOrCreate(
                ['email' => $managerData['email']],
                array_merge($managerData, [
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ])
            );
            if ($managerRole) {
                $manager->roles()->sync([$managerRole->id]);
            }
        }

        // Create Admins
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
            );
            if ($adminRole) {
                $admin->roles()->sync([$adminRole->id]);
            }
        }

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

        foreach ($tutors as $tutorData) {
            $tutor = User::firstOrCreate(
                ['email' => $tutorData['email']],
                array_merge($tutorData, [
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ])
            );
            if ($tutorRole) {
                $tutor->roles()->sync([$tutorRole->id]);
            }
        }

        // Create Parents
        $parents = [
            [
                'name' => 'Mr. Ade Williams',
                'email' => 'ade.williams@example.com',
                'phone' => '08011111111',
                'address' => '1 Parent Street, Ikeja, Lagos',
                'date_of_birth' => '1975-01-20',
                'gender' => 'male',
            ],
            [
                'name' => 'Mrs. Funmi Balogun',
                'email' => 'funmi.balogun@example.com',
                'phone' => '08022222222',
                'address' => '2 Family Road, Victoria Island, Lagos',
                'date_of_birth' => '1978-04-15',
                'gender' => 'female',
            ],
            [
                'name' => 'Mr. Chidi Okafor',
                'email' => 'chidi.okafor@example.com',
                'phone' => '08033333333',
                'address' => '3 Guardian Avenue, Lekki, Lagos',
                'date_of_birth' => '1980-07-22',
                'gender' => 'male',
            ],
            [
                'name' => 'Mrs. Amina Abdullahi',
                'email' => 'amina.abdullahi@example.com',
                'phone' => '08044444444',
                'address' => '4 Caretaker Close, Garki, Abuja',
                'date_of_birth' => '1982-10-08',
                'gender' => 'female',
            ],
            [
                'name' => 'Mr. Emeka Nwosu',
                'email' => 'emeka.nwosu@example.com',
                'phone' => '08055555555',
                'address' => '5 Parent Plaza, GRA, Port Harcourt',
                'date_of_birth' => '1977-03-12',
                'gender' => 'male',
            ],
            [
                'name' => 'Mrs. Kemi Adeyemi',
                'email' => 'kemi.adeyemi@example.com',
                'phone' => '08066666666',
                'address' => '6 Family Street, Bodija, Ibadan',
                'date_of_birth' => '1981-06-30',
                'gender' => 'female',
            ],
            [
                'name' => 'Mr. Tunde Bakare',
                'email' => 'tunde.bakare@example.com',
                'phone' => '08077777777',
                'address' => '7 Guardian Way, Ogba, Lagos',
                'date_of_birth' => '1979-09-18',
                'gender' => 'male',
            ],
            [
                'name' => 'Mrs. Ngozi Eze',
                'email' => 'ngozi.eze@example.com',
                'phone' => '08088888888',
                'address' => '8 Parent Drive, Enugu',
                'date_of_birth' => '1983-12-05',
                'gender' => 'female',
            ],
            [
                'name' => 'Mr. Yusuf Hassan',
                'email' => 'yusuf.hassan@example.com',
                'phone' => '08099999999',
                'address' => '9 Caretaker Court, Kaduna',
                'date_of_birth' => '1976-11-25',
                'gender' => 'male',
            ],
            [
                'name' => 'Mrs. Grace Obi',
                'email' => 'grace.obi@example.com',
                'phone' => '08010101010',
                'address' => '10 Family Lane, Owerri',
                'date_of_birth' => '1984-02-14',
                'gender' => 'female',
            ],
        ];

        foreach ($parents as $parentData) {
            $parent = User::firstOrCreate(
                ['email' => $parentData['email']],
                array_merge($parentData, [
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ])
            );
            if ($parentRole) {
                $parent->roles()->sync([$parentRole->id]);
            }
        }

        $this->command->info('Users seeded successfully!');
    }
}
