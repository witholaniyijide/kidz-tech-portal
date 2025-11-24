<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
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
    public function store(StoreStudentRequest $request)
    {
        $data = $request->validated();

        // Generate student ID
        $data['student_id'] = $this->generateStudentId();

        // Set default status if not provided
        $data['status'] = $data['status'] ?? 'active';

        // Handle class_schedule - encode as JSON
        if (isset($data['class_schedule']) && is_array($data['class_schedule'])) {
            $data['class_schedule'] = json_encode($data['class_schedule']);
        }

        // Set default completed_periods
        $data['completed_periods'] = $data['completed_periods'] ?? 0;

        $student = Student::create($data);

        // Check if "Save & Add Another" was clicked
        if ($request->input('action') === 'save_and_add') {
            return redirect()->route('students.create')
                ->with('success', 'Student added successfully! You can add another one.');
        }

        return redirect()->route('students.show', $student)
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
    public function update(UpdateStudentRequest $request, Student $student)
    {
        $data = $request->validated();

        // Handle class_schedule - encode as JSON
        if (isset($data['class_schedule']) && is_array($data['class_schedule'])) {
            $data['class_schedule'] = json_encode($data['class_schedule']);
        }

        $student->update($data);

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
