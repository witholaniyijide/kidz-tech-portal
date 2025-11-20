<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of students
     */
    public function index(Request $request)
    {
        $query = Student::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by location
        if ($request->has('location') && $request->location != '') {
            $query->where('location', $request->location);
        }

        $students = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('students.index', compact('students'));
    }

    /**
     * Show the form for creating a new student
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * Store a newly created student
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:students,email',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female',
            'address' => 'nullable|string',
            'state' => 'nullable|string|max:255',
            'parent_name' => 'required|string|max:255',
            'parent_email' => 'nullable|email',
            'parent_phone' => 'required|string|max:20',
            'parent_relationship' => 'required|string|max:255',
            'enrollment_date' => 'required|date',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        // Generate student ID
        $validated['student_id'] = $this->generateStudentId();
        $validated['status'] = 'active';

        Student::create($validated);

        return redirect()->route('students.index')
            ->with('success', 'Student added successfully!');
    }

    /**
     * Display the specified student
     */
    public function show(Student $student)
    {
        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified student
     */
    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified student
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:students,email,' . $student->id,
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female',
            'address' => 'nullable|string',
            'state' => 'nullable|string|max:255',
            'parent_name' => 'required|string|max:255',
            'parent_email' => 'nullable|email',
            'parent_phone' => 'required|string|max:20',
            'parent_relationship' => 'required|string|max:255',
            'enrollment_date' => 'required|date',
            'status' => 'required|in:active,inactive,graduated,withdrawn',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $student->update($validated);

        return redirect()->route('students.show', $student)
            ->with('success', 'Student updated successfully!');
    }

    /**
     * Remove the specified student
     */
    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully!');
    }

    /**
     * Generate unique student ID
     */
    private function generateStudentId()
    {
        $lastStudent = Student::withTrashed()->orderBy('id', 'desc')->first();
        $number = $lastStudent ? intval(substr($lastStudent->student_id, 2)) + 1 : 1;
        
        return 'KT' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
