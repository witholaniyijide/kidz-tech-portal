<?php

namespace App\Services;

use App\Models\Student;
use App\Models\StudentProgress;
use App\Models\TutorReport;
use Carbon\Carbon;

class StudentProgressService
{
    /**
     * Get comprehensive progress data for a student.
     */
    public function getProgressData(Student $student): array
    {
        return [
            'overall_percentage' => $student->progressPercentage(),
            'current_stage' => $student->roadmap_stage ?? 1,
            'stage_progress' => $student->roadmap_progress ?? 0,
            'next_milestone' => $student->roadmap_next_milestone,
            'milestones' => $this->getMilestones($student),
            'skills' => $this->getSkillsSummary($student),
            'monthly_trend' => $this->getMonthlyTrend($student),
            'radar_data' => $this->getRadarData($student),
        ];
    }

    /**
     * Get all milestones for a student.
     */
    public function getMilestones(Student $student): array
    {
        $milestones = StudentProgress::where('student_id', $student->id)
            ->orderBy('id')
            ->get();

        $completedCount = $milestones->where('completed', true)->count();
        $totalCount = $milestones->count();

        return [
            'list' => $milestones->map(function ($milestone) {
                return [
                    'id' => $milestone->id,
                    'title' => $milestone->title,
                    'description' => $milestone->description,
                    'milestone_code' => $milestone->milestone_code,
                    'points' => $milestone->points,
                    'completed' => $milestone->completed,
                    'completed_at' => $milestone->completed_at?->format('M d, Y'),
                ];
            })->toArray(),
            'completed_count' => $completedCount,
            'total_count' => $totalCount,
            'total_points' => $milestones->where('completed', true)->sum('points'),
        ];
    }

    /**
     * Get skills summary from reports.
     */
    public function getSkillsSummary(Student $student): array
    {
        $approvedReports = TutorReport::where('student_id', $student->id)
            ->where('status', 'approved-by-director')
            ->get();

        $masteredSkills = [];
        $newSkills = [];

        foreach ($approvedReports as $report) {
            if (is_array($report->skills_mastered)) {
                $masteredSkills = array_merge($masteredSkills, $report->skills_mastered);
            }
            if (is_array($report->new_skills)) {
                $newSkills = array_merge($newSkills, $report->new_skills);
            }
        }

        // Remove duplicates
        $masteredSkills = array_unique($masteredSkills);
        $newSkills = array_unique($newSkills);

        return [
            'mastered' => array_values($masteredSkills),
            'new' => array_values($newSkills),
            'total_count' => count($masteredSkills) + count($newSkills),
        ];
    }

    /**
     * Get monthly progress trend for the last 6 months.
     */
    public function getMonthlyTrend(Student $student): array
    {
        $months = [];
        $currentDate = Carbon::now();

        for ($i = 5; $i >= 0; $i--) {
            $date = $currentDate->copy()->subMonths($i);
            $monthName = $date->format('M');
            $year = $date->format('Y');
            $fullMonth = $date->format('F');

            // Get report for this month
            $report = TutorReport::where('student_id', $student->id)
                ->where('status', 'approved-by-director')
                ->where('month', $fullMonth)
                ->where('year', $year)
                ->first();

            // Calculate progress value
            $progressValue = 0;
            if ($report) {
                // Use rating converted to percentage, or performance_rating
                $progressValue = $report->rating
                    ? $report->rating * 20
                    : ($report->performance_rating ?? 0);
            }

            $months[] = [
                'month' => $monthName,
                'year' => $year,
                'full_month' => $fullMonth,
                'progress' => $progressValue,
                'has_report' => $report !== null,
                'report_id' => $report?->id,
            ];
        }

        return $months;
    }

