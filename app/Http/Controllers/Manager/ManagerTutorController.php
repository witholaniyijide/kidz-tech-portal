<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Tutor;
use App\Models\Report;
use App\Models\AttendanceRecord;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ManagerTutorController extends Controller
{
    /**
     * Display a listing of tutors (read-only).
     */
    public function index(Request $request)
    {
        $query = Tutor::withCount(['students', 'attendanceRecords']);

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by location if provided
        if ($request->has('location') && $request->location) {
            $query->where('location', $request->location);
        }

        // Search by name or email
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('tutor_id', 'like', "%{$search}%");
            });
        }

        $tutors = $query->orderBy('first_name')
            ->orderBy('last_name')
            ->paginate(15);

        // Get statistics
        $stats = [
            'total' => Tutor::count(),
            'active' => Tutor::where('status', 'active')->count(),
            'inactive' => Tutor::where('status', 'inactive')->count(),
            'on_leave' => Tutor::where('status', 'on_leave')->count(),
            'resigned' => Tutor::where('status', 'resigned')->count(),
        ];

        // Get unique locations for filter dropdown
        $locations = Tutor::select('location')
            ->distinct()
            ->whereNotNull('location')
            ->orderBy('location')
            ->pluck('location');

        return view('manager.tutors.index', compact('tutors', 'stats', 'locations'));
    }

    /**
     * Display the specified tutor (read-only).
     */
    public function show(Tutor $tutor)
    {
        // Load relationships
        $tutor->load(['students', 'user']);

        // Get tutor's recent reports
        $recentReports = Report::where('instructor_id', $tutor->user_id ?? null)
            ->with('student')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get tutor's recent attendance records
        $recentAttendance = AttendanceRecord::where('tutor_id', $tutor->id)
            ->with('student')
            ->orderBy('class_date', 'desc')
            ->limit(10)
            ->get();

        // Calculate tutor performance metrics
        $metrics = [
            'total_students' => $tutor->students()->count(),
            'active_students' => $tutor->students()->where('status', 'active')->count(),
            'total_classes' => AttendanceRecord::where('tutor_id', $tutor->id)
                ->where('status', 'approved')
                ->count(),
            'classes_this_month' => AttendanceRecord::where('tutor_id', $tutor->id)
                ->whereYear('class_date', Carbon::now()->year)
                ->whereMonth('class_date', Carbon::now()->month)
                ->where('status', 'approved')
                ->count(),
            'reports_submitted' => Report::where('instructor_id', $tutor->user_id ?? null)
                ->whereIn('status', ['submitted', 'submitted_to_manager', 'approved_by_manager', 'approved'])
                ->count(),
            'reports_pending' => Report::where('instructor_id', $tutor->user_id ?? null)
                ->where('status', 'draft')
                ->count(),
        ];

        return view('manager.tutors.show', compact('tutor', 'recentReports', 'recentAttendance', 'metrics'));
    }

    /**
     * Display tutor performance overview.
     */
    public function performance(Tutor $tutor)
    {
        // Get comprehensive performance data for the tutor
        $performanceData = [
            'attendance_rate' => $this->calculateAttendanceRate($tutor),
            'report_submission_rate' => $this->calculateReportSubmissionRate($tutor),
            'student_satisfaction' => $this->calculateStudentSatisfaction($tutor),
            'monthly_activity' => $this->getMonthlyActivity($tutor),
        ];

        return view('manager.tutors.performance', compact('tutor', 'performanceData'));
    }

    /**
     * Calculate tutor's attendance rate.
     */
    private function calculateAttendanceRate(Tutor $tutor)
    {
        $totalClasses = AttendanceRecord::where('tutor_id', $tutor->id)->count();
        $approvedClasses = AttendanceRecord::where('tutor_id', $tutor->id)
            ->where('status', 'approved')
            ->count();

        if ($totalClasses === 0) {
            return 0;
        }

        return round(($approvedClasses / $totalClasses) * 100, 2);
    }

    /**
     * Calculate tutor's report submission rate.
     */
    private function calculateReportSubmissionRate(Tutor $tutor)
    {
        $expectedReports = $tutor->students()->where('status', 'active')->count();
        $submittedReports = Report::where('instructor_id', $tutor->user_id ?? null)
            ->whereIn('status', ['submitted', 'submitted_to_manager', 'approved_by_manager', 'approved'])
            ->whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();

        if ($expectedReports === 0) {
            return 100;
        }

        return round(($submittedReports / $expectedReports) * 100, 2);
    }

    /**
     * Calculate student satisfaction (placeholder).
     */
    private function calculateStudentSatisfaction(Tutor $tutor)
    {
        // This would need a feedback/rating system
        // For now, return a placeholder
        return null;
    }

    /**
     * Get monthly activity data for charts.
     */
    private function getMonthlyActivity(Tutor $tutor)
    {
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = [
                'month' => $date->format('M Y'),
                'classes' => AttendanceRecord::where('tutor_id', $tutor->id)
                    ->whereYear('class_date', $date->year)
                    ->whereMonth('class_date', $date->month)
                    ->where('status', 'approved')
                    ->count(),
                'reports' => Report::where('instructor_id', $tutor->user_id ?? null)
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
            ];
        }

        return $months;
    }
}
