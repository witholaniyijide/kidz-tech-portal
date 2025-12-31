<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentProgress;
use App\Models\TutorReport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentPerformanceController extends Controller
{
    /**
     * Display the performance page for all children or a specific child.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get all children
        $children = $user->guardiansOf()->with(['tutor'])->get();

        if ($children->isEmpty()) {
            return view('parent.no-children');
        }

        // Get selected child (from request or session or first child)
        $selectedChildId = $request->input('student_id', session('selected_child_id', $children->first()->id));
        $selectedChild = $children->firstWhere('id', $selectedChildId) ?? $children->first();

        // Store in session
        session(['selected_child_id' => $selectedChild->id]);

        // Get performance data for the selected child
        $performanceData = $this->getPerformanceData($selectedChild);

        // Get monthly progress trendline (last 6 months)
        $monthlyProgress = $this->getMonthlyProgress($selectedChild);

        // Get milestones achieved
        $milestones = $this->getMilestonesAchieved($selectedChild);

        // Get next recommended milestone
        $nextMilestone = $this->getNextMilestone($selectedChild);

        // Get radar chart data
        $radarData = $this->getRadarChartData($selectedChild);

        return view('parent.performance.index', compact(
            'children',
            'selectedChild',
            'performanceData',
            'monthlyProgress',
            'milestones',
            'nextMilestone',
            'radarData'
        ));
    }

    /**
     * Get performance data for a specific child via AJAX.
     */
    public function getChildPerformance(Request $request, Student $student)
    {
        $user = Auth::user();

        // Ensure this student belongs to the logged-in parent
        abort_unless(
            $user->isGuardianOf($student) || $user->hasRole('admin'),
            403,
            'Unauthorized'
        );

        return response()->json([
            'performanceData' => $this->getPerformanceData($student),
            'monthlyProgress' => $this->getMonthlyProgress($student),
            'milestones' => $this->getMilestonesAchieved($student),
            'nextMilestone' => $this->getNextMilestone($student),
            'radarData' => $this->getRadarChartData($student),
        ]);
    }

    /**
     * Get overall performance data for a student.
     */
    private function getPerformanceData(Student $student): array
    {
        // Get approved reports for calculations
        $approvedReports = TutorReport::where('student_id', $student->id)
            ->where('status', 'approved-by-director')
            ->get();

        // Calculate average performance rating
        $avgRating = $approvedReports->avg('rating') ?? 0;

        // Calculate total XP points from progress
        $totalPoints = StudentProgress::where('student_id', $student->id)
            ->where('completed', true)
            ->sum('points');

        // Calculate progress percentage
        $progressPercentage = $student->progressPercentage();

        return [
            'overall_progress' => $progressPercentage,
            'average_rating' => round($avgRating, 1),
            'total_points' => $totalPoints,
            'total_reports' => $approvedReports->count(),
            'current_stage' => $student->roadmap_stage ?? 1,
        ];
    }

    /**
     * Get monthly progress trendline data.
     */
    private function getMonthlyProgress(Student $student): array
    {
        $months = [];
        $currentDate = Carbon::now();

        // Get last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $date = $currentDate->copy()->subMonths($i);
            $monthName = $date->format('M');
            $year = $date->format('Y');

            // Get report for this month if exists
            $report = TutorReport::where('student_id', $student->id)
                ->where('status', 'approved-by-director')
                ->where('month', $date->format('F'))
                ->where('year', $year)
                ->first();

            $months[] = [
                'month' => $monthName,
                'year' => $year,
                'progress' => $report ? ($report->rating ?? 0) * 20 : 0, // Convert 1-5 to percentage
                'has_report' => $report !== null,
            ];
        }

        return $months;
    }

    /**
     * Get milestones achieved by the student.
     */
    private function getMilestonesAchieved(Student $student): array
    {
        // Define all curriculum stages
        $curriculumStages = [
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

        $milestones = [];

        // Get current level (use current_level, then roadmap_stage, then starting_course_level as fallback)
        $currentLevel = $student->current_level
            ?? $student->roadmap_stage
            ?? $student->starting_course_level
            ?? 1;

        // All stages before the current level are considered completed
        // If current_level is 5, they've completed 1-4, currently on 5
        $completedLevels = max(0, $currentLevel - 1);

        for ($i = 1; $i <= $completedLevels; $i++) {
            if (isset($curriculumStages[$i])) {
                $milestones[] = [
                    'id' => $i,
                    'title' => $curriculumStages[$i],
                    'description' => 'Stage ' . $i . ' of 12 completed',
                    'points' => $i * 100, // 100 points per stage
                    'completed_at' => null, // We don't have exact dates for curriculum stages
                ];
            }
        }

        // Also include any explicitly completed StudentProgress milestones
        $progressMilestones = StudentProgress::where('student_id', $student->id)
            ->where('completed', true)
            ->orderBy('completed_at', 'desc')
            ->get()
            ->map(function ($milestone) {
                return [
                    'id' => 'p-' . $milestone->id,
                    'title' => $milestone->title,
                    'description' => $milestone->description,
                    'points' => $milestone->points,
                    'completed_at' => $milestone->completed_at ? $milestone->completed_at->format('M d, Y') : null,
                ];
            })
            ->toArray();

        // Merge and return (curriculum stages first, then custom milestones)
        return array_merge(array_reverse($milestones), $progressMilestones);
    }

    /**
     * Get the next recommended milestone.
     */
    private function getNextMilestone(Student $student): ?array
    {
        // Define all curriculum stages
        $curriculumStages = [
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

        // Get current level (use current_level, then roadmap_stage, then starting_course_level as fallback)
        $currentLevel = $student->current_level
            ?? $student->roadmap_stage
            ?? $student->starting_course_level
            ?? 1;

        // Check if student has a custom next milestone set
        if ($student->roadmap_next_milestone) {
            return [
                'title' => $student->roadmap_next_milestone,
                'description' => 'Stage ' . $currentLevel . ' of 12',
                'stage' => $currentLevel,
                'progress' => round(($currentLevel / 12) * 100),
            ];
        }

        // Primary: Use curriculum stage as the next milestone
        if ($currentLevel <= 12 && isset($curriculumStages[$currentLevel])) {
            $description = 'Stage ' . $currentLevel . ' of 12';

            // Optionally enhance with goals from latest report
            $latestReport = TutorReport::where('student_id', $student->id)
                ->where('status', 'approved-by-director')
                ->orderBy('approved_by_director_at', 'desc')
                ->first();

            if ($latestReport && $latestReport->goals_next_month) {
                $description = $latestReport->goals_next_month;
            }

            return [
                'title' => $curriculumStages[$currentLevel],
                'description' => $description,
                'stage' => $currentLevel,
                'progress' => round((($currentLevel - 1) / 12) * 100),
            ];
        }

        // Student has completed all stages
        if ($currentLevel > 12) {
            return [
                'title' => 'Curriculum Completed!',
                'description' => 'All 12 stages completed - Advanced learning continues',
                'stage' => 12,
                'progress' => 100,
            ];
        }

        // Fallback - shouldn't normally be reached
        return [
            'title' => $curriculumStages[1],
            'description' => 'Stage 1 of 12',
            'stage' => 1,
            'progress' => 0,
        ];
    }

    /**
     * Get radar chart data for the 4 performance axes.
     * Axes: Progress, Engagement, Technical Skills, Project Completion
     */
    private function getRadarChartData(Student $student): array
    {
        // Get approved reports for calculations
        $approvedReports = TutorReport::where('student_id', $student->id)
            ->where('status', 'approved-by-director')
            ->get();

        // Progress: Based on roadmap_progress
        $progress = $student->roadmap_progress ?? $student->progressPercentage();

        // Engagement: Based on average performance rating from reports
        $engagement = 0;
        if ($approvedReports->count() > 0) {
            $avgRating = $approvedReports->avg('rating') ?? 0;
            $engagement = min(100, $avgRating * 20); // Convert 1-5 scale to 0-100
        }

        // Technical Skills: Based on skills_mastered count
        $technicalSkills = 0;
        $totalSkills = 0;
        foreach ($approvedReports as $report) {
            $skills = is_array($report->skills_mastered) ? $report->skills_mastered : [];
            $totalSkills += count($skills);
        }
        // Cap at 100, assume 20 skills = 100%
        $technicalSkills = min(100, ($totalSkills / 20) * 100);

        // Project Completion: Based on projects completed
        $projectCompletion = 0;
        $totalProjects = 0;
        foreach ($approvedReports as $report) {
            $projects = is_array($report->projects) ? $report->projects : [];
            $totalProjects += count($projects);
        }
        // Cap at 100, assume 10 projects = 100%
        $projectCompletion = min(100, ($totalProjects / 10) * 100);

        return [
            'labels' => ['Progress', 'Engagement', 'Technical Skills', 'Project Completion'],
            'data' => [
                round($progress),
                round($engagement),
                round($technicalSkills),
                round($projectCompletion),
            ],
        ];
    }
}
