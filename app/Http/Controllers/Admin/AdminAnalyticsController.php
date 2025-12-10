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
        // Student Analytics
        $studentStats = [
            'total' => Student::count(),
            'active' => Student::where('status', 'active')->count(),
            'inactive' => Student::where('status', 'inactive')->count(),
            'graduated' => Student::where('status', 'graduated')->count(),
            'withdrawn' => Student::where('status', 'withdrawn')->count(),
        ];

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

        // Classes per week distribution
        $classesPerWeek = Student::where('status', 'active')
            ->whereNotNull('classes_per_week')
            ->select('classes_per_week', DB::raw('COUNT(*) as count'))
            ->groupBy('classes_per_week')
            ->orderBy('classes_per_week')
            ->get();

        // Attendance rate overview
        $totalAttendance = AttendanceRecord::count();
        $approvedAttendance = AttendanceRecord::where('status', 'approved')->count();
        $attendanceRate = $totalAttendance > 0 ? round(($approvedAttendance / $totalAttendance) * 100, 1) : 0;

        // Tutor Analytics
        $tutorStats = [
            'total' => Tutor::count(),
            'active' => Tutor::where('status', 'active')->count(),
            'inactive' => Tutor::where('status', 'inactive')->count(),
            'on_leave' => Tutor::where('status', 'on_leave')->count(),
            'resigned' => Tutor::where('status', 'resigned')->count(),
        ];

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
            'studentStats',
            'enrollmentTrend',
            'studentsPerTutor',
            'classesPerWeek',
            'attendanceRate',
            'tutorStats',
            'tutorLoad',
            'tutorActivity'
        ));
    }

    /**
     * Get student analytics data (AJAX).
     */
    public function students(Request $request)
    {
        $studentStats = [
            'total' => Student::count(),
            'active' => Student::where('status', 'active')->count(),
            'inactive' => Student::where('status', 'inactive')->count(),
            'graduated' => Student::where('status', 'graduated')->count(),
            'withdrawn' => Student::where('status', 'withdrawn')->count(),
        ];

        $enrollmentTrend = Student::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return response()->json([
            'stats' => $studentStats,
            'trend' => $enrollmentTrend,
        ]);
    }

    /**
     * Get tutor analytics data (AJAX).
     */
    public function tutors(Request $request)
    {
        $tutorStats = [
            'total' => Tutor::count(),
            'active' => Tutor::where('status', 'active')->count(),
            'inactive' => Tutor::where('status', 'inactive')->count(),
            'on_leave' => Tutor::where('status', 'on_leave')->count(),
            'resigned' => Tutor::where('status', 'resigned')->count(),
        ];

        $tutorLoad = Tutor::withCount('students')
            ->where('status', 'active')
            ->orderBy('students_count', 'desc')
            ->get()
            ->map(function($tutor) {
                return [
                    'name' => $tutor->first_name,
                    'students' => $tutor->students_count,
                ];
            });

        return response()->json([
            'stats' => $tutorStats,
            'load' => $tutorLoad,
        ]);
    }

    // Note: Admin does NOT have access to financial analytics per specification
}
