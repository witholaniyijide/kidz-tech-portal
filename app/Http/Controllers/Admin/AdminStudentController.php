<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\ActivityLog;
use App\Services\ParentAccountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminStudentController extends Controller
{
    protected ParentAccountService $parentAccountService;

    public function __construct(ParentAccountService $parentAccountService)
    {
        $this->parentAccountService = $parentAccountService;

        $this->middleware(['auth', 'verified']);
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->hasRole('admin')) {
                abort(403, 'Unauthorized access.');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of students.
     */
    public function index(Request $request)
    {
        $query = Student::with('tutor');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $students = $query->orderBy('created_at', 'desc')->paginate(20);

        // Statistics
        $stats = [
            'total' => Student::count(),
            'active' => Student::where('status', 'active')->count(),
            'inactive' => Student::where('status', 'inactive')->count(),
            'graduated' => Student::where('status', 'graduated')->count(),
            'withdrawn' => Student::where('status', 'withdrawn')->count(),
        ];

        return view('admin.students.index', compact('students', 'stats'));
    }

    /**
     * Show the form for creating a new student.
     */
    public function create()
    {
        $tutors = Tutor::where('status', 'active')->orderBy('first_name')->get();
        return view('admin.students.create', compact('tutors'));
    }

    /**
     * Store a newly created student.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Student Info
            'first_name' => 'required|string|max:255',
            'other_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:students,email',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'coding_experience' => 'nullable|string|max:500',
            'career_interest' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive,graduated,withdrawn',

            // Class Information
            'class_link' => 'nullable|url|max:500',
            'google_classroom_link' => 'nullable|url|max:500',
            'tutor_id' => 'nullable|exists:tutors,id',
            'classes_per_week' => 'nullable|integer|min:1|max:7',
            'starting_course_level' => 'nullable|integer|min:1|max:12',
            'enrollment_date' => 'nullable|date',
            'class_schedule' => 'nullable|array',

            // Parent Information - Father
            'father_name' => 'nullable|string|max:255',
            'father_phone' => 'nullable|string|max:20',
            'father_email' => 'nullable|email|max:255',
            'father_occupation' => 'nullable|string|max:255',
            'father_location' => 'nullable|string|max:255',

            // Parent Information - Mother
            'mother_name' => 'nullable|string|max:255',
            'mother_phone' => 'nullable|string|max:20',
            'mother_email' => 'nullable|email|max:255',
            'mother_occupation' => 'nullable|string|max:255',
            'mother_location' => 'nullable|string|max:255',
        ]);

        $student = null;

        // Generate student ID
        $validated['student_id'] = 'STU-' . strtoupper(uniqid());

        DB::transaction(function() use ($validated, &$student) {
            $student = Student::create($validated);

            // Log the action
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'created',
                'description' => "Created student: {$student->first_name} {$student->last_name}",
                'model_type' => Student::class,
                'model_id' => $student->id,
            ]);
        });

        // Create parent accounts after the transaction (so we have the student ID)
        if ($student) {
            $this->parentAccountService->createParentAccountsForStudent($student);
        }

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Student created successfully. Parent accounts have been created and welcome emails sent.');
    }

    /**
     * Display the specified student.
     */
    public function show(Student $student)
    {
        $student->load(['tutor', 'attendances', 'reports']);
        return view('admin.students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified student.
     */
    public function edit(Student $student)
    {
        $tutors = Tutor::where('status', 'active')->orderBy('first_name')->get();
        return view('admin.students.edit', compact('student', 'tutors'));
    }

    /**
     * Update the specified student.
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            // Student Info
            'first_name' => 'required|string|max:255',
            'other_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:students,email,' . $student->id,
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'coding_experience' => 'nullable|string|max:500',
            'career_interest' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive,graduated,withdrawn',
            
            // Class Information
            'class_link' => 'nullable|url|max:500',
            'google_classroom_link' => 'nullable|url|max:500',
            'tutor_id' => 'nullable|exists:tutors,id',
            'classes_per_week' => 'nullable|integer|min:1|max:7',
            'starting_course_level' => 'nullable|integer|min:1|max:12',
            'enrollment_date' => 'nullable|date',
            'class_schedule' => 'nullable|array',

            // Parent Information - Father
            'father_name' => 'nullable|string|max:255',
            'father_phone' => 'nullable|string|max:20',
            'father_email' => 'nullable|email|max:255',
            'father_occupation' => 'nullable|string|max:255',
            'father_location' => 'nullable|string|max:255',

            // Parent Information - Mother
            'mother_name' => 'nullable|string|max:255',
            'mother_phone' => 'nullable|string|max:20',
            'mother_email' => 'nullable|email|max:255',
            'mother_occupation' => 'nullable|string|max:255',
            'mother_location' => 'nullable|string|max:255',
        ]);

        // Track if parent emails changed for creating new parent accounts
        $fatherEmailChanged = !empty($validated['father_email']) &&
            $validated['father_email'] !== $student->father_email;
        $motherEmailChanged = !empty($validated['mother_email']) &&
            $validated['mother_email'] !== $student->mother_email;

        DB::transaction(function() use ($student, $validated) {
            $student->update($validated);

            // Log the action
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'updated',
                'description' => "Updated student: {$student->first_name} {$student->last_name}",
                'model_type' => Student::class,
                'model_id' => $student->id,
            ]);
        });

        // Create parent accounts if new parent emails were added
        if ($fatherEmailChanged || $motherEmailChanged) {
            $this->parentAccountService->createParentAccountsForStudent($student->fresh());
        }

        return redirect()
            ->route('admin.students.show', $student)
            ->with('success', 'Student updated successfully.');
    }

    // Note: Admin cannot delete students per specification
}
