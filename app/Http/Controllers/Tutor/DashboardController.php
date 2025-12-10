<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\DailyClassSchedule;
use App\Models\Notice;
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
                    ->whereIn('status', ['submitted', 'approved', 'director_approved'])
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
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

        // Get today's classes from DailyClassSchedule
        $todaySchedule = DailyClassSchedule::where('schedule_date', Carbon::today())
            ->where('status', 'posted')
            ->first();

        $todayClasses = collect();
        $schedulePosted = false;

        if ($todaySchedule) {
            $schedulePosted = true;
            if ($todaySchedule->classes) {
                // Filter classes for this tutor's students
                $todayClasses = collect($todaySchedule->classes)->filter(function ($class) use ($studentIds, $tutor) {
                    // Include if student is assigned to this tutor OR tutor_id matches
                    return (isset($class['student_id']) && in_array($class['student_id'], $studentIds))
                        || (isset($class['tutor_id']) && $class['tutor_id'] == $tutor->id);
                })->map(function ($class) use ($students) {
                    // Enrich with student data if available
                    if (isset($class['student_id'])) {
                        $student = $students->firstWhere('id', $class['student_id']);
                        if ($student) {
                            $class['student_name'] = $student->first_name . ' ' . $student->last_name;
                        }
                    }
                    return $class;
                })->sortBy('class_time')->values();
            }
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
                $filteredClasses = collect($schedule->classes)->filter(function ($class) use ($studentIds, $tutor) {
                    return (isset($class['student_id']) && in_array($class['student_id'], $studentIds))
                        || (isset($class['tutor_id']) && $class['tutor_id'] == $tutor->id);
                });

                foreach ($filteredClasses as $class) {
                    $class['schedule_date'] = $schedule->schedule_date;
                    $weekClasses->push($class);
                }
            }
        }

        // Classes today count
        $classesTodayCount = $todayClasses->count();

        // Get recent notices (visible to tutors)
        $recentNotices = Notice::where('status', 'published')
            ->where(function ($query) {
                $query->where('audience', 'all')
                    ->orWhere('audience', 'tutors');
            })
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->orderByRaw("FIELD(priority, 'urgent', 'high', 'normal', 'low')")
            ->orderBy('published_at', 'desc')
            ->take(3)
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
            'submittedThisMonth',
            'pendingReportsCount',
            'recentReports',
            'unreadNotifications',
            'unreadNotificationsCount',
            'upcomingAvailability',
            'todayClasses',
            'weekClasses',
            'classesTodayCount',
            'schedulePosted',
            'recentNotices'
        ));
    }
}
