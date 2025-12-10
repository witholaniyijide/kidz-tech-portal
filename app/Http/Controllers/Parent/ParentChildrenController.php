<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Certification;
use App\Models\Student;
use App\Models\StudentProgress;
use App\Models\TutorReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentChildrenController extends Controller
{
    /**
     * Display all children linked to the parent.
     */
    public function index()
    {
        $user = Auth::user();

        // Get all children with their tutors and progress
        $children = $user->guardiansOf()
            ->with(['tutor'])
            ->get()
            ->map(function ($child) {
                $child->progress_percentage = $child->progressPercentage();
                return $child;
            });

        return view('parent.children.index', compact('children'));
    }

    /**
     * Display a specific child's profile.
     */
    public function show(Student $student)
    {
        $user = Auth::user();

        // Ensure this student belongs to the logged-in parent
        abort_unless(
            $user->isGuardianOf($student) || $user->hasRole('admin'),
            403,
            'Unauthorized: You can only view your own children.'
        );

        // Load relationships
        $student->load(['tutor']);

        // Get progress percentage
        $progressPercentage = $student->progressPercentage();

        // Get all progress milestones
        $milestones = StudentProgress::where('student_id', $student->id)
            ->orderBy('id')
            ->get();

        // Get director-approved reports
        $reports = $student->approvedReports()
            ->with(['tutor'])
            ->take(5)
            ->get();

        // Get certifications
        $certifications = Certification::where('student_id', $student->id)
            ->where('status', 'active')
            ->orderBy('issue_date', 'desc')
            ->get();

        // Get curriculum roadmap
        $curriculumRoadmap = $this->getCurriculumRoadmap($student);

        // Format class schedule
        $classSchedule = $this->formatClassSchedule($student);

        return view('parent.children.show', compact(
            'student',
            'progressPercentage',
            'milestones',
            'reports',
            'certifications',
            'curriculumRoadmap',
            'classSchedule'
        ));
    }

    /**
     * Format the class schedule for display.
     */
    private function formatClassSchedule(Student $student): array
    {
        $schedule = $student->class_schedule ?? [];

        if (!is_array($schedule)) {
            return [];
        }

        $formatted = [];
        foreach ($schedule as $item) {
            if (is_array($item)) {
                $formatted[] = [
                    'day' => $item['day'] ?? '',
                    'time' => $item['time'] ?? '',
                ];
            } elseif (is_string($item)) {
                $formatted[] = ['schedule' => $item];
            }
        }

        return $formatted;
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

        $currentStage = $student->roadmap_stage ?? 1;
        $progress = $student->roadmap_progress ?? 0;

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
}
