<?php

namespace App\Http\Controllers\Director;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Student;
use App\Models\StudentCourseProgress;
use App\Models\Tutor;
use App\Models\User;
use App\Services\CourseCompletionNotificationService;
use App\Services\ParentAccountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DirectorStudentController extends Controller
{
    protected ParentAccountService $parentAccountService;

    public function __construct(ParentAccountService $parentAccountService)
    {
        $this->parentAccountService = $parentAccountService;
    }
    /**
     * Display a listing of students.
     */
    public function index(Request $request)
    {
        $query = Student::with(['tutor', 'parent', 'guardians', 'currentCourse']);

        // Filter by search (name, email, student_id)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Get counts for cards
        $totalStudents = Student::count();
        $activeStudents = Student::where('status', 'active')->count();
        $inactiveStudents = Student::where('status', 'inactive')->count();
        $graduatedStudents = Student::whereIn('status', ['graduated', 'withdrawn'])->count();

        $students = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('director.students.index', compact(
            'students',
            'totalStudents',
            'activeStudents',
            'inactiveStudents',
            'graduatedStudents'
        ));
    }

    /**
     * Show the form for creating a new student.
     */
    public function create()
    {
        $tutors = Tutor::where('status', 'active')->get();
        $parents = User::whereHas('roles', function ($q) {
            $q->where('name', 'parent');
        })->get();
        $courses = Course::where('is_active', true)->orderBy('sort_order')->get();

        return view('director.students.create', compact('tutors', 'parents', 'courses'));
    }

    /**
     * Store a newly created student.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:students,email',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:500',
            'tutor_id' => 'nullable|exists:tutors,id',
            'parent_id' => 'nullable|exists:users,id',
            'status' => 'required|in:active,inactive,graduated,withdrawn',
            'enrollment_date' => 'nullable|date',
            'current_level' => 'nullable|string|max:100',
            'starting_course_level' => 'nullable|integer|min:1|max:12',
            'classes_per_week' => 'nullable|integer|min:1|max:7',
            'class_schedule' => 'nullable|array',
            'class_schedule.*.day' => 'required_with:class_schedule|string',
            'class_schedule.*.time' => 'required_with:class_schedule|string',
            'class_link' => 'nullable|url|max:500',
            'google_classroom_link' => 'nullable|url|max:500',
            'live_classroom_link' => 'nullable|url|max:500',
            'coding_experience' => 'nullable|string|max:1000',
            'career_interest' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:1000',

            // Course Progression
            'starting_course_id' => 'nullable|exists:courses,id',
            'current_course_id' => 'nullable|exists:courses,id',
            'completed_course_ids' => 'nullable|array',
            'completed_course_ids.*' => 'exists:courses,id',

            // Parent Information - Father
            'father_name' => 'nullable|string|max:255',
            'father_phone' => 'nullable|string|max:20',
            'father_email' => 'nullable|email|max:255|different:mother_email',
            'father_occupation' => 'nullable|string|max:255',
            'father_location' => 'nullable|string|max:255',

            // Parent Information - Mother
            'mother_name' => 'nullable|string|max:255',
            'mother_phone' => 'nullable|string|max:20',
            'mother_email' => 'nullable|email|max:255|different:father_email',
            'mother_occupation' => 'nullable|string|max:255',
            'mother_location' => 'nullable|string|max:255',
        ], [
            'father_email.different' => 'Father and mother cannot have the same email address.',
            'mother_email.different' => 'Mother and father cannot have the same email address.',
        ]);

        $student = null;

        DB::beginTransaction();
        try {
            // Generate student ID
            $validated['student_id'] = 'STU-' . strtoupper(uniqid());

            // class_schedule is automatically cast to JSON by the model

            // Remove course progression arrays from validated data (handled separately)
            $completedCourseIds = $validated['completed_course_ids'] ?? [];
            unset($validated['completed_course_ids']);

            $student = Student::create($validated);

            // Link to parent if provided (existing parent)
            if ($request->filled('parent_id')) {
                $student->guardians()->attach($request->parent_id);
            }

            // Handle completed courses
            if (!empty($completedCourseIds)) {
                $student->syncCompletedCourses($completedCourseIds, 'manual');

                // Send notifications for newly completed courses
                $notificationService = app(CourseCompletionNotificationService::class);
                foreach ($completedCourseIds as $courseId) {
                    $notificationService->notify($student, $courseId);
                }
            }

            DB::commit();

            // Create parent accounts after the transaction (for newly entered parent info)
            if ($student) {
                $this->parentAccountService->createParentAccountsForStudent($student);
            }

            return redirect()->route('director.students.index')
                ->with('success', 'Student created successfully. Parent accounts have been created and welcome emails sent.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create student: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified student.
     */
    public function show(Student $student)
    {
        $student->load(['tutor', 'parent', 'guardians', 'attendanceRecords', 'reports']);
        
        return view('director.students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified student.
     */
    public function edit(Student $student)
    {
        $tutors = Tutor::where('status', 'active')->get();
        $parents = User::whereHas('roles', function ($q) {
            $q->where('name', 'parent');
        })->get();
        $courses = Course::where('is_active', true)->orderBy('sort_order')->get();
        $student->load('completedCourses');

        return view('director.students.edit', compact('student', 'tutors', 'parents', 'courses'));
    }

    /**
     * Update the specified student.
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['nullable', 'email', Rule::unique('students')->ignore($student->id)],
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:500',
            'tutor_id' => 'nullable|exists:tutors,id',
            'parent_id' => 'nullable|exists:users,id',
            'status' => 'required|in:active,inactive,graduated,withdrawn',
            'enrollment_date' => 'nullable|date',
            'current_level' => 'nullable|string|max:100',
            'starting_course_level' => 'nullable|integer|min:1|max:12',
            'classes_per_week' => 'nullable|integer|min:1|max:7',
            'class_schedule' => 'nullable|array',
            'class_schedule.*.day' => 'required_with:class_schedule|string',
            'class_schedule.*.time' => 'required_with:class_schedule|string',
            'class_link' => 'nullable|url|max:500',
            'google_classroom_link' => 'nullable|url|max:500',
            'live_classroom_link' => 'nullable|url|max:500',
            'coding_experience' => 'nullable|string|max:1000',
            'career_interest' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:1000',

            // Course Progression
            'starting_course_id' => 'nullable|exists:courses,id',
            'current_course_id' => 'nullable|exists:courses,id',
            'completed_course_ids' => 'nullable|array',
            'completed_course_ids.*' => 'exists:courses,id',

            // Parent Information - Father
            'father_name' => 'nullable|string|max:255',
            'father_phone' => 'nullable|string|max:20',
            'father_email' => 'nullable|email|max:255|different:mother_email',
            'father_occupation' => 'nullable|string|max:255',
            'father_location' => 'nullable|string|max:255',

            // Parent Information - Mother
            'mother_name' => 'nullable|string|max:255',
            'mother_phone' => 'nullable|string|max:20',
            'mother_email' => 'nullable|email|max:255|different:father_email',
            'mother_occupation' => 'nullable|string|max:255',
            'mother_location' => 'nullable|string|max:255',
        ], [
            'father_email.different' => 'Father and mother cannot have the same email address.',
            'mother_email.different' => 'Mother and father cannot have the same email address.',
        ]);

        // Track if parent emails changed for creating new parent accounts
        $fatherEmailChanged = !empty($validated['father_email']) &&
            $validated['father_email'] !== $student->father_email;
        $motherEmailChanged = !empty($validated['mother_email']) &&
            $validated['mother_email'] !== $student->mother_email;

        // Get existing completed course IDs for comparison
        $existingCompletedCourseIds = $student->completedCourses()->pluck('courses.id')->toArray();

        DB::beginTransaction();
        try {
            // class_schedule is automatically cast to JSON by the model

            // Remove course progression arrays from validated data (handled separately)
            $completedCourseIds = $validated['completed_course_ids'] ?? [];
            unset($validated['completed_course_ids']);

            $student->update($validated);

            // Update parent link (for existing parent selection)
            if ($request->filled('parent_id')) {
                $student->guardians()->sync([$request->parent_id]);
            } else {
                $student->guardians()->detach();
            }

            // Handle completed courses
            $student->syncCompletedCourses($completedCourseIds, 'manual');

            // Find newly completed courses
            $newlyCompletedCourseIds = array_diff($completedCourseIds, $existingCompletedCourseIds);

            DB::commit();

            // Send notifications for newly completed courses (after commit)
            if (!empty($newlyCompletedCourseIds)) {
                $notificationService = app(CourseCompletionNotificationService::class);
                foreach ($newlyCompletedCourseIds as $courseId) {
                    $notificationService->notify($student, (int)$courseId);
                }
            }

            // Create parent accounts if new parent emails were added
            if ($fatherEmailChanged || $motherEmailChanged) {
                $this->parentAccountService->createParentAccountsForStudent($student->fresh());
            }

            return redirect()->route('director.students.index')
                ->with('success', 'Student updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update student: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified student.
     */
    public function destroy(Student $student)
    {
        try {
            // Force delete student (permanently remove from database so email can be reused)
            $student->forceDelete();

            return redirect()->route('director.students.index')
                ->with('success', 'Student deleted successfully. Email can now be reused.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete student: ' . $e->getMessage());
        }
    }
}
