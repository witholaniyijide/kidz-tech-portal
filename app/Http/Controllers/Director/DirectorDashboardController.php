<?php

namespace App\Http\Controllers\Director;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\AttendanceRecord;
use App\Models\Report;
use App\Models\DailyClassSchedule;
use App\Models\Payment;
use App\Models\TutorAssessment;
use App\Models\DirectorActivityLog;
use App\Models\Notice;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DirectorDashboardController extends Controller
{
    public function index()
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
        $monthlyRevenue = Payment::where('type', 'income')
                                 ->whereMonth('payment_date', Carbon::now()->month)
                                 ->whereYear('payment_date', Carbon::now()->year)
                                 ->sum('amount');

        // Revenue trend - last 6 months
        $revenueTrend = [];
        $revenueLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $revenueLabels[] = $date->format('M');
            $revenueTrend[] = Payment::where('type', 'income')
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

        // Today's Class Schedule - prioritize posted DailyClassSchedule, fallback to students' class_schedule
        $todayScheduleRecord = DailyClassSchedule::whereDate('schedule_date', Carbon::today())->first();
        $schedulePosted = $todayScheduleRecord && $todayScheduleRecord->posted_at !== null;
        $schedulePostedAt = $todayScheduleRecord?->posted_at;

        $todayClasses = [];

        // If admin has posted a schedule for today, use that
        if ($schedulePosted && $todayScheduleRecord && !empty($todayScheduleRecord->classes)) {
            foreach ($todayScheduleRecord->classes as $class) {
                $todayClasses[] = [
                    'time' => $class['time'] ?? '09:00',
                    'student' => $class['student_name'] ?? 'Unknown',
                    'tutor' => $class['tutor_name'] ?? 'Unassigned',
                    'level' => $class['level'] ?? 'Not set',
                    'class_link' => $class['class_link'] ?? null,
                ];
            }
        } else {
            // Fallback to students' class_schedule
            $todayName = Carbon::today()->format('l'); // e.g., "Monday"

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
        }

        // Sort by time
        usort($todayClasses, fn($a, $b) => strcmp($a['time'], $b['time']));

        // To-do list - dynamic based on pending items
        $pendingAttendance = AttendanceRecord::where('status', 'pending')->count();
        $pendingAssessments = TutorAssessment::where('status', 'approved-by-manager')->count();

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
        $recentActivities = DirectorActivityLog::with('director')
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

        // Notices for the notice board
        $notices = Notice::where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

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
            'schedulePosted' => $schedulePosted,
            'schedulePostedAt' => $schedulePostedAt,
            'todos' => $todos,
            'recentActivities' => $recentActivities,
            'notices' => $notices,
        ];

        return view('dashboards.director', $data);
    }
}
