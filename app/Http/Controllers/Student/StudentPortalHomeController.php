<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\ParentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $students = $user->guardiansOf;

        // Get per-student dashboard metrics
        $studentMetrics = $students->map(function ($student) {
            return [
                'student' => $student,
                'nextMilestone' => $student->progress()
                    ->where('completed', false)
                    ->orderBy('created_at', 'asc')
                    ->first(),
                'progressPercentage' => $student->progressPercentage(),
                'lastReport' => $student->approvedReports()->first(),
            ];
        });

        // Get unread notifications count
        $unreadNotifications = ParentNotification::where('parent_id', $user->id)
            ->whereNull('read_at')
            ->count();

        return view('parent.dashboard', compact(
            'students',
            'studentMetrics',
            'unreadNotifications'
        ));
    }
}
