<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\DirectorNotification;
use App\Models\Message;
use App\Models\Student;
use App\Models\StudentProgress;
use App\Models\TutorReport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ParentChildrenController extends Controller
{
    /**
     * Display all children linked to the parent.
     */
    public function index()
    {
        $user = Auth::user();

        // Get all visible children with their tutors and progress
        $children = $user->visibleChildren()
            ->with(['tutor', 'currentCourse'])
            ->get()
            ->map(function ($child) {
                try {
                    // Use appropriate progression system
                    if ($child->usesExplicitProgression()) {
                        $child->progress_percentage = $child->getExplicitProgressPercentage();
                        // Pass current course name for display
                        $child->current_course_name = $child->currentCourse?->full_name ?? $child->currentCourse?->name;
                    } else {
                        $child->progress_percentage = $child->progressPercentage();
                        $child->current_course_name = null;
                    }
                    // Calculate current stage based on course statuses
                    $child->current_stage = $this->calculateCurrentStage($child);
                } catch (\Exception $e) {
                    Log::error('Failed to calculate progress for child', [
                        'student_id' => $child->id,
                        'error' => $e->getMessage(),
                    ]);
                    $child->progress_percentage = 0;
                    $child->current_stage = 1;
                    $child->current_course_name = null;
                }
                return $child;
            });

        return view('parent.children.index', compact('children'));
    }

    /**
     * Calculate the current stage based on course statuses.
     */
    private function calculateCurrentStage(Student $student): int
    {
        // Use appropriate progression system
        if ($student->usesExplicitProgression()) {
            $courseStatuses = $student->getExplicitCurriculumWithStatuses();
        } else {
            $courseStatuses = $student->getCurriculumWithStatuses();
        }

        // Find the current (ongoing) course
        foreach ($courseStatuses as $course) {
            if ($course['status'] === 'ongoing') {
                return $course['level'] ?? $course['id'];
            }
        }

        // If no ongoing course, find the last completed + 1
        $lastCompleted = 0;
        foreach ($courseStatuses as $course) {
            if ($course['status'] === 'completed') {
                $courseLevel = $course['level'] ?? $course['id'];
                $lastCompleted = max($lastCompleted, $courseLevel);
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

        // Get progress percentage using appropriate progression system
        try {
            if ($student->usesExplicitProgression()) {
                $progressPercentage = $student->getExplicitProgressPercentage();
            } else {
                $progressPercentage = $student->progressPercentage();
            }
        } catch (\Exception $e) {
            Log::error('Explicit progress failed, trying legacy', [
                'student_id' => $student->id,
                'error' => $e->getMessage(),
            ]);
            try {
                $progressPercentage = $student->progressPercentage();
            } catch (\Exception $e2) {
                $progressPercentage = 0;
            }
        }

        // Calculate current stage dynamically
        try {
            $currentStage = $this->calculateCurrentStage($student);
        } catch (\Exception $e) {
            Log::error('Failed to calculate current stage', [
                'student_id' => $student->id,
                'error' => $e->getMessage(),
            ]);
            $currentStage = 1;
        }

        // Get all progress milestones
        $milestones = StudentProgress::where('student_id', $student->id)
            ->orderBy('id')
            ->get();

        // Get director-approved reports
        try {
            $reports = $student->approvedReports()
                ->with(['tutor'])
                ->take(5)
                ->get();
        } catch (\Exception $e) {
            Log::error('Failed to load approved reports', [
                'student_id' => $student->id,
                'error' => $e->getMessage(),
            ]);
            $reports = collect();
        }

        // Get curriculum roadmap
        try {
            $curriculumRoadmap = $this->getCurriculumRoadmap($student);
        } catch (\Exception $e) {
            Log::error('Failed to load curriculum roadmap', [
                'student_id' => $student->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            $curriculumRoadmap = [];
        }

        // Format class schedule
        try {
            $classSchedule = $this->formatClassSchedule($student);
        } catch (\Exception $e) {
            Log::error('Failed to format class schedule', [
                'student_id' => $student->id,
                'error' => $e->getMessage(),
            ]);
            $classSchedule = [];
        }

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
     * Uses explicit progression system if student has starting_course_id set.
     * Progress for current course is calculated from attendance records.
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

        // Check for auto-completion of current course based on attendance
        if ($student->usesExplicitProgression()) {
            try {
                $student->autoCompleteCourseIfReady();
                $student->refresh();
            } catch (\Exception $e) {
                Log::warning('Auto-complete check failed, continuing with current state', [
                    'student_id' => $student->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Use explicit progression if student has it, otherwise fall back to legacy
        $curriculumWithStatuses = null;
        $currentCourseProgress = 0;

        if ($student->usesExplicitProgression()) {
            try {
                $curriculumWithStatuses = $student->getExplicitCurriculumWithStatuses();
                $currentCourseProgress = $student->getCurrentCourseProgress();
            } catch (\Exception $e) {
                Log::error('Explicit progression failed, falling back to legacy', [
                    'student_id' => $student->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                // Fall through to legacy below
                $curriculumWithStatuses = null;
            }
        }

        // Fall back to legacy system if explicit failed or student uses legacy
        if ($curriculumWithStatuses === null) {
            $curriculumWithStatuses = $student->getCurriculumWithStatuses();
            $currentCourseProgress = $student->roadmap_progress ?? 0;
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

            // Determine progress for this course
            $progress = match($displayStatus) {
                'completed' => 100,
                'current' => $currentCourseProgress,
                default => 0,
            };

            $courses[] = [
                'id' => $courseId,
                'title' => $course['title'] ?? $course['full_name'] ?? "Level {$courseId}",
                'icon' => $icons[$courseId] ?? 'book',
                'status' => $displayStatus,
                'progress' => $progress,
            ];
        }

        return $courses;
    }

    /**
     * Get course learning data for a specific course.
     */
    public function getCourseLearningData(Request $request, Student $student)
    {
        try {
            $user = Auth::user();

            // Ensure this student belongs to the logged-in parent
            abort_unless(
                $user->isGuardianOf($student) || $user->hasRole('admin'),
                403,
                'Unauthorized: You can only view your own children.'
            );

            $courseId = $request->query('course_id');
            $courseTitle = $request->query('course_title');

            if (!$courseId) {
                return response()->json(['error' => 'Course ID is required'], 400);
            }

            // Build the course prefix pattern (e.g., "01 - ", "02 - ")
            $coursePrefix = str_pad($courseId, 2, '0', STR_PAD_LEFT) . ' - ';

            // Get all approved attendance records for this student
            $allRecords = AttendanceRecord::where('student_id', $student->id)
                ->where('status', 'approved')
                ->orderBy('class_date', 'desc')
                ->get();

            // Filter records that match this course and collect all available courses
            $matchingRecords = [];
            $availableCourses = []; // Courses the student has actually taken classes for

            foreach ($allRecords as $record) {
                $courses = $record->courses_covered;

                // Handle different formats of courses_covered
                if (is_string($courses)) {
                    $courses = json_decode($courses, true) ?? [$courses];
                }

                // Skip if no courses data
                if (!is_array($courses) || empty($courses)) {
                    // For older records without courses_covered, use topic directly
                    if ($record->topic) {
                        // These are legacy records - include them for this course if we have no other matches
                        continue;
                    }
                    continue;
                }

                // Collect all courses this student has records for
                foreach ($courses as $course) {
                    if (!empty($course) && !in_array($course, $availableCourses)) {
                        $availableCourses[] = $course;
                    }
                }

                foreach ($courses as $course) {
                    // Match by course prefix (e.g., "01 - Introduction to Computer Science")
                    if (str_starts_with($course, $coursePrefix)) {
                        $matchingRecords[] = $record;
                        break;
                    }
                    // Also match by title if it contains the course title
                    if ($courseTitle && stripos($course, $courseTitle) !== false) {
                        $matchingRecords[] = $record;
                        break;
                    }
                }
            }

            // Get unique topics covered from matching records
            $topicsSet = [];

            // If no matching records, check for legacy records without courses_covered
            if (count($matchingRecords) === 0 && count($availableCourses) === 0) {
                // All records are legacy (no courses_covered) - show all topics
                foreach ($allRecords as $record) {
                    if ($record->topic) {
                        $topicsSet[$record->topic] = true;
                    }
                }
            } else {
                // Use only matching records
                foreach ($matchingRecords as $record) {
                    if ($record->topic) {
                        $topicsSet[$record->topic] = true;
                    }
                }
            }

            $topics = array_keys($topicsSet);

            // Sort available courses naturally
            sort($availableCourses, SORT_NATURAL);

            return response()->json([
                'success' => true,
                'data' => [
                    'course_title' => $courseTitle,
                    'topics' => $topics,
                    'available_courses' => $availableCourses,
                    'has_matching_records' => count($matchingRecords) > 0,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load course learning data',
                'message' => $e->getMessage()
            ], 500);
        }
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

        // Create notification for director
        DirectorNotification::create([
            'user_id' => $director->id,
            'title' => 'New Course Request from Parent',
            'body' => $user->name . ' has requested "' . $request->course_name . '" for ' . $student->first_name . ' ' . $student->last_name,
            'type' => 'course_request',
            'is_read' => false,
            'meta' => [
                'student_id' => $student->id,
                'student_name' => $student->first_name . ' ' . $student->last_name,
                'parent_name' => $user->name,
                'course_name' => $request->course_name,
                'link' => route('director.messages.index'),
            ],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Your course request has been sent to the Director.'
        ]);
    }
}