    /**
     * Get radar chart data (4 axes: Progress, Engagement, Technical Skills, Project Completion).
     */
    public function getRadarData(Student $student): array
    {
        $approvedReports = TutorReport::where('student_id', $student->id)
            ->where('status', 'approved-by-director')
            ->get();

        // Progress: Based on roadmap_progress
        $progress = $student->roadmap_progress ?? $student->progressPercentage();

        // Engagement: Based on average rating
        $engagement = 0;
        if ($approvedReports->count() > 0) {
            $avgRating = $approvedReports->avg('rating') ?? 0;
            $engagement = min(100, $avgRating * 20);
        }

        // Technical Skills: Based on skills count
        $skillsCount = 0;
        foreach ($approvedReports as $report) {
            $skills = is_array($report->skills_mastered) ? $report->skills_mastered : [];
            $skillsCount += count($skills);
        }
        $technicalSkills = min(100, ($skillsCount / 20) * 100);

        // Project Completion: Based on projects count
        $projectsCount = 0;
        foreach ($approvedReports as $report) {
            $projects = is_array($report->projects) ? $report->projects : [];
            $projectsCount += count($projects);
        }
        $projectCompletion = min(100, ($projectsCount / 10) * 100);

        return [
            'labels' => ['Progress', 'Engagement', 'Technical Skills', 'Project Completion'],
            'data' => [
                round($progress),
                round($engagement),
                round($technicalSkills),
                round($projectCompletion),
            ],
            'max' => 100,
        ];
    }

    /**
     * Get the next recommended milestone.
     */
    public function getNextMilestone(Student $student): ?array
    {
        // Check custom next milestone
        if ($student->roadmap_next_milestone) {
            return [
                'title' => $student->roadmap_next_milestone,
                'type' => 'custom',
            ];
        }

        // Get next incomplete milestone from progress
        $nextMilestone = StudentProgress::where('student_id', $student->id)
            ->where('completed', false)
            ->orderBy('id')
            ->first();

        if ($nextMilestone) {
            return [
                'id' => $nextMilestone->id,
                'title' => $nextMilestone->title,
                'description' => $nextMilestone->description,
                'points' => $nextMilestone->points,
                'type' => 'milestone',
            ];
        }

        // Generate from current stage
        $currentStage = $student->roadmap_stage ?? 1;
        $courses = $this->getCoursesList();

        if (isset($courses[$currentStage])) {
            return [
                'title' => "Complete " . $courses[$currentStage],
                'description' => isset($courses[$currentStage + 1])
                    ? "Next: " . $courses[$currentStage + 1]
                    : "Final stage!",
                'type' => 'stage',
            ];
        }

        return null;
    }

    /**
     * Get list of courses.
     */
    private function getCoursesList(): array
    {
        return [
            1 => 'Introduction to Computer Science',
            2 => 'Coding & Fundamental Concepts',
            3 => 'Scratch Programming',
            4 => 'Artificial Intelligence',
            5 => 'Graphic Design',
            6 => 'Game Development',
            7 => 'Mobile App Development',
            8 => 'Website Development',
            9 => 'Python Programming',
            10 => 'Digital Literacy & Safety/Security',
            11 => 'Machine Learning',
            12 => 'Robotics',
        ];
    }

    /**
     * Calculate overall stats for multiple students (for parent dashboard).
     */
    public function getAggregateStats(array $studentIds): array
    {
        $totalProgress = 0;
        $totalMilestones = 0;
        $totalPoints = 0;
        $totalReports = 0;

        foreach ($studentIds as $studentId) {
            $student = Student::find($studentId);
            if (!$student) continue;

            $totalProgress += $student->progressPercentage();
            $totalMilestones += StudentProgress::where('student_id', $studentId)
                ->where('completed', true)
                ->count();
            $totalPoints += StudentProgress::where('student_id', $studentId)
                ->where('completed', true)
                ->sum('points');
            $totalReports += TutorReport::where('student_id', $studentId)
                ->where('status', 'approved-by-director')
                ->count();
        }

        $studentCount = count($studentIds);

        return [
            'average_progress' => $studentCount > 0 ? round($totalProgress / $studentCount) : 0,
            'total_milestones' => $totalMilestones,
            'total_points' => $totalPoints,
            'total_reports' => $totalReports,
        ];
    }
}
