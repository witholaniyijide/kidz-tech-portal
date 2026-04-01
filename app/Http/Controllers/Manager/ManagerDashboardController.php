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

                // Tutor stats (exclude resigned from total)
                'totalTutors' => Tutor::where('status', '!=', 'resigned')->count(),
                'activeTutors' => Tutor::where('status', 'active')->count(),
                'inactiveTutors' => Tutor::where('status', 'inactive')->count(),
                'onLeaveTutors' => Tutor::where('status', 'on_leave')->count(),
                'resignedTutors' => Tutor::where('status', 'resigned')->count(),

                // Today's classes
                'todayClasses' => $this->getTodayClassesCount(),

                // Reports stats
                'totalReports' => TutorReport::count(),
                'pendingReports' => TutorReport::where('status', 'submitted')->count(),
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
            ->where('status', 'posted')
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

        // Get recent notices (visible to managers) - limit to 4, prioritize pinned
        $notices = Notice::where('status', 'published')
            ->where(function($query) {
                $query->whereJsonContains('visible_to', 'manager')
                      ->orWhereJsonContains('visible_to', 'all');
            })
            ->orderBy('is_pinned', 'desc')
            ->orderBy('pinned_at', 'desc')
            ->orderBy('published_at', 'desc')
            ->limit(4)
            ->get();

        // Today's Birthdays
        $todaysBirthdays = $this->getTodaysBirthdays();

        // Auto-generated to-do list
        $todos = [
            [
                'text' => "Review {$stats['pendingReports']} pending report(s)",
                'completed' => $stats['pendingReports'] == 0,
                'link' => route('manager.reports.index', ['status' => 'submitted']),
                'count' => $stats['pendingReports'],
                'priority' => $stats['pendingReports'] > 5 ? 'high' : 'medium',
            ],
            [
                'text' => "Review {$stats['pendingAssessments']} assessment(s)",
                'completed' => $stats['pendingAssessments'] == 0,
                'link' => route('manager.assessments.index'),
                'count' => $stats['pendingAssessments'],
                'priority' => $stats['pendingAssessments'] > 3 ? 'high' : 'medium',
            ],
            [
                'text' => "Approve {$stats['pendingAttendance']} attendance record(s)",
                'completed' => $stats['pendingAttendance'] == 0,
                'link' => route('manager.attendance.index', ['status' => 'pending']),
                'count' => $stats['pendingAttendance'],
                'priority' => $stats['pendingAttendance'] > 10 ? 'high' : 'low',
            ],
            [
                'text' => "Check today's class schedule",
                'completed' => $todaySchedule !== null,
                'link' => route('manager.attendance.calendar'),
                'count' => $stats['todayClasses'],
                'priority' => 'low',
            ],
        ];

        return view('manager.dashboard', compact(
            'stats',
            'todaySchedule',
            'recentReports',
            'recentAssessments',
            'notices',
            'todaysBirthdays',
            'todos'
        ));
    }

    /**
     * Get count of today's scheduled classes
     */
    private function getTodayClassesCount()
    {
        $todaySchedule = DailyClassSchedule::where('schedule_date', Carbon::today())
            ->where('status', 'posted')
            ->first();

        if (!$todaySchedule || !$todaySchedule->classes) {
            return 0;
        }

        return count($todaySchedule->classes);
    }

    /**
     * Get today's birthdays for students and tutors.
     */
    private function getTodaysBirthdays(): array
    {
        $today = Carbon::today();
        $birthdays = [];

        // Get students with birthday today
        $studentBirthdays = Student::whereMonth('date_of_birth', $today->month)
            ->whereDay('date_of_birth', $today->day)
            ->where('status', 'active')
            ->get();

        foreach ($studentBirthdays as $student) {
            $birthdays[] = [
                'name' => $student->first_name . ' ' . $student->last_name,
                'role' => 'Student',
                'type' => 'student',
            ];
        }

        // Get tutors with birthday today
        $tutorBirthdays = Tutor::whereMonth('date_of_birth', $today->month)
            ->whereDay('date_of_birth', $today->day)
            ->where('status', 'active')
            ->get();

        foreach ($tutorBirthdays as $tutor) {
            $birthdays[] = [
                'name' => $tutor->first_name . ' ' . $tutor->last_name,
                'role' => 'Tutor',
                'type' => 'tutor',
            ];
        }

        return $birthdays;
    }
}
