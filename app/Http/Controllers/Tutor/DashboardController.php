<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\DailyClassSchedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the tutor dashboard.
     */
    public function index()
    {
        // Get the authenticated tutor
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            abort(403, 'You do not have a tutor profile.');
        }

        // Cache dashboard stats for 5 minutes
        $cacheKey = 'tutor_dashboard_' . $tutor->id;

        $stats = Cache::remember($cacheKey, 300, function () use ($tutor) {
            $students = $tutor->students()->active()->get();

            return [
                'studentsCount' => $students->count(),
                'studentIds' => $students->pluck('id')->toArray(),
                'pendingAttendanceCount' => $tutor->attendanceRecords()
                    ->where('status', 'pending')
                    ->count(),
                'reportsCount' => $tutor->reports()->count(),
                'draftReportsCount' => $tutor->reports()->where('status', 'draft')->count(),
                'submittedReportsCount' => $tutor->reports()->where('status', 'submitted')->count(),
                'submittedThisMonth' => $tutor->reports()
                    ->where('status', 'submitted')
                    ->where('month', now()->format('Y-m'))
                    ->count(),
                'pendingReportsCount' => $tutor->reports()
                    ->whereIn('status', ['draft', 'returned'])
                    ->count(),
            ];
        });

        // Get students with eager loading
        $students = $tutor->students()->active()->get();
        $studentsCount = $stats['studentsCount'];
        $studentIds = $stats['studentIds'];
        $pendingAttendanceCount = $stats['pendingAttendanceCount'];
        $reportsCount = $stats['reportsCount'];
        $draftReportsCount = $stats['draftReportsCount'];
        $submittedReportsCount = $stats['submittedReportsCount'];
        $submittedThisMonth = $stats['submittedThisMonth'];
        $pendingReportsCount = $stats['pendingReportsCount'];

        // Get recent attendance records with eager loading
        $recentAttendance = $tutor->attendanceRecords()
            ->with('student')
            ->orderBy('class_date', 'desc')
            ->take(5)
            ->get();

        // Get recent reports
        $recentReports = $tutor->reports()
            ->with('student')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get unread notifications
        $unreadNotifications = $tutor->notifications()
            ->unread()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $unreadNotificationsCount = $tutor->notifications()->unread()->count();

        // Get upcoming availability (current week)
        $upcomingAvailability = $tutor->availabilities()
            ->active()
            ->get();

        // Get today's classes
        $todaySchedule = DailyClassSchedule::where('schedule_date', Carbon::today())
            ->where('status', 'posted')
            ->first();

        $todayClasses = collect();
        if ($todaySchedule && $todaySchedule->classes) {
            // Filter classes for this tutor's students
            $todayClasses = collect($todaySchedule->classes)->filter(function ($class) use ($studentIds) {
                return isset($class['student_id']) && in_array($class['student_id'], $studentIds);
            });
        }

        // Get this week's classes
        $weekStart = Carbon::now()->startOfWeek();
        $weekEnd = Carbon::now()->endOfWeek();

        $weekSchedules = DailyClassSchedule::whereBetween('schedule_date', [$weekStart, $weekEnd])
            ->where('status', 'posted')
            ->orderBy('schedule_date')
            ->get();

        $weekClasses = collect();
        foreach ($weekSchedules as $schedule) {
            if ($schedule->classes) {
                $filteredClasses = collect($schedule->classes)->filter(function ($class) use ($studentIds) {
                    return isset($class['student_id']) && in_array($class['student_id'], $studentIds);
                });

                foreach ($filteredClasses as $class) {
                    $class['schedule_date'] = $schedule->schedule_date;
                    $weekClasses->push($class);
                }
            }
        }

        // Classes today count
        $classesTodayCount = $todayClasses->count();

        return view('tutor.dashboard', compact(
            'tutor',
            'students',
            'studentsCount',
            'recentAttendance',
            'pendingAttendanceCount',
            'reportsCount',
            'draftReportsCount',
            'submittedReportsCount',
            'submittedThisMonth',
            'pendingReportsCount',
            'recentReports',
            'unreadNotifications',
            'unreadNotificationsCount',
            'upcomingAvailability',
            'todayClasses',
            'weekClasses',
            'classesTodayCount'
        ));
    }
}
