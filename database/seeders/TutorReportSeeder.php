<?php

namespace Database\Seeders;

use App\Models\TutorReport;
use App\Models\Tutor;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TutorReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all active tutors and their students
        $tutors = Tutor::with('students')->where('status', 'active')->get();

        if ($tutors->isEmpty()) {
            $this->command->info('No active tutors found. Skipping report seeding.');
            return;
        }

        $statuses = ['draft', 'submitted', 'approved-by-manager', 'approved-by-director'];
        $performanceRatings = ['excellent', 'good', 'average', 'poor'];

        $months = [
            Carbon::now()->subMonths(3)->format('Y-m'),
            Carbon::now()->subMonths(2)->format('Y-m'),
            Carbon::now()->subMonths(1)->format('Y-m'),
            Carbon::now()->format('Y-m'),
        ];

        foreach ($tutors->take(3) as $tutor) {
            $students = $tutor->students()->active()->take(5)->get();

            foreach ($students as $student) {
                // Create 2-3 reports per student
                foreach ($months as $index => $month) {
                    if ($index >= 2) break; // Only create reports for 2 months per student

                    $status = $statuses[array_rand($statuses)];
                    $performanceRating = $performanceRatings[array_rand($performanceRatings)];

                    $report = TutorReport::create([
                        'tutor_id' => $tutor->id,
                        'student_id' => $student->id,
                        'month' => $month,
                        'progress_summary' => $this->generateProgressSummary($student->first_name),
                        'strengths' => $this->generateStrengths(),
                        'weaknesses' => $this->generateWeaknesses(),
                        'next_steps' => $this->generateNextSteps(),
                        'attendance_score' => rand(70, 100),
                        'performance_rating' => $performanceRating,
                        'status' => $status,
                        'manager_comment' => $status === 'approved-by-manager' || $status === 'approved-by-director'
                            ? 'Good progress observed. Keep up the excellent work.'
                            : null,
                        'director_comment' => $status === 'approved-by-director'
                            ? 'Excellent report. Well documented progress.'
                            : null,
                        'submitted_at' => $status !== 'draft' ? Carbon::now()->subDays(rand(1, 10)) : null,
                        'approved_by_manager_at' => in_array($status, ['approved-by-manager', 'approved-by-director'])
                            ? Carbon::now()->subDays(rand(1, 5))
                            : null,
                        'approved_by_director_at' => $status === 'approved-by-director'
                            ? Carbon::now()->subDays(rand(1, 3))
                            : null,
                    ]);

                    $this->command->info("Created {$status} report for {$student->fullName()} - {$month}");
                }
            }
        }

        $this->command->info('Tutor reports seeded successfully!');
    }

    private function generateProgressSummary(string $name): string
    {
        $summaries = [
            "{$name} has shown consistent progress this month, demonstrating strong engagement in class activities and completing assignments on time.",
            "{$name} continues to improve their coding skills, showing particular strength in problem-solving and logical thinking.",
            "This month, {$name} has made excellent progress in understanding fundamental programming concepts and applying them to projects.",
            "{$name} has been very engaged in class, actively participating in discussions and collaborating well with peers.",
        ];

        return $summaries[array_rand($summaries)];
    }

    private function generateStrengths(): string
    {
        $strengths = [
            "- Strong problem-solving abilities\n- Excellent attention to detail\n- Quick to grasp new concepts\n- Demonstrates creativity in projects",
            "- Good collaboration skills\n- Consistent class participation\n- Shows initiative in learning\n- Strong debugging skills",
            "- Excellent logical thinking\n- Good time management\n- Asks insightful questions\n- Shows enthusiasm for coding",
            "- Strong computational thinking\n- Good at breaking down complex problems\n- Demonstrates patience and persistence\n- Excellent code organization",
        ];

        return $strengths[array_rand($strengths)];
    }

    private function generateWeaknesses(): string
    {
        $weaknesses = [
            "- Could improve on syntax accuracy\n- Needs more practice with loops and conditionals\n- Should work on code commenting\n- Can be distracted occasionally",
            "- Needs to build confidence in independent problem-solving\n- Could improve debugging strategies\n- Should review fundamental concepts regularly\n- Time management could be better",
            "- Needs more practice with complex algorithms\n- Should work on code optimization\n- Could improve attention to edge cases\n- Needs to be more consistent with homework",
            "- Could improve focus during longer sessions\n- Needs more practice with error handling\n- Should work on project planning\n- Can be hesitant to ask for help",
        ];

        return $weaknesses[array_rand($weaknesses)];
    }

    private function generateNextSteps(): string
    {
        $nextSteps = [
            "- Continue practicing with loops and conditionals\n- Work on personal coding project\n- Review and strengthen fundamental concepts\n- Practice debugging techniques",
            "- Build confidence through independent challenges\n- Complete additional practice exercises\n- Explore advanced topics in area of interest\n- Work on larger, more complex projects",
            "- Practice with algorithm challenges\n- Focus on code optimization techniques\n- Develop stronger project planning skills\n- Participate in coding competitions",
            "- Improve focus and time management\n- Work on consistent homework completion\n- Practice with real-world coding scenarios\n- Build portfolio of completed projects",
        ];

        return $nextSteps[array_rand($nextSteps)];
    }
}
