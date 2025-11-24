<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Report;
use App\Models\AttendanceRecord;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ManagerStudentController extends Controller
{
    /**
     * Display a listing of students (read-only).
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

        // Get unique locations for filter dropdown
        $locations = Student::select('location')
            ->distinct()
            ->whereNotNull('location')
            ->orderBy('location')
            ->pluck('location');

        return view('manager.students.index', compact('students', 'stats', 'locations'));
    }

    /**
     * Display the specified student (read-only).
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

        return view('manager.students.show', compact('student', 'recentReports', 'recentAttendance', 'metrics'));
    }

    /**
     * Display student progress overview.
     */
    public function progress(Student $student)
    {
        // Get comprehensive progress data for the student
        $progressData = [
            'completion_rate' => $this->calculateCompletionRate($student),
            'attendance_rate' => $this->calculateStudentAttendanceRate($student),
            'monthly_activity' => $this->getMonthlyActivity($student),
            'skills_mastered' => $this->getSkillsMastered($student),
            'recent_projects' => $this->getRecentProjects($student),
        ];

        return view('manager.students.progress', compact('student', 'progressData'));
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
     * Get monthly activity data for charts.
     */
    private function getMonthlyActivity(Student $student)
    {
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = [
                'month' => $date->format('M Y'),
                'classes' => AttendanceRecord::where('student_id', $student->id)
                    ->whereYear('class_date', $date->year)
                    ->whereMonth('class_date', $date->month)
                    ->where('status', 'approved')
                    ->count(),
                'reports' => Report::where('student_id', $student->id)
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
            ];
        }

        return $months;
    }

    /**
     * Get skills mastered by the student.
     */
    private function getSkillsMastered(Student $student)
    {
        $reports = Report::where('student_id', $student->id)
            ->whereNotNull('skills_mastered')
            ->get();

        $allSkills = [];
        foreach ($reports as $report) {
            $skills = $report->skills_mastered ?? [];
            if (is_array($skills)) {
                $allSkills = array_merge($allSkills, $skills);
            }
        }

        return array_unique($allSkills);
    }

    /**
     * Get recent projects completed by the student.
     */
    private function getRecentProjects(Student $student)
    {
        $reports = Report::where('student_id', $student->id)
            ->whereNotNull('projects')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $projects = [];
        foreach ($reports as $report) {
            $reportProjects = $report->projects ?? [];
            if (is_array($reportProjects)) {
                foreach ($reportProjects as $project) {
                    $projects[] = [
                        'name' => $project,
                        'month' => $report->month,
                        'year' => $report->year,
                    ];
                }
            }
        }

        return $projects;
    }

    /**
     * Display student's attendance history.
     */
    public function attendance(Student $student)
    {
        $attendanceRecords = AttendanceRecord::where('student_id', $student->id)
            ->with('tutor')
            ->orderBy('class_date', 'desc')
            ->paginate(20);

        return view('manager.students.attendance', compact('student', 'attendanceRecords'));
    }

    /**
     * Display student's reports history.
     */
    public function reports(Student $student)
    {
        $reports = Report::where('student_id', $student->id)
            ->with('instructor')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->paginate(15);

        return view('manager.students.reports', compact('student', 'reports'));
    }
}
