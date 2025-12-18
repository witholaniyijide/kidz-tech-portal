<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\AttendanceRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminAnalyticsController extends Controller
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
     * Display analytics dashboard (Students & Tutors only - NO finance).
     */
    public function index()
    {
        // Attendance rate overview
        $totalAttendance = AttendanceRecord::count();
        $approvedAttendance = AttendanceRecord::where('status', 'approved')->count();
        $attendanceRate = $totalAttendance > 0 ? round(($approvedAttendance / $totalAttendance) * 100, 1) : 0;

        // Classes this month (from attendance records)
        $classesThisMonth = AttendanceRecord::whereMonth('class_date', Carbon::now()->month)
            ->whereYear('class_date', Carbon::now()->year)
            ->count();

        // Average students per tutor
        $activeTutors = Tutor::where('status', 'active')->count();
        $activeStudents = Student::where('status', 'active')->count();
        $avgStudentsPerTutor = $activeTutors > 0 ? round($activeStudents / $activeTutors, 1) : 0;

        // Stats for the view
        $stats = [
            'total_students' => Student::count(),
            'active_students' => $activeStudents,
            'inactive_students' => Student::where('status', 'inactive')->count(),
            'graduated_students' => Student::where('status', 'graduated')->count(),
            'withdrawn_students' => Student::where('status', 'withdrawn')->count(),
            'total_tutors' => Tutor::count(),
            'active_tutors' => $activeTutors,
            'inactive_tutors' => Tutor::where('status', 'inactive')->count(),
            'on_leave_tutors' => Tutor::where('status', 'on_leave')->count(),
            'resigned_tutors' => Tutor::where('status', 'resigned')->count(),
            'classes_this_month' => $classesThisMonth,
            'avg_attendance_rate' => $attendanceRate,
            'avg_students_per_tutor' => $avgStudentsPerTutor,
        ];

        // Monthly classes breakdown (for chart)
        $monthlyClasses = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyClasses[$i] = AttendanceRecord::whereMonth('class_date', $i)
                ->whereYear('class_date', Carbon::now()->year)
                ->count();
        }

        // Enrollment trend (last 12 months)
        $enrollmentTrend = Student::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Students per tutor
        $studentsPerTutor = Tutor::withCount('students')
            ->where('status', 'active')
            ->orderBy('students_count', 'desc')
            ->take(10)
            ->get();

        // Tutor load (students per tutor)
        $tutorLoad = Tutor::withCount('students')
            ->where('status', 'active')
            ->get()
            ->map(function($tutor) {
                return [
                    'name' => $tutor->first_name . ' ' . $tutor->last_name,
                    'students' => $tutor->students_count,
                ];
            });

        // Tutor activity (attendance submissions per tutor this month)
        $tutorActivity = AttendanceRecord::select('tutor_id', DB::raw('COUNT(*) as submissions'))
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('tutor_id')
            ->with('tutor:id,first_name,last_name')
            ->orderBy('submissions', 'desc')
            ->take(10)
            ->get();

        return view('admin.analytics.index', compact(
            'stats',
            'monthlyClasses',
            'enrollmentTrend',
            'studentsPerTutor',
            'tutorLoad',
            'tutorActivity'
        ));
    }

    /**
     * Display student analytics page.
     */
    public function students(Request $request)
    {
        $stats = [
            'total' => Student::count(),
            'active' => Student::where('status', 'active')->count(),
            'inactive' => Student::where('status', 'inactive')->count(),
            'graduated' => Student::where('status', 'graduated')->count(),
            'withdrawn' => Student::where('status', 'withdrawn')->count(),
        ];

        // Classes per week distribution
        $classesPerWeek = [];
        for ($i = 1; $i <= 7; $i++) {
            $classesPerWeek[$i] = Student::where('classes_per_week', $i)->count();
        }

        // Top students by attendance count
        $topStudents = Student::withCount('attendanceRecords as attendances_count')
            ->with('tutor:id,first_name,last_name')
            ->where('status', 'active')
            ->orderBy('attendances_count', 'desc')
            ->take(10)
            ->get();

        // Students without assigned tutor
        $studentsWithoutTutor = Student::whereNull('tutor_id')
            ->where('status', 'active')
            ->get();

        return view('admin.analytics.students', compact(
            'stats',
            'classesPerWeek',
            'topStudents',
            'studentsWithoutTutor'
        ));
    }

    /**
     * Display tutor analytics page.
     */
    public function tutors(Request $request)
    {
        $activeTutors = Tutor::where('status', 'active')->count();
        $activeStudents = Student::where('status', 'active')->count();

        $stats = [
            'total' => Tutor::count(),
            'active' => $activeTutors,
            'inactive' => Tutor::where('status', 'inactive')->count(),
            'on_leave' => Tutor::where('status', 'on_leave')->count(),
            'resigned' => Tutor::where('status', 'resigned')->count(),
            'avg_students' => $activeTutors > 0 ? round($activeStudents / $activeTutors, 1) : 0,
        ];

        // Tutor workloads (students per tutor)
        $tutorWorkloads = Tutor::withCount('students')
            ->where('status', 'active')
            ->orderBy('students_count', 'desc')
            ->get();

        // Classes completed this month per tutor
        $tutorClassesThisMonth = Tutor::withCount(['students', 'attendanceRecords as attendances_count' => function($query) {
                $query->whereMonth('class_date', Carbon::now()->month)
                      ->whereYear('class_date', Carbon::now()->year);
            }])
            ->where('status', 'active')
            ->orderBy('attendances_count', 'desc')
            ->take(10)
            ->get();

        // All tutors with performance metrics
        $allTutors = Tutor::withCount(['students',
            'attendanceRecords as classes_this_month' => function($query) {
                $query->whereMonth('class_date', Carbon::now()->month)
                      ->whereYear('class_date', Carbon::now()->year);
            },
            'attendanceRecords as total_classes',
            'attendanceRecords as late_submissions' => function($query) {
                $query->where('status', 'late');
            }
        ])
        ->orderBy('first_name')
        ->get();

        return view('admin.analytics.tutors', compact(
            'stats',
            'tutorWorkloads',
            'tutorClassesThisMonth',
            'allTutors'
        ));
    }

    // Note: Admin does NOT have access to financial analytics per specification
}
