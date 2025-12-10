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
            
            'todayClasses' => DailyClassSchedule::whereDate('schedule_date', Carbon::today())->count(),
            'completedClasses' => DailyClassSchedule::whereDate('schedule_date', Carbon::today())
                ->where('status', 'completed')->count(),
            'upcomingClasses' => DailyClassSchedule::whereDate('schedule_date', Carbon::today())
                ->where('status', 'scheduled')->count(),
            
            'pendingAttendance' => AttendanceRecord::where('status', 'pending')->count(),
            'approvedAttendance' => AttendanceRecord::where('status', 'approved')->count(),
            'lateAttendance' => AttendanceRecord::where('is_late', true)->count(),
        ];

        // Today's Schedule
        $todaySchedule = DailyClassSchedule::with(['student', 'tutor'])
            ->whereDate('schedule_date', Carbon::today())
            ->orderBy('class_time')
            ->get();

        // Check if schedule is posted
        $schedulePosted = DailyClassSchedule::whereDate('schedule_date', Carbon::today())
            ->whereNotNull('posted_at')
            ->exists();
        
        $schedulePostedAt = DailyClassSchedule::whereDate('schedule_date', Carbon::today())
            ->whereNotNull('posted_at')
            ->first()?->posted_at;

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
            'todaySchedule',
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
        
        // Update all today's schedules as posted
        DailyClassSchedule::whereDate('schedule_date', $today)
            ->update([
                'posted_at' => now(),
                'posted_by' => Auth::id(),
            ]);

        // Log the action
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'posted_schedule',
            'description' => 'Posted daily schedule for ' . $today->format('l, M j, Y'),
            'model_type' => DailyClassSchedule::class,
        ]);

        return redirect()->back()->with('success', 'Schedule posted successfully!');
    }

    /**
     * Get schedule in WhatsApp format.
     */
    public function getScheduleWhatsAppFormat()
    {
        $today = Carbon::today();
        $schedules = DailyClassSchedule::with(['student', 'tutor'])
            ->whereDate('schedule_date', $today)
            ->orderBy('class_time')
            ->get();

        $format = "📚 *Classes Scheduled for Today*\n";
        $format .= "📅 " . $today->format('l, M j, Y') . "\n\n";

        $count = 1;
        foreach ($schedules as $schedule) {
            $studentName = $schedule->student->first_name ?? 'Unknown';
            $tutorName = $schedule->tutor->first_name ?? 'Unknown';
            $time = Carbon::parse($schedule->class_time)->format('g:ia');
            
            $format .= "{$count}. {$studentName} - {$time} by {$tutorName}\n";
            $count++;
        }

        $format .= "\n✅ Total: " . ($count - 1) . " classes";

        return response()->json(['format' => $format]);
    }
}
