<?php

namespace App\Http\Controllers\Director;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\TutorReport;
use App\Models\TutorAssessment;
use App\Models\DirectorActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->hasRole('director') && !Auth::user()->hasRole('admin')) {
                abort(403, 'Unauthorized access.');
            }
            return $next($request);
        });
    }

    /**
     * Display analytics dashboard.
     */
    public function index()
    {
        // Get summary stats with caching
        $stats = $this->getDashboardStats();

        return view('director.analytics.index', compact('stats'));
    }

    /**
     * Get dashboard statistics (cached).
     */
    protected function getDashboardStats()
    {
        return Cache::remember('director.analytics.dashboard.stats', 300, function () {
            return [
                'total_students' => Student::count(),
                'active_students' => Student::where('status', 'active')->count(),
                'inactive_students' => Student::where('status', 'inactive')->count(),

                'total_tutors' => Tutor::count(),
                'active_tutors' => Tutor::where('status', 'active')->count(),
                'on_leave_tutors' => Tutor::where('status', 'on_leave')->count(),

                'reports_this_month' => TutorReport::whereYear('created_at', now()->year)
                    ->whereMonth('created_at', now()->month)
                    ->count(),
                'reports_submitted_this_month' => TutorReport::whereYear('created_at', now()->year)
                    ->whereMonth('created_at', now()->month)
                    ->where('status', 'submitted')
                    ->count(),
                'reports_approved_this_month' => TutorReport::whereYear('created_at', now()->year)
                    ->whereMonth('created_at', now()->month)
                    ->where('status', 'approved-by-director')
                    ->count(),

                'assessments_this_month' => TutorAssessment::whereYear('created_at', now()->year)
                    ->whereMonth('created_at', now()->month)
                    ->count(),
                'assessments_approved_this_month' => TutorAssessment::whereYear('created_at', now()->year)
                    ->whereMonth('created_at', now()->month)
                    ->where('status', 'approved-by-director')
                    ->count(),

                'pending_reports' => TutorReport::where('status', 'approved-by-manager')->count(),
                'pending_assessments' => TutorAssessment::whereIn('status', ['submitted', 'approved-by-manager'])->count(),

                'activity_today' => DirectorActivityLog::whereDate('created_at', today())->count(),
            ];
        });
    }

    /**
     * Get enrollments data for chart (JSON).
     */
    public function getEnrollmentsData()
    {
        $data = Cache::remember('director.analytics.enrollments', 3600, function () {
            // Last 12 months enrollment data
            $enrollments = DB::table('students')
                ->select(
                    DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                    DB::raw('COUNT(*) as new_enrollments'),
                    DB::raw("SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active"),
                    DB::raw("SUM(CASE WHEN status = 'inactive' THEN 1 ELSE 0 END) as inactive")
                )
                ->where('created_at', '>=', now()->subMonths(12))
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->get();

            $labels = $enrollments->pluck('month')->toArray();

            return [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'New Enrollments',
                        'data' => $enrollments->pluck('new_enrollments')->toArray(),
                        'borderColor' => 'rgb(30, 64, 175)',
                        'backgroundColor' => 'rgba(30, 64, 175, 0.1)',
                        'tension' => 0.4,
                    ],
                    [
                        'label' => 'Active Students',
                        'data' => $enrollments->pluck('active')->toArray(),
                        'borderColor' => 'rgb(124, 58, 237)',
                        'backgroundColor' => 'rgba(124, 58, 237, 0.1)',
                        'tension' => 0.4,
                    ]
                ],
                'table' => $enrollments->toArray()
            ];
        });

        return response()->json($data);
    }

    /**
     * Get reports analytics data (JSON).
     */
    public function getReportsData()
    {
        $data = Cache::remember('director.analytics.reports', 600, function () {
            // Monthly report submissions (last 12 months)
            $monthlyReports = DB::table('tutor_reports')
                ->select(
                    DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                    DB::raw('COUNT(*) as total')
                )
                ->where('created_at', '>=', now()->subMonths(12))
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->get();

            // Current month status breakdown
            $statusBreakdown = TutorReport::select('status', DB::raw('COUNT(*) as count'))
                ->whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status')
                ->toArray();

            // Top 10 tutors by reports this month
            $topTutors = DB::table('tutor_reports')
                ->join('tutors', 'tutor_reports.tutor_id', '=', 'tutors.id')
                ->select(
                    'tutors.first_name',
                    'tutors.last_name',
                    DB::raw('COUNT(*) as report_count'),
                    DB::raw('AVG(tutor_reports.attendance_score) as avg_attendance')
                )
                ->whereYear('tutor_reports.created_at', now()->year)
                ->whereMonth('tutor_reports.created_at', now()->month)
                ->groupBy('tutors.id', 'tutors.first_name', 'tutors.last_name')
                ->orderBy('report_count', 'desc')
                ->limit(10)
                ->get();

            return [
                'monthly' => [
                    'labels' => $monthlyReports->pluck('month')->toArray(),
                    'datasets' => [
                        [
                            'label' => 'Reports Submitted',
                            'data' => $monthlyReports->pluck('total')->toArray(),
                            'borderColor' => 'rgb(30, 64, 175)',
                            'backgroundColor' => 'rgba(30, 64, 175, 0.5)',
                        ]
                    ]
                ],
                'status' => [
                    'labels' => array_keys($statusBreakdown),
                    'datasets' => [
                        [
                            'label' => 'Reports by Status',
                            'data' => array_values($statusBreakdown),
                            'backgroundColor' => [
                                'rgba(156, 163, 175, 0.8)', // draft - gray
                                'rgba(99, 102, 241, 0.8)',  // submitted - indigo
                                'rgba(251, 191, 36, 0.8)',  // approved-by-manager - yellow
                                'rgba(34, 197, 94, 0.8)',   // approved-by-director - green
                            ]
                        ]
                    ]
                ],
                'top_tutors' => $topTutors->toArray()
            ];
        });

        return response()->json($data);
    }

    /**
     * Get tutor performance data (JSON).
     */
    public function getTutorPerformanceData()
    {
        $data = Cache::remember('director.analytics.tutor_performance', 600, function () {
            // Students per tutor (top 20)
            $studentsPerTutor = DB::table('tutors')
                ->leftJoin('tutor_reports', 'tutors.id', '=', 'tutor_reports.tutor_id')
                ->select(
                    'tutors.first_name',
                    'tutors.last_name',
                    DB::raw('COUNT(DISTINCT tutor_reports.student_id) as student_count')
                )
                ->where('tutors.status', 'active')
                ->groupBy('tutors.id', 'tutors.first_name', 'tutors.last_name')
                ->orderBy('student_count', 'desc')
                ->limit(20)
                ->get();

            // Average attendance by tutor
            $attendanceByTutor = DB::table('tutors')
                ->leftJoin('tutor_reports', 'tutors.id', '=', 'tutor_reports.tutor_id')
                ->select(
                    'tutors.first_name',
                    'tutors.last_name',
                    'tutors.id',
                    DB::raw('AVG(tutor_reports.attendance_score) as avg_attendance')
                )
                ->where('tutors.status', 'active')
                ->whereNotNull('tutor_reports.attendance_score')
                ->groupBy('tutors.id', 'tutors.first_name', 'tutors.last_name')
                ->having('avg_attendance', '>', 0)
                ->orderBy('avg_attendance', 'desc')
                ->limit(20)
                ->get();

            // Tutors with low attendance (<70%)
            $lowAttendanceTutors = $attendanceByTutor->filter(function ($tutor) {
                return $tutor->avg_attendance < 70;
            })->values();

            return [
                'students_per_tutor' => [
                    'labels' => $studentsPerTutor->map(fn($t) => $t->first_name . ' ' . $t->last_name)->toArray(),
                    'datasets' => [
                        [
                            'label' => 'Students per Tutor',
                            'data' => $studentsPerTutor->pluck('student_count')->toArray(),
                            'backgroundColor' => 'rgba(124, 58, 237, 0.7)',
                        ]
                    ]
                ],
                'attendance' => [
                    'labels' => $attendanceByTutor->map(fn($t) => $t->first_name . ' ' . $t->last_name)->toArray(),
                    'datasets' => [
                        [
                            'label' => 'Average Attendance %',
                            'data' => $attendanceByTutor->pluck('avg_attendance')->toArray(),
                            'backgroundColor' => $attendanceByTutor->map(function($t) {
                                return $t->avg_attendance >= 90 ? 'rgba(34, 197, 94, 0.7)' :
                                       ($t->avg_attendance >= 75 ? 'rgba(59, 130, 246, 0.7)' :
                                       ($t->avg_attendance >= 60 ? 'rgba(251, 191, 36, 0.7)' : 'rgba(239, 68, 68, 0.7)'));
                            })->toArray(),
                        ]
                    ]
                ],
                'low_attendance_tutors' => $lowAttendanceTutors->toArray()
            ];
        });

        return response()->json($data);
    }

    /**
     * Get assessment metrics data (JSON).
     */
    public function getAssessmentData()
    {
        $data = Cache::remember('director.analytics.assessments', 1800, function () {
            // Average performance score by month (last 12 months)
            $monthlyPerformance = DB::table('tutor_assessments')
                ->select(
                    DB::raw("DATE_FORMAT(assessment_month, '%Y-%m') as month"),
                    DB::raw('AVG(performance_score) as avg_score'),
                    DB::raw('COUNT(*) as count')
                )
                ->where('assessment_month', '>=', now()->subMonths(12)->format('Y-m'))
                ->whereNotNull('performance_score')
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->get();

            // Distribution of professionalism ratings
            $ratingDistribution = TutorAssessment::select(
                    DB::raw('ROUND(professionalism_rating / 10) * 10 as rating_range'),
                    DB::raw('COUNT(*) as count')
                )
                ->whereNotNull('professionalism_rating')
                ->groupBy('rating_range')
                ->orderBy('rating_range', 'desc')
                ->get();

            return [
                'monthly_performance' => [
                    'labels' => $monthlyPerformance->pluck('month')->toArray(),
                    'datasets' => [
                        [
                            'label' => 'Average Performance Score',
                            'data' => $monthlyPerformance->pluck('avg_score')->toArray(),
                            'borderColor' => 'rgb(124, 58, 237)',
                            'backgroundColor' => 'rgba(124, 58, 237, 0.1)',
                            'tension' => 0.4,
                        ]
                    ]
                ],
                'rating_distribution' => [
                    'labels' => $ratingDistribution->pluck('rating_range')->map(fn($r) => $r . '-' . ($r + 10))->toArray(),
                    'datasets' => [
                        [
                            'label' => 'Professionalism Rating Distribution',
                            'data' => $ratingDistribution->pluck('count')->toArray(),
                            'backgroundColor' => [
                                'rgba(239, 68, 68, 0.7)',
                                'rgba(251, 191, 36, 0.7)',
                                'rgba(59, 130, 246, 0.7)',
                                'rgba(34, 197, 94, 0.7)',
                            ]
                        ]
                    ]
                ]
            ];
        });

        return response()->json($data);
    }

    /**
     * Export reports data as CSV.
     */
    public function exportReportsCsv(Request $request)
    {
        // Validate month parameter
        $request->validate([
            'month' => 'nullable|date_format:Y-m'
        ]);

        $month = $request->input('month', now()->format('Y-m'));

        // Log export action
        DirectorActivityLog::logAction(
            Auth::id(),
            'exported_reports_csv',
            null,
            null,
            $request->ip(),
            $request->userAgent()
        );

        $filename = "reports_{$month}.csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($month) {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, [
                'Report ID',
                'Student Name',
                'Tutor Name',
                'Month',
                'Status',
                'Attendance Score',
                'Performance Rating',
                'Manager Comment',
                'Director Comment',
                'Submitted At',
                'Approved by Manager At',
                'Approved by Director At'
            ]);

            // Stream data in chunks
            TutorReport::with(['student', 'tutor'])
                ->where('month', $month)
                ->orderBy('created_at', 'desc')
                ->chunk(100, function($reports) use ($file) {
                    foreach ($reports as $report) {
                        fputcsv($file, [
                            $report->id,
                            $report->student->fullName() ?? 'N/A',
                            $report->tutor->fullName() ?? 'N/A',
                            $report->month,
                            $report->status,
                            $report->attendance_score ?? 'N/A',
                            $report->performance_rating ?? 'N/A',
                            $report->manager_comment ?? '',
                            $report->director_comment ?? '',
                            $report->submitted_at ? $report->submitted_at->format('Y-m-d H:i:s') : '',
                            $report->approved_by_manager_at ? $report->approved_by_manager_at->format('Y-m-d H:i:s') : '',
                            $report->approved_by_director_at ? $report->approved_by_director_at->format('Y-m-d H:i:s') : '',
                        ]);
                    }
                });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export tutors performance data as CSV.
     */
    public function exportTutorsCsv(Request $request)
    {
        // Validate month parameter
        $request->validate([
            'month' => 'nullable|date_format:Y-m'
        ]);

        $month = $request->input('month', now()->format('Y-m'));

        // Log export action
        DirectorActivityLog::logAction(
            Auth::id(),
            'exported_tutors_csv',
            null,
            null,
            $request->ip(),
            $request->userAgent()
        );

        $filename = "tutors_performance_{$month}.csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($month) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Tutor ID',
                'Tutor Name',
                'Email',
                'Status',
                'Reports Count',
                'Avg Attendance Score',
                'Assessments Count',
                'Avg Performance Score'
            ]);

            DB::table('tutors')
                ->leftJoin('tutor_reports', function($join) use ($month) {
                    $join->on('tutors.id', '=', 'tutor_reports.tutor_id')
                         ->where('tutor_reports.month', '=', $month);
                })
                ->leftJoin('tutor_assessments', function($join) use ($month) {
                    $join->on('tutors.id', '=', 'tutor_assessments.tutor_id')
                         ->where('tutor_assessments.assessment_month', '=', $month);
                })
                ->select(
                    'tutors.id',
                    'tutors.first_name',
                    'tutors.last_name',
                    'tutors.email',
                    'tutors.status',
                    DB::raw('COUNT(DISTINCT tutor_reports.id) as reports_count'),
                    DB::raw('AVG(tutor_reports.attendance_score) as avg_attendance'),
                    DB::raw('COUNT(DISTINCT tutor_assessments.id) as assessments_count'),
                    DB::raw('AVG(tutor_assessments.performance_score) as avg_performance')
                )
                ->groupBy('tutors.id', 'tutors.first_name', 'tutors.last_name', 'tutors.email', 'tutors.status')
                ->chunk(100, function($tutors) use ($file) {
                    foreach ($tutors as $tutor) {
                        fputcsv($file, [
                            $tutor->id,
                            $tutor->first_name . ' ' . $tutor->last_name,
                            $tutor->email,
                            $tutor->status,
                            $tutor->reports_count ?? 0,
                            $tutor->avg_attendance ? number_format($tutor->avg_attendance, 2) : 'N/A',
                            $tutor->assessments_count ?? 0,
                            $tutor->avg_performance ? number_format($tutor->avg_performance, 2) : 'N/A',
                        ]);
                    }
                });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
