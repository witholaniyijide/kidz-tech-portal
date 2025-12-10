<?php

namespace Database\Seeders;

use App\Models\Tutor;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TutorsSeeder extends Seeder
{
    public function run(): void
    {
        $tutorUsers = User::whereHas('roles', function($query) {
            $query->where('name', 'tutor');
        })->get();

        $tutorsData = [
            [
                'first_name' => 'David',
                'last_name' => 'Okonkwo',
                'email' => 'david.okonkwo@kidztech.com',
                'phone' => '08045678901',
                'date_of_birth' => '1990-05-14',
                'gender' => 'male',
                'address' => '12 Coding Lane, Lagos',
                'state' => 'Lagos',
                'location' => 'Ikeja',
                'specializations' => ['Python', 'JavaScript', 'Web Development'],
                'hire_date' => '2023-01-15',
                'status' => 'active',
                'hourly_rate' => 5000.00,
                'qualifications' => 'B.Sc Computer Science, 5 years teaching experience',
            ],
            [
                'first_name' => 'Chioma',
                'last_name' => 'Adebayo',
                'email' => 'chioma.adebayo@kidztech.com',
                'phone' => '08056789012',
                'date_of_birth' => '1992-08-25',
                'gender' => 'female',
                'address' => '34 Python Street, Lagos',
                'state' => 'Lagos',
                'location' => 'Victoria Island',
                'specializations' => ['Scratch', 'Game Development', 'Mobile Apps'],
                'hire_date' => '2023-03-20',
                'status' => 'active',
                'hourly_rate' => 4500.00,
                'qualifications' => 'M.Sc Software Engineering, Certified coding instructor',
            ],
            [
                'first_name' => 'Emmanuel',
                'last_name' => 'Johnson',
                'email' => 'emmanuel.johnson@kidztech.com',
                'phone' => '08067890123',
                'date_of_birth' => '1988-11-30',
                'gender' => 'male',
                'address' => '56 JavaScript Ave, Abuja',
                'state' => 'FCT Abuja',
                'location' => 'Garki',
                'specializations' => ['Robotics', 'Arduino', 'IoT'],
                'hire_date' => '2022-09-10',
                'status' => 'active',
                'hourly_rate' => 5500.00,
                'qualifications' => 'B.Eng Electrical Engineering, Robotics certification',
            ],
            [
                'first_name' => 'Blessing',
                'last_name' => 'Okoro',
                'email' => 'blessing.okoro@kidztech.com',
                'phone' => '08078901234',
                'date_of_birth' => '1994-02-18',
                'gender' => 'female',
                'address' => '78 HTML Boulevard, Port Harcourt',
                'state' => 'Rivers',
                'location' => 'GRA',
                'specializations' => ['HTML/CSS', 'UI/UX Design', 'Graphic Design'],
                'hire_date' => '2023-06-01',
                'status' => 'active',
                'hourly_rate' => 4000.00,
                'qualifications' => 'B.A Creative Arts, Web design certification',
            ],
            [
                'first_name' => 'Ibrahim',
                'last_name' => 'Musa',
                'email' => 'ibrahim.musa@kidztech.com',
                'phone' => '08089012345',
                'date_of_birth' => '1991-09-05',
                'gender' => 'male',
                'address' => '90 CSS Circle, Kano',
                'state' => 'Kano',
                'location' => 'Nassarawa',
                'specializations' => ['Data Science', 'AI/ML', 'Python'],
                'hire_date' => '2023-02-14',
                'status' => 'on_leave',
                'hourly_rate' => 6000.00,
                'qualifications' => 'M.Sc Data Science, AI researcher',
            ],
        ];

        foreach ($tutorsData as $index => $tutorData) {
            // Find the corresponding user
            $user = $tutorUsers->where('email', $tutorData['email'])->first();

            Tutor::create([
                'user_id' => $user ? $user->id : null,
                'tutor_id' => 'TUT' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'first_name' => $tutorData['first_name'],
                'last_name' => $tutorData['last_name'],
                'email' => $tutorData['email'],
                'phone' => $tutorData['phone'],
                'date_of_birth' => $tutorData['date_of_birth'],
                'gender' => $tutorData['gender'],
                'address' => $tutorData['address'],
                'state' => $tutorData['state'],
                'location' => $tutorData['location'],
                'specializations' => json_encode($tutorData['specializations']),
                'hire_date' => $tutorData['hire_date'],
                'status' => $tutorData['status'],
                'hourly_rate' => $tutorData['hourly_rate'],
                'qualifications' => $tutorData['qualifications'],
            ]);
        }

        $this->command->info('Tutors seeded successfully!');
    }
}
