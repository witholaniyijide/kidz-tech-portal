<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Certification;
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
        $children = $user->guardiansOf()->with(['tutor'])->get();

        if ($children->isEmpty()) {
            return view('parent.no-children');
        }

        // Get first child as default selected child (can switch later)
        $selectedChild = $children->first();

        // Get student IDs
        $studentIds = $children->pluck('id');

        // Get recent director-approved reports for all children
        $recentReports = TutorReport::whereIn('student_id', $studentIds)
            ->where('status', 'approved-by-director')
            ->with(['student', 'tutor'])
            ->orderBy('approved_by_director_at', 'desc')
            ->take(5)
            ->get();

        // Get recent certifications for all children
        $recentCertifications = Certification::whereIn('student_id', $studentIds)
            ->where('status', 'active')
            ->with(['student'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Calculate overall progress percentage
        $totalProgress = 0;
        foreach ($children as $child) {
            $totalProgress += $child->progressPercentage();
        }
        $overallProgress = $children->count() > 0 ? round($totalProgress / $children->count()) : 0;

        // Get milestones completed across all children
        $milestonesCompleted = StudentProgress::whereIn('student_id', $studentIds)
            ->where('completed', true)
            ->count();

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
            'recentCertifications',
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
        // Return roadmap_next_milestone if set
        if ($student->roadmap_next_milestone) {
            return [
                'title' => $student->roadmap_next_milestone,
                'stage' => $student->roadmap_stage ?? 1,
            ];
        }

        // Otherwise, calculate from progress
        $lastCompleted = StudentProgress::where('student_id', $student->id)
            ->where('completed', true)
            ->orderBy('completed_at', 'desc')
            ->first();

        $nextMilestone = StudentProgress::where('student_id', $student->id)
            ->where('completed', false)
            ->orderBy('id')
            ->first();

        if ($nextMilestone) {
            return [
                'title' => $nextMilestone->title,
                'description' => $nextMilestone->description,
            ];
        }

        return null;
    }

    /**
     * Get the curriculum roadmap with progress for a student.
     */
    private function getCurriculumRoadmap(Student $student): array
    {
        $courses = [
            ['id' => 1, 'title' => 'Introduction to Computer Science', 'icon' => 'computer'],
            ['id' => 2, 'title' => 'Coding & Fundamental Concepts', 'icon' => 'code'],
            ['id' => 3, 'title' => 'Scratch Programming', 'icon' => 'puzzle'],
            ['id' => 4, 'title' => 'Artificial Intelligence', 'icon' => 'brain'],
            ['id' => 5, 'title' => 'Graphic Design', 'icon' => 'palette'],
            ['id' => 6, 'title' => 'Game Development', 'icon' => 'gamepad'],
            ['id' => 7, 'title' => 'Mobile App Development', 'icon' => 'smartphone'],
            ['id' => 8, 'title' => 'Website Development', 'icon' => 'globe'],
            ['id' => 9, 'title' => 'Python Programming', 'icon' => 'terminal'],
            ['id' => 10, 'title' => 'Digital Literacy & Safety/Security', 'icon' => 'shield'],
            ['id' => 11, 'title' => 'Machine Learning', 'icon' => 'cpu'],
            ['id' => 12, 'title' => 'Robotics', 'icon' => 'robot'],
        ];

        // Get current stage from student
        $currentStage = $student->roadmap_stage ?? 1;
        $progress = $student->roadmap_progress ?? 0;

        // Mark courses as completed, current, or upcoming
        foreach ($courses as $key => $course) {
            if ($course['id'] < $currentStage) {
                $courses[$key]['status'] = 'completed';
                $courses[$key]['progress'] = 100;
            } elseif ($course['id'] == $currentStage) {
                $courses[$key]['status'] = 'current';
                $courses[$key]['progress'] = $progress;
            } else {
                $courses[$key]['status'] = 'upcoming';
                $courses[$key]['progress'] = 0;
            }
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
}
