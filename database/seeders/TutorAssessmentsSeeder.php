<?php

namespace Database\Seeders;

use App\Models\Tutor;
use App\Models\User;
use App\Models\TutorAssessment;
use Illuminate\Database\Seeder;

class TutorAssessmentsSeeder extends Seeder
{
    public function run(): void
    {
        // Get required users
        $director = User::whereHas('roles', function ($query) {
            $query->where('name', 'director');
        })->first();

        $managers = User::whereHas('roles', function ($query) {
            $query->where('name', 'manager');
        })->take(2)->get();

        $tutors = Tutor::take(3)->get();

        if ($tutors->isEmpty() || $managers->isEmpty()) {
            $this->command->warn('Please run TutorsSeeder and UsersSeeder first!');
            return;
        }

        // Assessment 1: Draft status (Manager is still working on it)
        if (isset($tutors[0]) && isset($managers[0])) {
            TutorAssessment::create([
                'tutor_id' => $tutors[0]->id,
                'manager_id' => $managers[0]->id,
                'director_id' => null,
                'assessment_month' => now()->format('Y-m'),
                'strengths' => 'Punctual and professional. Shows strong technical knowledge and communicates well with students.',
                'weaknesses' => 'Could improve lesson planning documentation. Sometimes runs over allocated time.',
                'recommendations' => 'Attend time management workshop. Work on standardizing lesson plans.',
                'performance_score' => 85,
                'professionalism_rating' => 'excellent',
                'communication_rating' => 'good',
                'punctuality_rating' => 'excellent',
                'manager_comment' => 'This assessment is still being finalized. Need to review one more session before submission.',
                'director_comment' => null,
                'approved_by_manager_at' => null,
                'approved_by_director_at' => null,
                'status' => 'draft',
            ]);
        }

        // Assessment 2: Approved by Manager (Pending Director Approval)
        if (isset($tutors[1]) && isset($managers[0])) {
            TutorAssessment::create([
                'tutor_id' => $tutors[1]->id,
                'manager_id' => $managers[0]->id,
                'director_id' => null,
                'assessment_month' => now()->subMonth()->format('Y-m'),
                'strengths' => 'Excellent rapport with students. Creative in teaching approaches and consistently achieves learning objectives. Very responsive to feedback.',
                'weaknesses' => 'Occasionally late with report submissions. Could improve integration of assessment tools.',
                'recommendations' => 'Set calendar reminders for deadlines. Provide training on using the new assessment platform.',
                'performance_score' => 92,
                'professionalism_rating' => 'excellent',
                'communication_rating' => 'excellent',
                'punctuality_rating' => 'good',
                'manager_comment' => 'Outstanding tutor with strong performance across all metrics. Highly recommend for director approval. The identified areas for improvement are minor and easily addressable.',
                'director_comment' => null,
                'approved_by_manager_at' => now()->subDays(5),
                'approved_by_director_at' => null,
                'status' => 'approved-by-manager',
            ]);
        }

        // Assessment 3: Approved by Director (Complete workflow)
        if (isset($tutors[2]) && isset($managers[1]) && $director) {
            TutorAssessment::create([
                'tutor_id' => $tutors[2]->id,
                'manager_id' => $managers[1]->id,
                'director_id' => $director->id,
                'assessment_month' => now()->subMonths(2)->format('Y-m'),
                'strengths' => 'Demonstrates exceptional teaching ability. Strong classroom management and ability to adapt to different learning styles. Maintains detailed records.',
                'weaknesses' => 'Could benefit from more collaborative planning with other tutors. Sometimes hesitant to try new teaching technologies.',
                'recommendations' => 'Encourage participation in peer teaching sessions. Provide support for technology adoption through one-on-one training.',
                'performance_score' => 88,
                'professionalism_rating' => 'excellent',
                'communication_rating' => 'excellent',
                'punctuality_rating' => 'excellent',
                'manager_comment' => 'Excellent tutor who consistently delivers high-quality instruction. The recommendations focus on professional development opportunities that will further enhance their already strong skills.',
                'director_comment' => 'I concur with the manager\'s assessment. This tutor is a valuable asset to our team. Approved. Please ensure the recommended training is scheduled.',
                'approved_by_manager_at' => now()->subMonths(2)->addDays(20),
                'approved_by_director_at' => now()->subMonths(2)->addDays(25),
                'status' => 'approved-by-director',
            ]);
        }

        $this->command->info('Tutor assessments seeded successfully!');
    }
}
