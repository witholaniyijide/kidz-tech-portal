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
                $fullName = trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? ''));

                $initials = collect(explode(' ', $fullName))
                    ->filter()
                    ->map(fn($word) => strtoupper(substr($word, 0, 1)))
                    ->take(2)
                    ->join('');

                // Get tutor name properly
                $tutorName = 'Unassigned';
                if ($student->tutor) {
                    $tutorName = trim(($student->tutor->first_name ?? '') . ' ' . ($student->tutor->last_name ?? ''));
                    if (empty($tutorName)) {
                        $tutorName = $student->tutor->email ?? 'Unassigned';
                    }
                }

                return [
                    'id' => $student->id,
                    'name' => $fullName,
                    'email' => $student->email ?? 'N/A',
                    'tutor' => $tutorName,
                    'lastClass' => $student->updated_at ? $student->updated_at->diffForHumans() : 'N/A',
                    'lastClassDate' => $student->updated_at ? $student->updated_at->toDateString() : '',
                    'status' => $student->status ?? 'inactive',
                    'initials' => $initials ?: 'NA',
                    'avatarGradient' => collect([
                        'bg-gradient-to-br from-blue-500 to-cyan-600',
                        'bg-gradient-to-br from-purple-500 to-pink-600',
                        'bg-gradient-to-br from-green-500 to-emerald-600',
                        'bg-gradient-to-br from-teal-500 to-blue-600',
                        'bg-gradient-to-br from-violet-500 to-fuchsia-600'
                    ])->random(),
                ];
            })
            ->toArray();

        // Recent tutors with student count
        $recentTutorsData = Tutor::withCount('students')
            ->latest()
            ->take(3)
            ->get()
            ->map(function ($tutor) {
                $fullName = trim(($tutor->first_name ?? '') . ' ' . ($tutor->last_name ?? ''));

                // If no name, fall back to email username
                if (empty($fullName)) {
                    $fullName = explode('@', $tutor->email ?? 'Unknown')[0];
                    $fullName = ucwords(str_replace(['.', '_', '-'], ' ', $fullName));
                }

                $initials = collect(explode(' ', $fullName))
                    ->filter()
                    ->map(fn($word) => strtoupper(substr($word, 0, 1)))
                    ->take(2)
                    ->join('');

                return [
                    'id' => $tutor->id,
                    'name' => $fullName,
                    'email' => $tutor->email ?? 'N/A',
                    'studentsCount' => $tutor->students_count ?? 0,
                    'lastActive' => $tutor->updated_at ? $tutor->updated_at->diffForHumans() : 'N/A',
                    'lastActiveDate' => $tutor->updated_at ? $tutor->updated_at->toDateString() : '',
                    'status' => $tutor->status ?? 'inactive',
                    'initials' => $initials ?: 'NA',
                    'avatarGradient' => collect([
                        'bg-gradient-to-br from-indigo-500 to-purple-600',
                        'bg-gradient-to-br from-pink-500 to-rose-600',
                        'bg-gradient-to-br from-orange-500 to-amber-600',
                        'bg-gradient-to-br from-rose-500 to-red-600',
                        'bg-gradient-to-br from-fuchsia-500 to-purple-600'
                    ])->random(),
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
        // Core stats - dynamic
        $totalStudents = Student::count();
        $activeStudents = Student::where('status', 'active')->count();
        $totalTutors = Tutor::count();
        $activeTutors = Tutor::where('status', 'active')->count();
        
        $currentMonth = Carbon::now()->format('F');
        $currentYear = Carbon::now()->format('Y');
        
        // Reports stats
        $monthlyReports = Report::where('month', $currentMonth)
                                ->where('year', $currentYear)
                                ->count();
        
        $approvedReports = Report::where('status', 'approved')
                                 ->where('month', $currentMonth)
                                 ->where('year', $currentYear)
                                 ->count();
        
        $pendingApprovals = Report::where('status', 'submitted')->count();
        
        // Attendance stats
        $todayAttendance = AttendanceRecord::whereDate('class_date', Carbon::today())
                                          ->where('status', 'present')
                                          ->count();

        $totalAttendanceToday = AttendanceRecord::whereDate('class_date', Carbon::today())->count();
        
        $attendanceRate = $totalAttendanceToday > 0 
            ? round(($todayAttendance / $totalAttendanceToday) * 100, 1) 
            : 0;

        // Monthly Revenue - dynamic from payments
        $monthlyRevenue = \App\Models\Payment::where('type', 'income')
                                             ->whereMonth('payment_date', Carbon::now()->month)
                                             ->whereYear('payment_date', Carbon::now()->year)
                                             ->sum('amount');

        // Revenue trend - last 6 months
        $revenueTrend = [];
        $revenueLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $revenueLabels[] = $date->format('M');
            $revenueTrend[] = \App\Models\Payment::where('type', 'income')
                                                  ->whereMonth('payment_date', $date->month)
                                                  ->whereYear('payment_date', $date->year)
                                                  ->sum('amount');
        }

        // Weekly attendance data - last 7 days
        $attendanceData = [];
        $attendanceLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $attendanceLabels[] = $date->format('D');
            $total = AttendanceRecord::whereDate('class_date', $date)->count();
            $present = AttendanceRecord::whereDate('class_date', $date)->where('status', 'present')->count();
            $attendanceData['present'][] = $total > 0 ? round(($present / $total) * 100) : 0;
            $attendanceData['absent'][] = $total > 0 ? round((($total - $present) / $total) * 100) : 0;
        }

        // Student distribution by status
        $studentDistribution = [
            'Active' => Student::where('status', 'active')->count(),
            'Inactive' => Student::where('status', 'inactive')->count(),
            'Graduated' => Student::where('status', 'graduated')->count(),
            'Withdrawn' => Student::where('status', 'withdrawn')->count(),
        ];

        // Today's Class Schedule - from students with class_schedule
        $todayName = Carbon::today()->format('l'); // e.g., "Monday"
        $todayClasses = [];
        
        $studentsWithSchedule = Student::with('tutor')
            ->where('status', 'active')
            ->whereNotNull('class_schedule')
            ->get();
            
        foreach ($studentsWithSchedule as $student) {
            $schedules = is_array($student->class_schedule) 
                ? $student->class_schedule 
                : json_decode($student->class_schedule, true) ?? [];
            
            foreach ($schedules as $schedule) {
                if (isset($schedule['day']) && $schedule['day'] === $todayName) {
                    $todayClasses[] = [
                        'time' => $schedule['time'] ?? '09:00',
                        'student' => $student->first_name . ' ' . $student->last_name,
                        'tutor' => $student->tutor ? $student->tutor->first_name . ' ' . $student->tutor->last_name : 'Unassigned',
                        'level' => $student->current_level ?? 'Not set',
                    ];
                }
            }
        }
        
        // Sort by time
        usort($todayClasses, fn($a, $b) => strcmp($a['time'], $b['time']));

        // To-do list - dynamic based on pending items
        $pendingAttendance = AttendanceRecord::where('status', 'pending')->count();
        $pendingAssessments = \App\Models\TutorAssessment::where('status', 'approved-by-manager')->count();
        
        $todos = [
            [
                'text' => "Review {$pendingApprovals} pending report(s)",
                'completed' => $pendingApprovals == 0,
                'link' => route('director.reports.index'),
                'count' => $pendingApprovals,
            ],
            [
                'text' => "Approve {$pendingAttendance} attendance record(s)",
                'completed' => $pendingAttendance == 0,
                'link' => route('director.attendance.index'),
                'count' => $pendingAttendance,
            ],
            [
                'text' => "Review {$pendingAssessments} assessment(s)",
                'completed' => $pendingAssessments == 0,
                'link' => route('director.assessments.index'),
                'count' => $pendingAssessments,
            ],
            [
                'text' => 'Check analytics dashboard',
                'completed' => false,
                'link' => route('director.analytics.index'),
                'count' => 0,
            ],
        ];

        // Recent Activity from DirectorActivityLog
        $recentActivities = \App\Models\DirectorActivityLog::with('director')
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($log) {
                $icons = [
                    'report_approved' => ['icon' => 'document-check', 'gradient' => 'from-green-500 to-emerald-600'],
                    'report_rejected' => ['icon' => 'x-circle', 'gradient' => 'from-red-500 to-pink-600'],
                    'assessment_approved' => ['icon' => 'academic-cap', 'gradient' => 'from-purple-500 to-indigo-600'],
                    'attendance_approved' => ['icon' => 'clipboard-check', 'gradient' => 'from-blue-500 to-cyan-600'],
                    'income_recorded' => ['icon' => 'currency-dollar', 'gradient' => 'from-green-500 to-teal-600'],
                    'expense_recorded' => ['icon' => 'receipt-percent', 'gradient' => 'from-orange-500 to-red-600'],
                    'notice_created' => ['icon' => 'speakerphone', 'gradient' => 'from-yellow-500 to-orange-600'],
                    'default' => ['icon' => 'bell', 'gradient' => 'from-gray-500 to-gray-600'],
                ];
                
                $style = $icons[$log->action_type] ?? $icons['default'];
                
                return [
                    'title' => ucwords(str_replace('_', ' ', $log->action_type)),
                    'description' => $log->payload['description'] ?? ucwords(str_replace('_', ' ', $log->action_type)) . ' action performed',
                    'time' => $log->created_at->diffForHumans(),
                    'icon' => $style['icon'],
                    'gradient' => $style['gradient'],
                ];
            });

        // If no activity logs, show placeholder
        if ($recentActivities->isEmpty()) {
            $recentActivities = collect([
                [
                    'title' => 'Welcome to Director Dashboard',
                    'description' => 'Your activity will appear here as you use the system',
                    'time' => 'Just now',
                    'icon' => 'sparkles',
                    'gradient' => 'from-purple-500 to-pink-600',
                ],
            ]);
        }
        
        $data = [
            'totalStudents' => $totalStudents,
            'activeStudents' => $activeStudents,
            'totalTutors' => $totalTutors,
            'activeTutors' => $activeTutors,
            'monthlyRevenue' => $monthlyRevenue,
            'attendanceRate' => $attendanceRate,
            'recentReports' => $approvedReports,
            'monthlyReports' => $monthlyReports,
            'pendingApprovals' => $pendingApprovals,
            // Chart data
            'revenueTrend' => $revenueTrend,
            'revenueLabels' => $revenueLabels,
            'attendanceData' => $attendanceData,
            'attendanceLabels' => $attendanceLabels,
            'studentDistribution' => $studentDistribution,
            // Schedule & Todos
            'todayClasses' => $todayClasses,
            'todos' => $todos,
            'recentActivities' => $recentActivities,
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
        $todayAttendance = 0;
        $presentToday = 0;

        if ($tutor) {
            $todayAttendance = AttendanceRecord::whereDate('class_date', Carbon::today())
                                              ->where('tutor_id', $tutor->id)
                                              ->count();

            $presentToday = AttendanceRecord::whereDate('class_date', Carbon::today())
                                           ->where('tutor_id', $tutor->id)
                                           ->where('status', 'approved')
                                           ->count();
        }

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
