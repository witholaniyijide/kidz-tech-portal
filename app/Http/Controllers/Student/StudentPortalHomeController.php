<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\ParentNotification;
use App\Models\TutorReport;
use App\Models\AttendanceRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StudentPortalHomeController extends Controller
{
    /**
     * Display the student dashboard.
     */
    public function studentDashboard()
    {
        $user = Auth::user();

        // Find the student record by email
        $student = Student::where('email', $user->email)->firstOrFail();

        // Get student profile data
        $profile = $student;

        // Get next milestone (first uncompleted progress item)
        $nextMilestone = $student->progress()
            ->where('completed', false)
            ->orderBy('created_at', 'asc')
            ->first();

        // Get progress percentage
        $progressPercentage = $student->progressPercentage();

        // Get last director-approved report
        $lastReport = $student->approvedReports()->first();

        // Get unread notifications count (students don't have notifications in current system)
        $unreadNotifications = 0;

        return view('student.dashboard', compact(
            'student',
            'profile',
            'nextMilestone',
            'progressPercentage',
            'lastReport',
            'unreadNotifications'
        ));
    }

    /**
     * Display the parent dashboard.
     */
    public function parentDashboard()
    {
        $user = Auth::user();

        // Get all students linked to this parent
        $children = $user->guardiansOf;

        // Get student IDs
        $studentIds = $children->pluck('id');

        // Get recent director-approved tutor reports for all children
        $recentReports = TutorReport::whereIn('student_id', $studentIds)
                              ->where('status', 'approved-by-director')
                              ->with(['student', 'tutor'])
                              ->orderBy('created_at', 'desc')
                              ->take(5)
                              ->get();

        // Get attendance summary
        $currentMonth = Carbon::now()->format('F');
        $currentYear = Carbon::now()->format('Y');

        $totalAttendance = AttendanceRecord::whereIn('student_id', $studentIds)
                                          ->whereMonth('class_date', Carbon::now()->month)
                                          ->whereYear('class_date', Carbon::now()->year)
                                          ->count();

        $presentCount = AttendanceRecord::whereIn('student_id', $studentIds)
                                       ->whereMonth('class_date', Carbon::now()->month)
                                       ->whereYear('class_date', Carbon::now()->year)
                                       ->where('status', 'present')
                                       ->count();

        $attendanceRate = $totalAttendance > 0
            ? round(($presentCount / $totalAttendance) * 100, 1)
            : 0;

        // Get unread notifications count
        $unreadNotifications = ParentNotification::where('parent_id', $user->id)
            ->whereNull('read_at')
            ->count();

        return view('parent.dashboard', compact(
            'children',
            'recentReports',
            'attendanceRate',
            'currentMonth',
            'currentYear',
            'unreadNotifications'
        ));
    }
}
