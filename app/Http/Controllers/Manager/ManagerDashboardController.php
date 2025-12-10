<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\TutorReport;
use App\Models\TutorAssessment;
use App\Models\AttendanceRecord;
use App\Models\DailyClassSchedule;
use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class ManagerDashboardController extends Controller
{
    /**
     * Display the manager dashboard.
     */
    public function index()
    {
        // Cache dashboard data for 5 minutes
        $stats = Cache::remember('manager_dashboard_stats', 300, function () {
            return [
                // Student stats
                'totalStudents' => Student::count(),
                'activeStudents' => Student::where('status', 'active')->count(),
                'inactiveStudents' => Student::where('status', 'inactive')->count(),
                'graduatedStudents' => Student::where('status', 'graduated')->count(),
                'withdrawnStudents' => Student::where('status', 'withdrawn')->count(),

                // Tutor stats
                'totalTutors' => Tutor::count(),
                'activeTutors' => Tutor::where('status', 'active')->count(),
                'inactiveTutors' => Tutor::where('status', 'inactive')->count(),
                'onLeaveTutors' => Tutor::where('status', 'on_leave')->count(),
                'resignedTutors' => Tutor::where('status', 'resigned')->count(),

                // Today's classes
                'todayClasses' => $this->getTodayClassesCount(),

                // Reports stats
                'totalReports' => TutorReport::count(),
                'pendingReports' => TutorReport::whereIn('status', ['submitted', 'pending'])->count(),
                'managerApprovedReports' => TutorReport::where('status', 'approved-by-manager')->count(),
                'directorApprovedReports' => TutorReport::where('status', 'approved-by-director')->count(),

                // Assessment stats
                'totalAssessments' => TutorAssessment::count(),
                'pendingAssessments' => TutorAssessment::whereIn('status', ['submitted', 'pending', 'draft'])->count(),
                'awaitingDirectorAssessments' => TutorAssessment::where('status', 'approved-by-manager')->count(),
                'completedAssessments' => TutorAssessment::where('status', 'approved-by-director')->count(),

                // Attendance stats
                'totalAttendance' => AttendanceRecord::count(),
                'pendingAttendance' => AttendanceRecord::where('status', 'pending')->count(),
                'approvedAttendance' => AttendanceRecord::where('status', 'approved')->count(),
            ];
        });

        // Get today's schedule (not cached - real-time)
        $todaySchedule = DailyClassSchedule::where('schedule_date', Carbon::today())
            ->where('status', 'published')
            ->first();

        // Get recent submitted reports (last 10)
        $recentReports = TutorReport::with(['student', 'tutor'])
            ->whereIn('status', ['submitted', 'pending', 'approved-by-manager'])
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        // Get recent assessments awaiting action
        $recentAssessments = TutorAssessment::with(['tutor'])
            ->whereIn('status', ['submitted', 'pending', 'draft', 'approved-by-manager'])
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        // Get recent notices (visible to managers)
        $notices = Notice::where('status', 'published')
            ->where(function($query) {
                $query->whereJsonContains('visible_to', 'manager')
                      ->orWhereJsonContains('visible_to', 'all');
            })
            ->orderBy('published_at', 'desc')
            ->limit(5)
            ->get();

        return view('manager.dashboard', compact(
            'stats',
            'todaySchedule',
            'recentReports',
            'recentAssessments',
            'notices'
        ));
    }

    /**
     * Get count of today's scheduled classes
     */
    private function getTodayClassesCount()
    {
        $todaySchedule = DailyClassSchedule::where('schedule_date', Carbon::today())
            ->where('status', 'published')
            ->first();

        if (!$todaySchedule || !$todaySchedule->classes) {
            return 0;
        }

        return count($todaySchedule->classes);
    }
}
