<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\AttendanceRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StudentAttendanceController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->hasRole('student') && !Auth::user()->hasRole('admin')) {
                abort(403, 'Unauthorized access.');
            }
            return $next($request);
        });
    }

    /**
     * Get the student associated with the authenticated user.
     */
    protected function getAuthenticatedStudent()
    {
        $student = Student::where('user_id', Auth::id())->first();

        if (!$student) {
            // Try by email as fallback
            $student = Student::where('email', Auth::user()->email)->first();
        }

        if (!$student) {
            abort(404, 'Student profile not found. Please contact administration.');
        }

        return $student;
    }

    /**
     * Display monthly attendance data for Chart.js and timeline.
     */
    public function index(Request $request)
    {
        $student = $this->getAuthenticatedStudent();

        // Get month filter (default to current month)
        $month = $request->get('month', now()->format('Y-m'));
        $selectedDate = Carbon::parse($month);

        // Get all attendance records for this student
        $allRecords = AttendanceRecord::where('student_id', $student->id)
            ->orderBy('class_date', 'desc')
            ->get();

        // Calculate statistics
        $totalClasses = $allRecords->count();
        $completedClasses = $allRecords->where('status', 'present')->count();
        $missedClasses = $allRecords->where('status', 'absent')->count();
        $attendanceRate = $totalClasses > 0 ? round(($completedClasses / $totalClasses) * 100, 1) : 0;

        // Calculate current streak
        $currentStreak = $this->calculateStreak($allRecords);

        // Get recent attendance records for timeline (paginated)
        $attendanceRecords = AttendanceRecord::where('student_id', $student->id)
            ->with(['tutor'])
            ->orderBy('class_date', 'desc')
            ->paginate(20);

        // Prepare monthly chart data
        $monthlyData = $this->prepareMonthlyChartData($student, $selectedDate);

        return view('student.attendance.index', compact(
            'student',
            'attendanceRecords',
            'attendanceRate',
            'completedClasses',
            'missedClasses',
            'currentStreak',
            'monthlyData',
            'selectedDate'
        ));
    }

    /**
     * Return JSON attendance chart data for a specific month.
     */
    public function attendanceChart(Student $student, $month)
    {
        // Verify student access
        $authStudent = $this->getAuthenticatedStudent();
        if ($authStudent->id !== $student->id) {
            abort(403, 'Unauthorized access.');
        }

        $selectedDate = Carbon::parse($month);
        $chartData = $this->prepareMonthlyChartData($student, $selectedDate);

        return response()->json($chartData);
    }

    /**
     * Display attendance detail for a specific record.
     */
    public function show(AttendanceRecord $record)
    {
        $student = $this->getAuthenticatedStudent();

        // Ensure this record belongs to the authenticated student
        if ($record->student_id !== $student->id) {
            abort(403, 'Unauthorized: You can only view your own attendance records.');
        }

        // Load relationships
        $record->load(['tutor', 'student']);

        return view('student.attendance.show', compact('student', 'record'));
    }

    /**
     * Prepare monthly chart data for Chart.js.
     */
    protected function prepareMonthlyChartData(Student $student, Carbon $date)
    {
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        // Get attendance records for the month
        $records = AttendanceRecord::where('student_id', $student->id)
            ->whereBetween('class_date', [$startOfMonth, $endOfMonth])
            ->orderBy('class_date', 'asc')
            ->get();

        // Prepare data arrays
        $dates = [];
        $presentData = [];
        $absentData = [];

        // Group by date
        foreach ($records as $record) {
            $dateKey = Carbon::parse($record->class_date)->format('M d');

            if (!in_array($dateKey, $dates)) {
                $dates[] = $dateKey;
            }

            if ($record->status === 'present') {
                $presentData[$dateKey] = ($presentData[$dateKey] ?? 0) + 1;
            } else {
                $absentData[$dateKey] = ($absentData[$dateKey] ?? 0) + 1;
            }
        }

        // Fill missing dates with zeros
        $presentValues = [];
        $absentValues = [];
        foreach ($dates as $date) {
            $presentValues[] = $presentData[$date] ?? 0;
            $absentValues[] = $absentData[$date] ?? 0;
        }

        return [
            'labels' => $dates,
            'datasets' => [
                [
                    'label' => 'Present',
                    'data' => $presentValues,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.5)',
                    'borderColor' => 'rgba(34, 197, 94, 1)',
                    'borderWidth' => 2,
                ],
                [
                    'label' => 'Absent',
                    'data' => $absentValues,
                    'backgroundColor' => 'rgba(239, 68, 68, 0.5)',
                    'borderColor' => 'rgba(239, 68, 68, 1)',
                    'borderWidth' => 2,
                ]
            ]
        ];
    }

    /**
     * Calculate the current attendance streak.
     */
    protected function calculateStreak($records)
    {
        $streak = 0;
        $sortedRecords = $records->sortByDesc('class_date');

        foreach ($sortedRecords as $record) {
            if ($record->status === 'present') {
                $streak++;
            } else {
                break;
            }
        }

        return $streak;
    }
}
