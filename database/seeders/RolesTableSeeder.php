<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'director',
                'display_name' => 'Director',
                'description' => 'Full system access - can manage everything',
            ],
            [
                'name' => 'admin',
                'display_name' => 'Admin',
                'description' => 'Administrative access - can manage users, students, and operations',
            ],
            [
                'name' => 'manager',
                'display_name' => 'Manager',
                'description' => 'Can approve reports and manage tutors',
            ],
            [
                'name' => 'tutor',
                'display_name' => 'Tutor',
                'description' => 'Can submit attendance, create reports, and view assigned students',
            ],
            [
                'name' => 'parent',
                'display_name' => 'Parent',
                'description' => 'Can view their child\'s progress and reports',
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
