<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\AttendanceRecord;
use App\Models\Report;
use App\Models\DailyClassSchedule;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
{
    $user = Auth::user();
    
    if ($user->hasRole('director')) {
        return $this->directorDashboard();
    } elseif ($user->hasRole('admin')) {
        return $this->adminDashboard();
    } elseif ($user->hasRole('manager')) {
        return $this->managerDashboard();
    } elseif ($user->hasRole('tutor')) {
        return $this->tutorDashboard();
    } elseif ($user->hasRole('parent')) {
        return redirect()->route('parent.dashboard');
    }
    
    return view('dashboard');
}

    /**
     * Public admin dashboard method for direct access
     */
    public function admin()
    {
        // Stats
        $totalStudents = Student::count();
        $activeStudents = Student::where('status', 'active')->count();
        $totalTutors = Tutor::count();
        $activeTutors = Tutor::where('status', 'active')->count();
        $inactiveTutors = Tutor::where('status', 'inactive')->count();
        $onLeaveTutors = Tutor::where('status', 'on_leave')->count();

        // Classes
        $todayClasses = DailyClassSchedule::where('schedule_date', today())->count();
        $completedClasses = AttendanceRecord::whereDate('class_date', today())->where('status', 'completed')->count();
        $upcomingClasses = $todayClasses - $completedClasses;

        // Attendance
        $pendingAttendance = AttendanceRecord::where('status', 'pending')->count();

        // Schedule (convert to array format for component)
        $todaySchedule = DailyClassSchedule::where('schedule_date', today())->first();
        $classes = [];
        if ($todaySchedule && isset($todaySchedule->classes)) {
            foreach ($todaySchedule->classes as $index => $class) {
                $classes[] = [
                    'time' => $class['time'] ?? '',
                    'name' => $class['name'] ?? '',
                    'tutor' => $class['tutor'] ?? '',
                    'students' => $class['students'] ?? 0,
                ];
            }
        }

        // TODO list
        $todos = [
            ['text' => 'Post today\'s schedule', 'completed' => false],
            ['text' => 'Review pending attendance', 'completed' => false],
            ['text' => 'Follow up inactive students', 'completed' => false],
            ['text' => 'Approve tutor submissions', 'completed' => false],
        ];

        // Recent notices
        $notices = [
            [
                'type' => 'Important',
                'color' => 'blue',
                'title' => 'New Class Time Updates',
                'content' => 'Please note the revised schedule for Wednesday classes...',
                'dateHuman' => '2 days ago',
                'date' => now()->subDays(2)->toDateString(),
            ],
            [
                'type' => 'General',
                'color' => 'purple',
                'title' => 'Tutor Training Session',
                'content' => 'Join us for the monthly tutor development workshop...',
                'dateHuman' => '5 days ago',
                'date' => now()->subDays(5)->toDateString(),
            ],
            [
                'type' => 'Reminder',
                'color' => 'green',
                'title' => 'Monthly Reports Due',
                'content' => 'Submit your monthly progress reports by the 25th...',
                'dateHuman' => '1 week ago',
                'date' => now()->subWeek()->toDateString(),
            ],
        ];

        // Recent students with eager loading
        $recentStudentsData = Student::with(['tutor'])
            ->latest()
            ->take(3)
            ->get()
            ->map(function ($student) {
                $initials = collect(explode(' ', $student->first_name . ' ' . $student->last_name))
                    ->map(fn($word) => strtoupper(substr($word, 0, 1)))
                    ->join('');

                return [
                    'id' => $student->id,
                    'name' => $student->first_name . ' ' . $student->last_name,
                    'email' => $student->email ?? 'N/A',
                    'tutor' => $student->tutor->name ?? 'Unassigned',
                    'lastClass' => $student->updated_at ? $student->updated_at->diffForHumans() : 'N/A',
                    'lastClassDate' => $student->updated_at ? $student->updated_at->toDateString() : '',
                    'status' => $student->status ?? 'inactive',
                    'initials' => $initials,
                    'avatarGradient' => collect(['from-blue-400 to-cyan-400', 'from-purple-400 to-pink-400', 'from-green-400 to-emerald-400'])->random(),
                ];
            })
            ->toArray();

        // Recent tutors with student count
        $recentTutorsData = Tutor::withCount('students')
            ->latest()
            ->take(3)
            ->get()
            ->map(function ($tutor) {
                $initials = collect(explode(' ', $tutor->name))
                    ->map(fn($word) => strtoupper(substr($word, 0, 1)))
                    ->join('');

                return [
                    'id' => $tutor->id,
                    'name' => $tutor->name,
                    'email' => $tutor->email ?? 'N/A',
                    'studentsCount' => $tutor->students_count ?? 0,
                    'lastActive' => $tutor->updated_at ? $tutor->updated_at->diffForHumans() : 'N/A',
                    'lastActiveDate' => $tutor->updated_at ? $tutor->updated_at->toDateString() : '',
                    'status' => $tutor->status ?? 'inactive',
                    'initials' => $initials,
                    'avatarGradient' => collect(['from-indigo-400 to-purple-400', 'from-pink-400 to-rose-400', 'from-orange-400 to-red-400'])->random(),
                ];
            })
            ->toArray();

        return view('dashboards.admin', [
            'stats' => [
                'totalStudents' => $totalStudents,
                'activeStudents' => $activeStudents,
                'totalTutors' => $totalTutors,
                'activeTutors' => $activeTutors,
                'inactiveTutors' => $inactiveTutors,
                'onLeaveTutors' => $onLeaveTutors,
                'todayClasses' => $todayClasses,
                'completedClasses' => $completedClasses,
                'upcomingClasses' => $upcomingClasses,
                'pendingAttendance' => $pendingAttendance,
            ],
            'classes' => $classes,
            'todos' => $todos,
            'notices' => $notices,
            'students' => $recentStudentsData,
            'tutors' => $recentTutorsData,
        ]);
    }

    private function directorDashboard()
    {
        $totalStudents = Student::count();
        $activeStudents = Student::where('status', 'active')->count();
        
        $currentMonth = Carbon::now()->format('F');
        $currentYear = Carbon::now()->format('Y');
        
        $monthlyReports = Report::where('month', $currentMonth)
                                ->where('year', $currentYear)
                                ->count();
        
        $approvedReports = Report::where('status', 'approved')
                                 ->where('month', $currentMonth)
                                 ->where('year', $currentYear)
                                 ->count();
        
        $pendingApprovals = Report::where('status', 'submitted')->count();
        
        $todayAttendance = AttendanceRecord::whereDate('class_date', Carbon::today())
                                          ->where('status', 'present')
                                          ->count();

        $totalAttendanceToday = AttendanceRecord::whereDate('class_date', Carbon::today())->count();
        
        $attendanceRate = $totalAttendanceToday > 0 
            ? round(($todayAttendance / $totalAttendanceToday) * 100, 1) 
            : 0;
        
        $data = [
            'totalStudents' => $totalStudents,
            'activeStudents' => $activeStudents,
            'totalTutors' => 16,
            'monthlyRevenue' => 2310000,
            'attendanceRate' => $attendanceRate,
            'recentReports' => $approvedReports,
            'monthlyReports' => $monthlyReports,
            'pendingApprovals' => $pendingApprovals,
        ];
        
        return view('dashboards.director', $data);
    }
    
    private function adminDashboard()
    {
        // Call the public admin method to get the structured data
        return $this->admin();
    }
    
    private function managerDashboard()
    {
        $totalStudents = Student::count();
        $activeStudents = Student::where('status', 'active')->count();
        $inactiveStudents = Student::where('status', 'inactive')->count();

        $totalTutors = \App\Models\Tutor::count();
        $activeTutors = \App\Models\Tutor::where('status', 'active')->count();

        $totalRevenue = \App\Models\Payment::where('status', 'completed')->sum('amount');
        $monthlyRevenue = \App\Models\Payment::where('status', 'completed')
                                            ->whereMonth('payment_date', Carbon::now()->month)
                                            ->whereYear('payment_date', Carbon::now()->year)
                                            ->sum('amount');

        $pendingReports = Report::where('status', 'submitted')->count();
        $approvedReports = Report::where('status', 'approved')
                                 ->whereMonth('created_at', Carbon::now()->month)
                                 ->count();

        $todayAttendance = AttendanceRecord::whereDate('class_date', Carbon::today())->count();
        $presentToday = AttendanceRecord::whereDate('class_date', Carbon::today())
                                       ->where('status', 'present')
                                       ->count();

        $attendanceRate = $todayAttendance > 0
            ? round(($presentToday / $todayAttendance) * 100, 1)
            : 0;

        $recentStudents = Student::latest()->take(5)->get();
        $recentReports = Report::with(['student', 'instructor'])
                              ->latest()
                              ->take(5)
                              ->get();

        return view('dashboards.manager', compact(
            'totalStudents', 'activeStudents', 'inactiveStudents',
            'totalTutors', 'activeTutors',
            'totalRevenue', 'monthlyRevenue',
            'pendingReports', 'approvedReports',
            'attendanceRate', 'todayAttendance', 'presentToday',
            'recentStudents', 'recentReports'
        ));
    }

    private function tutorDashboard()
    {
        $user = Auth::user();

        // Get tutor record
        $tutor = \App\Models\Tutor::where('user_id', $user->id)->first();

        // Get assigned students count (you may need to adjust based on your student-tutor relationship)
        $totalStudents = Student::count();
        $activeStudents = Student::where('status', 'active')->count();

        // Get reports submitted by this tutor
        $myReports = Report::where('instructor_id', $user->id)->count();
        $pendingReports = Report::where('instructor_id', $user->id)
                               ->where('status', 'submitted')
                               ->count();
        $approvedReports = Report::where('instructor_id', $user->id)
                                ->where('status', 'approved')
                                ->count();

        // Get today's attendance records submitted by this tutor
        $todayAttendance = AttendanceRecord::whereDate('class_date', Carbon::today())
                                          ->where('submitted_by', $user->id)
                                          ->count();

        $presentToday = AttendanceRecord::whereDate('class_date', Carbon::today())
                                       ->where('submitted_by', $user->id)
                                       ->where('status', 'present')
                                       ->count();

        // Recent students
        $recentStudents = Student::latest()->take(8)->get();

        // Recent reports by this tutor
        $recentReports = Report::with('student')
                              ->where('instructor_id', $user->id)
                              ->latest()
                              ->take(5)
                              ->get();

        // Upcoming sessions or tasks (placeholder - adjust based on your needs)
        $upcomingSessions = [];

        return view('dashboards.tutor', compact(
            'tutor', 'totalStudents', 'activeStudents',
            'myReports', 'pendingReports', 'approvedReports',
            'todayAttendance', 'presentToday',
            'recentStudents', 'recentReports', 'upcomingSessions'
        ));
    }
    
    private function parentDashboard()
    {
        return view('dashboards.parent');
    }
}
