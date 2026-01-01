<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Student;
use App\Models\StudentProgress;
use App\Models\TutorReport;
use App\Models\User;
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
                // Calculate current stage based on course statuses
                $child->current_stage = $this->calculateCurrentStage($child);
                return $child;
            });

        return view('parent.children.index', compact('children'));
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

        // Calculate current stage dynamically
        $currentStage = $this->calculateCurrentStage($student);

        // Get all progress milestones
        $milestones = StudentProgress::where('student_id', $student->id)
            ->orderBy('id')
            ->get();

        // Get director-approved reports
        $reports = $student->approvedReports()
            ->with(['tutor'])
            ->take(5)
            ->get();

        // Get curriculum roadmap
        $curriculumRoadmap = $this->getCurriculumRoadmap($student);

        // Format class schedule
        $classSchedule = $this->formatClassSchedule($student);

        return view('parent.children.show', compact(
            'student',
            'progressPercentage',
            'currentStage',
            'milestones',
            'reports',
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

        // Get course statuses from student model (uses starting_course_level and reports)
        $curriculumWithStatuses = $student->getCurriculumWithStatuses();

        $courses = [];
        foreach ($curriculumWithStatuses as $course) {
            $status = $course['status'];
            // Map status to display status
            $displayStatus = match($status) {
                'completed' => 'completed',
                'ongoing' => 'current',
                default => 'upcoming',
            };

            $courses[] = [
                'id' => $course['id'],
                'title' => $course['title'],
                'icon' => $icons[$course['id']] ?? 'book',
                'status' => $displayStatus,
                'progress' => $displayStatus === 'completed' ? 100 : ($displayStatus === 'current' ? ($student->roadmap_progress ?? 0) : 0),
            ];
        }

        return $courses;
    }

    /**
     * Request a new course for a child.
     */
    public function requestCourse(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_name' => 'required|string|max:255',
            'message' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        $student = Student::findOrFail($request->student_id);

        // Verify parent owns this student
        abort_unless($user->isGuardianOf($student), 403, 'Unauthorized');

        // Find director to send message to
        $director = User::whereHas('roles', function ($query) {
            $query->where('name', 'director');
        })->first();

        if (!$director) {
            return response()->json(['error' => 'No director found to send message to'], 500);
        }

        // Build message body
        $body = "I want to request a new course for my child:\n\n";
        $body .= "Student: " . $student->first_name . " " . $student->last_name . "\n";
        $body .= "Requested Course: " . $request->course_name . "\n";
        if ($request->message) {
            $body .= "\nAdditional Message:\n" . $request->message;
        }

        // Create message to director
        Message::create([
            'sender_id' => $user->id,
            'recipient_id' => $director->id,
            'student_id' => $student->id,
            'subject' => 'New Course Request: ' . $request->course_name . ' for ' . $student->first_name,
            'body' => $body,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Your course request has been sent to the Director.'
        ]);
    }
}
