<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\AttendanceRecord;
use App\Models\DailyClassSchedule;
use App\Models\Notice;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->hasRole('admin')) {
                abort(403, 'Unauthorized access.');
            }
            return $next($request);
        });
    }

    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Get today's schedule (one row per day with classes JSON)
        $todayScheduleRecord = DailyClassSchedule::whereDate('schedule_date', Carbon::today())->first();
        $todayClasses = $todayScheduleRecord ? ($todayScheduleRecord->classes ?? []) : [];

        // Sort classes by time
        usort($todayClasses, function($a, $b) {
            return strcmp($a['time'] ?? '00:00', $b['time'] ?? '00:00');
        });

        // Stat Cards Data
        $stats = [
            'totalStudents' => Student::count(),
            'activeStudents' => Student::where('status', 'active')->count(),
            'inactiveStudents' => Student::where('status', 'inactive')->count(),
            'graduatedStudents' => Student::where('status', 'graduated')->count(),
            'withdrawnStudents' => Student::where('status', 'withdrawn')->count(),

            'totalTutors' => Tutor::count(),
            'activeTutors' => Tutor::where('status', 'active')->count(),
            'inactiveTutors' => Tutor::where('status', 'inactive')->count(),
            'onLeaveTutors' => Tutor::where('status', 'on_leave')->count(),
            'resignedTutors' => Tutor::where('status', 'resigned')->count(),

            'todayClasses' => count($todayClasses),
            'completedClasses' => 0, // Can be calculated from attendance records
            'upcomingClasses' => count($todayClasses),

            'pendingAttendance' => AttendanceRecord::where('status', 'pending')->count(),
            'approvedAttendance' => AttendanceRecord::where('status', 'approved')->count(),
            'lateAttendance' => AttendanceRecord::where('is_late', true)->count(),
        ];

        // Check if schedule is posted
        $schedulePosted = $todayScheduleRecord && $todayScheduleRecord->posted_at !== null;
        $schedulePostedAt = $todayScheduleRecord?->posted_at;

        // Recent Activities (last 20)
        $recentActivities = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        // Latest Notices
        $notices = Notice::where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // Recent Students (last 5 added)
        $recentStudents = Student::with('tutor')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Recent Tutors (last 5 added)
        $recentTutors = Tutor::withCount('students')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'todayScheduleRecord',
            'todayClasses',
            'schedulePosted',
            'schedulePostedAt',
            'recentActivities',
            'notices',
            'recentStudents',
            'recentTutors'
        ));
    }

    /**
     * Post today's schedule.
     */
    public function postSchedule(Request $request)
    {
        $today = Carbon::today();

        $schedule = DailyClassSchedule::whereDate('schedule_date', $today)->first();

        if ($schedule) {
            $schedule->update([
                'status' => 'posted',
                'posted_at' => now(),
                'posted_by' => Auth::id(),
            ]);

            // Log the action
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'posted_schedule',
                'description' => 'Posted daily schedule for ' . $today->format('l, M j, Y'),
                'model_type' => DailyClassSchedule::class,
                'model_id' => $schedule->id,
            ]);

            return redirect()->back()->with('success', 'Schedule posted successfully!');
        }

        return redirect()->back()->with('error', 'No schedule found for today.');
    }

    /**
     * Get schedule in WhatsApp format.
     */
    public function getScheduleWhatsAppFormat()
    {
        $today = Carbon::today();
        $schedule = DailyClassSchedule::whereDate('schedule_date', $today)->first();
        $classes = $schedule ? ($schedule->classes ?? []) : [];

        // Sort classes by time
        usort($classes, function($a, $b) {
            return strcmp($a['time'] ?? '00:00', $b['time'] ?? '00:00');
        });

        $format = "📚 *Classes Scheduled for Today*\n";
        $format .= "📅 " . $today->format('l, M j, Y') . "\n\n";

        $count = 1;
        foreach ($classes as $class) {
            $studentName = $class['student_name'] ?? 'Unknown';
            $tutorName = $class['tutor_name'] ?? 'Unknown';
            $time = $class['time'] ?? '00:00';

            // Format time nicely
            try {
                $time = Carbon::parse($time)->format('g:ia');
            } catch (\Exception $e) {
                // Keep original time if parsing fails
            }

            $format .= "{$count}. {$studentName} - {$time} by {$tutorName}\n";
            $count++;
        }

        $format .= "\n✅ Total: " . ($count - 1) . " classes";

        if ($schedule && $schedule->footer_note) {
            $format .= "\n\n📌 " . $schedule->footer_note;
        }

        return response()->json(['format' => $format]);
    }
}
