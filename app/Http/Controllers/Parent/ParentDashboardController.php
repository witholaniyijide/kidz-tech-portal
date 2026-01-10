<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\ParentNotification;
use App\Models\Student;
use App\Models\StudentProgress;
use App\Models\TutorReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentDashboardController extends Controller
{
    /**
     * Display the parent dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        // Get all children of this parent using guardian_student relationship
        $children = $user->guardiansOf()->with(['tutor'])->get()
            ->map(function ($child) {
                // Calculate current stage based on curriculum progress
                $child->current_stage = $this->calculateCurrentStage($child);
                return $child;
            });

        if ($children->isEmpty()) {
            return view('parent.no-children');
        }

        // Get selected child from session, or use first child as default
        $selectedChildId = session('selected_child_id');
        $selectedChild = null;

        if ($selectedChildId) {
            $selectedChild = $children->firstWhere('id', $selectedChildId);
        }

        // If not found in session or session child not in parent's children, use first child
        if (!$selectedChild) {
            $selectedChild = $children->first();
            session(['selected_child_id' => $selectedChild->id]);
        }

        // Get student IDs
        $studentIds = $children->pluck('id');

        // Get recent director-approved reports for all children
        $recentReports = TutorReport::whereIn('student_id', $studentIds)
            ->where('status', 'approved-by-director')
            ->with(['student', 'tutor'])
            ->orderBy('approved_by_director_at', 'desc')
            ->take(5)
            ->get();

        // Calculate overall progress percentage
        $totalProgress = 0;
        foreach ($children as $child) {
            $totalProgress += $child->progressPercentage();
        }
        $overallProgress = $children->count() > 0 ? round($totalProgress / $children->count()) : 0;

        // Get milestones completed based on current curriculum level
        // Use starting_course_level if numeric, otherwise calculate from approved reports
        $milestonesCompleted = 0;
        if (is_numeric($selectedChild->starting_course_level)) {
            $milestonesCompleted = max(0, (int)$selectedChild->starting_course_level - 1);
        } else {
            // Count approved reports as proxy for milestones completed
            $milestonesCompleted = TutorReport::where('student_id', $selectedChild->id)
                ->where('status', 'approved-by-director')
                ->count();
        }

        // Get last report date
        $lastReport = TutorReport::whereIn('student_id', $studentIds)
            ->where('status', 'approved-by-director')
            ->orderBy('approved_by_director_at', 'desc')
            ->first();

        // Get next milestone for selected child
        $nextMilestone = $this->getNextMilestone($selectedChild);

        // Get unread notifications count
        $unreadNotifications = ParentNotification::where('parent_id', $user->id)
            ->whereNull('read_at')
            ->count();

        // Curriculum roadmap (12 courses)
        $curriculumRoadmap = $this->getCurriculumRoadmap($selectedChild);

        return view('parent.dashboard', compact(
            'children',
            'selectedChild',
            'recentReports',
            'overallProgress',
            'milestonesCompleted',
            'lastReport',
            'nextMilestone',
            'unreadNotifications',
            'curriculumRoadmap'
        ));
    }

    /**
     * Get the next milestone for a student.
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

        // Get actual course statuses based on starting_course_level and approved reports
        $curriculumWithStatuses = $student->getCurriculumWithStatuses();

        // Find the first course that is not completed (either ongoing or upcoming)
        $nextCourse = null;
        foreach ($curriculumWithStatuses as $course) {
            if ($course['status'] !== 'completed') {
                $nextCourse = $course;
                break;
            }
        }

        // If no next course found, all courses are completed
        if (!$nextCourse) {
            return [
                'title' => 'Curriculum Completed!',
                'description' => 'All 12 stages completed. Congratulations!',
                'stage' => 12,
            ];
        }

        // Get next course details
        $currentStage = $nextCourse['id'];
        $title = $nextCourse['title'];
        $description = 'Stage ' . $currentStage . ' of 12';

        // If custom roadmap_next_milestone is set, use it as description
        if ($student->roadmap_next_milestone) {
            $description = $student->roadmap_next_milestone;
        } else {
            // Optionally enhance with goals from latest report
            $latestReport = TutorReport::where('student_id', $student->id)
                ->where('status', 'approved-by-director')
                ->orderBy('approved_by_director_at', 'desc')
                ->first();

            if ($latestReport && $latestReport->goals_next_month) {
                $description = $latestReport->goals_next_month;
            }
        }

        return [
            'title' => $title,
            'description' => $description,
            'stage' => $currentStage,
        ];
    }

    /**
     * Get the curriculum roadmap with progress for a student.
     * Uses explicit progression system if student has starting_course_id set.
     */
    private function getCurriculumRoadmap(Student $student): array
    {
        $icons = [
            1 => 'computer',
            2 => 'code',
            3 => 'puzzle',
            4 => 'brain',
            5 => 'palette',
            6 => 'gamepad',
            7 => 'smartphone',
            8 => 'globe',
            9 => 'terminal',
            10 => 'shield',
            11 => 'cpu',
            12 => 'robot',
        ];

        // Use explicit progression if student has it, otherwise fall back to legacy
        if ($student->usesExplicitProgression()) {
            $curriculumWithStatuses = $student->getExplicitCurriculumWithStatuses();
        } else {
            $curriculumWithStatuses = $student->getCurriculumWithStatuses();
        }

        $courses = [];
        foreach ($curriculumWithStatuses as $course) {
            $status = $course['status'];
            // Map status to display status
            $displayStatus = match($status) {
                'completed' => 'completed',
                'ongoing' => 'current',
                default => 'upcoming',
            };

            // Use 'level' for explicit system, 'id' for legacy
            $courseId = $course['level'] ?? $course['id'];

            $courses[] = [
                'id' => $courseId,
                'title' => $course['title'] ?? $course['full_name'] ?? "Level {$courseId}",
                'icon' => $icons[$courseId] ?? 'book',
                'status' => $displayStatus,
                'progress' => $displayStatus === 'completed' ? 100 : ($displayStatus === 'current' ? ($student->roadmap_progress ?? 0) : 0),
            ];
        }

        return $courses;
    }

    /**
     * Switch selected child on dashboard.
     */
    public function switchChild(Request $request)
    {
        $user = Auth::user();
        $studentId = $request->input('student_id');

        // Verify this student belongs to the parent
        $student = $user->guardiansOf()->where('students.id', $studentId)->first();

        if (!$student) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            abort(403, 'Unauthorized');
        }

        // Store selected child in session
        session(['selected_child_id' => $studentId]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'student' => $student,
                'roadmap' => $this->getCurriculumRoadmap($student),
                'nextMilestone' => $this->getNextMilestone($student),
            ]);
        }

        return redirect()->back();
    }

    /**
     * Calculate the current stage based on course statuses.
     */
    private function calculateCurrentStage(Student $student): int
    {
        $courseStatuses = $student->getCurriculumWithStatuses();

        // Find the current (ongoing) course
        foreach ($courseStatuses as $course) {
            if ($course['status'] === 'ongoing') {
                return $course['id'];
            }
        }

        // If no ongoing course, find the last completed + 1
        $lastCompleted = 0;
        foreach ($courseStatuses as $course) {
            if ($course['status'] === 'completed') {
                $lastCompleted = max($lastCompleted, $course['id']);
            }
        }

        return min($lastCompleted + 1, 12);
    }
}
