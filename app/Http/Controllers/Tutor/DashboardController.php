<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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

        return view('tutor.dashboard', compact(
            'tutor',
            'students',
            'studentsCount',
            'recentAttendance',
            'pendingAttendanceCount',
            'reportsCount',
            'draftReportsCount',
            'submittedReportsCount',
            'recentReports',
            'unreadNotifications',
            'unreadNotificationsCount',
            'upcomingAvailability'
        ));
    }
}
