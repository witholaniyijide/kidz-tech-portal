<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\DailyClassSchedule;
use Illuminate\Support\Facades\Auth;
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

        // Get students assigned to this tutor
        $students = $tutor->students()->active()->get();
        $studentsCount = $students->count();

        // Get student IDs for filtering classes
        $studentIds = $students->pluck('id')->toArray();

        // Get recent attendance records
        $recentAttendance = $tutor->attendanceRecords()
            ->with('student')
            ->orderBy('class_date', 'desc')
            ->take(5)
            ->get();

        // Get pending attendance count
        $pendingAttendanceCount = $tutor->attendanceRecords()
            ->where('status', 'pending')
            ->count();

        // Get reports stats
        $reportsCount = $tutor->reports()->count();
        $draftReportsCount = $tutor->reports()->where('status', 'draft')->count();
        $submittedReportsCount = $tutor->reports()->where('status', 'submitted')->count();
        $pendingReportsCount = $tutor->reports()
            ->whereIn('status', ['draft', 'returned'])
            ->count();

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

        return view('tutor.dashboard', compact(
            'tutor',
            'students',
            'studentsCount',
            'recentAttendance',
            'pendingAttendanceCount',
            'reportsCount',
            'draftReportsCount',
            'submittedReportsCount',
            'pendingReportsCount',
            'recentReports',
            'unreadNotifications',
            'unreadNotificationsCount',
            'upcomingAvailability',
            'todayClasses',
            'weekClasses'
        ));
    }
}
