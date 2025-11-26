<?php

namespace Database\Seeders;

use App\Models\Tutor;
use App\Models\Student;
use App\Models\User;
use App\Models\TutorReport;
use Illuminate\Database\Seeder;

class DirectorApprovedReportsSeeder extends Seeder
{
    public function run(): void
    {
        // Get required users
        $director = User::whereHas('roles', function ($query) {
            $query->where('name', 'director');
        })->first();

        $tutors = Tutor::take(3)->get();
        $students = Student::take(3)->get();

        if (!$director) {
            $this->command->warn('No director found! Please run UsersSeeder first.');
            return;
        }

        if ($tutors->isEmpty() || $students->isEmpty()) {
            $this->command->warn('Please run TutorsSeeder and StudentsSeeder first!');
            return;
        }

        // Create 3 director-approved reports
        $reportData = [
            [
                'month' => now()->subMonths(3)->format('Y-m'),
                'progress_summary' => 'Excellent progress demonstrated throughout the month. Student has mastered HTML/CSS fundamentals and is now working on JavaScript basics.',
                'strengths' => 'Strong attention to detail, quick learner, excellent problem-solving skills, and consistent attendance.',
                'weaknesses' => 'Needs more practice with debugging techniques and could improve time management during coding exercises.',
                'next_steps' => 'Continue with JavaScript fundamentals, introduce DOM manipulation, and start working on interactive web projects.',
                'attendance_score' => 95,
                'performance_rating' => 'excellent',
                'manager_comment' => 'This is a well-written report. The tutor has provided clear insights into the student\'s progress. Approved for director review.',
                'director_comment' => 'Excellent work! The report is comprehensive and shows great progress. Approved for parent viewing.',
                'director_signature' => 'Olaniyi Jide',
            ],
            [
                'month' => now()->subMonths(2)->format('Y-m'),
                'progress_summary' => 'Good progress this month. Student completed the Python basics module and created their first calculator application.',
                'strengths' => 'Good understanding of variables and functions, enthusiastic about learning, and asks thoughtful questions.',
                'weaknesses' => 'Sometimes struggles with loop concepts and needs to practice more independently between sessions.',
                'next_steps' => 'Focus on mastering loops and conditional statements, then move to working with lists and dictionaries.',
                'attendance_score' => 88,
                'performance_rating' => 'good',
                'manager_comment' => 'Good report. Clear assessment of the student\'s abilities. Moving to director for final approval.',
                'director_comment' => 'Good progress report. The identified areas for improvement are actionable. Approved.',
                'director_signature' => 'Olaniyi Jide',
            ],
            [
                'month' => now()->subMonths(1)->format('Y-m'),
                'progress_summary' => 'Steady progress this month. Student is working through Scratch programming and has created two interactive games.',
                'strengths' => 'Creative thinking, good grasp of basic programming logic, and excellent collaboration with peers during group activities.',
                'weaknesses' => 'Could benefit from more structured planning before starting projects. Sometimes rushes through exercises.',
                'next_steps' => 'Introduce project planning techniques, work on more complex Scratch projects, and prepare for transition to text-based coding.',
                'attendance_score' => 92,
                'performance_rating' => 'good',
                'manager_comment' => 'The report provides a clear picture of the student\'s development. Recommended for director approval.',
                'director_comment' => 'Well-documented progress. The next steps are appropriate. Approved for release to parents.',
                'director_signature' => 'Olaniyi Jide',
            ],
        ];

        foreach ($reportData as $index => $data) {
            if (!isset($tutors[$index]) || !isset($students[$index])) {
                continue;
            }

            TutorReport::create([
                'tutor_id' => $tutors[$index]->id,
                'student_id' => $students[$index]->id,
                'director_id' => $director->id,
                'month' => $data['month'],
                'progress_summary' => $data['progress_summary'],
                'strengths' => $data['strengths'],
                'weaknesses' => $data['weaknesses'],
                'next_steps' => $data['next_steps'],
                'attendance_score' => $data['attendance_score'],
                'performance_rating' => $data['performance_rating'],
                'status' => 'approved-by-director',
                'manager_comment' => $data['manager_comment'],
                'director_comment' => $data['director_comment'],
                'director_signature' => $data['director_signature'],
                'submitted_at' => now()->subMonths(3 - $index)->subDays(20),
                'approved_by_manager_at' => now()->subMonths(3 - $index)->subDays(15),
                'approved_by_director_at' => now()->subMonths(3 - $index)->subDays(10),
            ]);
        }

        $this->command->info('Director-approved reports seeded successfully!');
    }
}
