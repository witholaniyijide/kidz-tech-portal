<?php

namespace App\Http\Controllers\Director;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\Report;
use App\Models\AttendanceRecord;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DirectorStudentController extends Controller
{
    /**
     * Display a listing of students.
     */
    public function index(Request $request)
    {
        $query = Student::with('tutor');

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by location if provided
        if ($request->has('location') && $request->location) {
            $query->where('location', $request->location);
        }

        // Filter by tutor if provided
        if ($request->has('tutor_id') && $request->tutor_id) {
            $query->where('tutor_id', $request->tutor_id);
        }

        // Search by name or email
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('other_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%")
                  ->orWhere('parent_email', 'like', "%{$search}%");
            });
        }

        $students = $query->orderBy('first_name')
            ->orderBy('last_name')
            ->paginate(15);

        // Get statistics
        $stats = [
            'total' => Student::count(),
            'active' => Student::where('status', 'active')->count(),
            'inactive' => Student::where('status', 'inactive')->count(),
            'on_hold' => Student::where('status', 'on_hold')->count(),
        ];

        // Get unique locations and tutors for filter dropdowns
        $locations = Student::select('location')
            ->distinct()
            ->whereNotNull('location')
            ->orderBy('location')
            ->pluck('location');

        $tutors = Tutor::orderBy('name')->get();

        return view('director.students.index', compact('students', 'stats', 'locations', 'tutors'));
    }

    /**
     * Show the form for creating a new student.
     */
    public function create()
    {
        $tutors = Tutor::orderBy('name')->get();
        return view('director.students.create', compact('tutors'));
    }

    /**
     * Store a newly created student.
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

        return redirect()->route('director.students.show', $student)
            ->with('success', 'Student created successfully!');
    }

    /**
     * Display the specified student.
     */
    public function show(Student $student)
    {
        // Load relationships
        $student->load(['tutor', 'parent']);

        // Get student's recent reports
        $recentReports = Report::where('student_id', $student->id)
            ->with('instructor')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get student's recent attendance records
        $recentAttendance = AttendanceRecord::where('student_id', $student->id)
            ->with('tutor')
            ->orderBy('class_date', 'desc')
            ->limit(10)
            ->get();

        // Calculate student progress metrics
        $metrics = [
            'total_classes' => AttendanceRecord::where('student_id', $student->id)
                ->where('status', 'approved')
                ->count(),
            'classes_this_month' => AttendanceRecord::where('student_id', $student->id)
                ->whereYear('class_date', Carbon::now()->year)
                ->whereMonth('class_date', Carbon::now()->month)
                ->where('status', 'approved')
                ->count(),
            'total_reports' => Report::where('student_id', $student->id)
                ->whereIn('status', ['submitted', 'submitted_to_manager', 'approved_by_manager', 'approved'])
                ->count(),
            'completion_rate' => $this->calculateCompletionRate($student),
            'attendance_rate' => $this->calculateStudentAttendanceRate($student),
        ];

        return view('director.students.show', compact('student', 'recentReports', 'recentAttendance', 'metrics'));
    }

    /**
     * Show the form for editing the specified student.
     */
    public function edit(Student $student)
    {
        $tutors = Tutor::orderBy('name')->get();
        return view('director.students.edit', compact('student', 'tutors'));
    }

    /**
     * Update the specified student.
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {
        $data = $request->validated();

        // Handle class_schedule - encode as JSON
        if (isset($data['class_schedule']) && is_array($data['class_schedule'])) {
            $data['class_schedule'] = json_encode($data['class_schedule']);
        }

        $student->update($data);

        return redirect()->route('director.students.show', $student)
            ->with('success', 'Student updated successfully!');
    }

    /**
     * Remove the specified student.
     */
    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('director.students.index')
            ->with('success', 'Student deleted successfully!');
    }

    /**
     * Calculate student's completion rate based on periods.
     */
    private function calculateCompletionRate(Student $student)
    {
        $totalPeriods = $student->total_periods ?? 0;
        $completedPeriods = $student->completed_periods ?? 0;

        if ($totalPeriods === 0) {
            return 0;
        }

        return round(($completedPeriods / $totalPeriods) * 100, 2);
    }

    /**
     * Calculate student's attendance rate.
     */
    private function calculateStudentAttendanceRate(Student $student)
    {
        // Get total scheduled classes for this student
        $totalScheduled = AttendanceRecord::where('student_id', $student->id)->count();

        // Get total attended classes
        $totalAttended = AttendanceRecord::where('student_id', $student->id)
            ->where('status', 'approved')
            ->count();

        if ($totalScheduled === 0) {
            return 0;
        }

        return round(($totalAttended / $totalScheduled) * 100, 2);
    }

    /**
     * Generate unique student ID.
     */
    private function generateStudentId()
    {
        $lastStudent = Student::withTrashed()->orderBy('id', 'desc')->first();
        $number = $lastStudent ? intval(substr($lastStudent->student_id, 2)) + 1 : 1;

        return 'KT' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
