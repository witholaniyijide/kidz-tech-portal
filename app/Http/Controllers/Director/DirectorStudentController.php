<?php

namespace App\Http\Controllers\Director;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DirectorStudentController extends Controller
{
    /**
     * Display a listing of students.
     */
    public function index(Request $request)
    {
        $query = Student::with(['tutor', 'parent', 'guardians']);

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

        return view('director.students.create', compact('tutors', 'parents'));
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
            'classes_per_week' => 'nullable|integer|min:1|max:7',
            'class_schedules' => 'nullable|array',
            'class_schedules.*.day' => 'required_with:class_schedules|string',
            'class_schedules.*.time' => 'required_with:class_schedules|string',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            // Generate student ID
            $validated['student_id'] = 'STU-' . strtoupper(uniqid());
            
            // Handle class schedule JSON
            if (isset($validated['class_schedules'])) {
                $validated['class_schedule'] = json_encode($validated['class_schedules']);
                unset($validated['class_schedules']);
            }

            $student = Student::create($validated);

            // Link to parent if provided
            if ($request->filled('parent_id')) {
                $student->guardians()->attach($request->parent_id);
            }

            DB::commit();

            return redirect()->route('director.students.index')
                ->with('success', 'Student created successfully.');
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

        return view('director.students.edit', compact('student', 'tutors', 'parents'));
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
            'classes_per_week' => 'nullable|integer|min:1|max:7',
            'class_schedules' => 'nullable|array',
            'class_schedules.*.day' => 'required_with:class_schedules|string',
            'class_schedules.*.time' => 'required_with:class_schedules|string',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            // Handle class schedule JSON
            if (isset($validated['class_schedules'])) {
                $validated['class_schedule'] = json_encode($validated['class_schedules']);
                unset($validated['class_schedules']);
            }

            $student->update($validated);

            // Update parent link
            if ($request->filled('parent_id')) {
                $student->guardians()->sync([$request->parent_id]);
            } else {
                $student->guardians()->detach();
            }

            DB::commit();

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
            $student->delete();
            return redirect()->route('director.students.index')
                ->with('success', 'Student deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete student: ' . $e->getMessage());
        }
    }
}
