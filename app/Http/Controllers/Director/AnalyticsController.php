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
        // Clear cache for fresh data
        Cache::forget('director.analytics.dashboard.stats');

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
                'pending_assessments' => TutorAssessment::whereIn('status', ['submitted', 'pending_review', 'approved-by-manager'])->count(),

                'activity_today' => DirectorActivityLog::whereDate('created_at', today())->count(),
            ];
        });
    }

    /**
     * Get enrollments data for chart (JSON).
     */
    public function getEnrollmentsData(Request $request)
    {
        $year = $request->input('year', now()->year);

        // Clear cache for fresh data
        Cache::forget("director.analytics.enrollments.{$year}");

        $data = Cache::remember("director.analytics.enrollments.{$year}", 300, function () use ($year) {
            // Get enrollment data for the selected year
            $enrollments = DB::table('students')
                ->select(
                    DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                    DB::raw('COUNT(*) as new_enrollments'),
                    DB::raw("SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active"),
                    DB::raw("SUM(CASE WHEN status = 'inactive' THEN 1 ELSE 0 END) as inactive")
                )
                ->whereYear('created_at', $year)
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
    public function getReportsData(Request $request)
    {
        try {
            $year = $request->input('year', now()->year);

            // Clear cache for fresh data
            Cache::forget("director.analytics.reports.{$year}");

            $data = Cache::remember("director.analytics.reports.{$year}", 300, function () use ($year) {
            // Monthly report submissions for selected year
            $monthlyReports = DB::table('tutor_reports')
                ->select(
                    DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                    DB::raw('COUNT(*) as total')
                )
                ->whereYear('created_at', $year)
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->get();

            // If no monthly data, fill with zeros for all months of the year
            if ($monthlyReports->isEmpty()) {
                $monthlyReports = collect();
                for ($i = 1; $i <= 12; $i++) {
                    $month = $year . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
                    $monthlyReports->push((object)['month' => $month, 'total' => 0]);
                }
            }

            // Status breakdown for the selected year
            $statusBreakdown = TutorReport::select('status', DB::raw('COUNT(*) as count'))
                ->whereYear('created_at', $year)
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status')
                ->toArray();

            // If no reports, use placeholder
            if (empty($statusBreakdown)) {
                $statusBreakdown = [
                    'draft' => 0,
                    'submitted' => 0,
                    'approved-by-manager' => 0,
                    'approved-by-director' => 0
                ];
            }

            // Map status names to readable labels and colors
            $statusLabels = [];
            $statusData = [];
            $statusColors = [
                'draft' => 'rgba(156, 163, 175, 0.8)',
                'submitted' => 'rgba(99, 102, 241, 0.8)',
                'approved-by-manager' => 'rgba(251, 191, 36, 0.8)',
                'approved-by-director' => 'rgba(34, 197, 94, 0.8)',
            ];
            $colorValues = [];

            foreach ($statusBreakdown as $status => $count) {
                $statusLabels[] = ucwords(str_replace('-', ' ', $status));
                $statusData[] = $count;
                $colorValues[] = $statusColors[$status] ?? 'rgba(107, 114, 128, 0.8)';
            }

            // Top tutors by reports - all time if current month is empty
            $topTutors = DB::table('tutor_reports')
                ->join('tutors', 'tutor_reports.tutor_id', '=', 'tutors.id')
                ->select(
                    'tutors.first_name',
                    'tutors.last_name',
                    DB::raw('COUNT(*) as report_count'),
                    DB::raw('AVG(tutor_reports.attendance_score) as avg_attendance')
                )
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
                            'fill' => true,
                            'tension' => 0.4,
                        ]
                    ]
                ],
                'status' => [
                    'labels' => $statusLabels,
                    'datasets' => [
                        [
                            'label' => 'Reports by Status',
                            'data' => $statusData,
                            'backgroundColor' => $colorValues,
                        ]
                    ]
                ],
                'top_tutors' => $topTutors->toArray()
            ];
        });

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'monthly' => ['labels' => [], 'datasets' => []],
                'status' => ['labels' => [], 'datasets' => []],
                'top_tutors' => [],
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get tutor performance data (JSON).
     */
    public function getTutorPerformanceData(Request $request)
    {
        $year = $request->input('year', now()->year);

        // Clear cache for fresh data
        Cache::forget("director.analytics.tutor_performance.{$year}");

        $data = Cache::remember("director.analytics.tutor_performance.{$year}", 300, function () use ($year) {
            // Students per tutor - use actual student.tutor_id relationship
            $studentsPerTutor = DB::table('tutors')
                ->leftJoin('students', 'tutors.id', '=', 'students.tutor_id')
                ->select(
                    'tutors.first_name',
                    'tutors.last_name',
                    DB::raw('COUNT(DISTINCT students.id) as student_count')
                )
                ->where('tutors.status', 'active')
                ->where(function($query) {
                    $query->where('students.status', 'active')
                          ->orWhereNull('students.id');
                })
                ->groupBy('tutors.id', 'tutors.first_name', 'tutors.last_name')
                ->orderBy('student_count', 'desc')
                ->limit(20)
                ->get();

            // Average attendance by tutor - use actual attendance_records table
            // Calculate attendance rate: approved classes / total classes submitted
            $attendanceByTutor = DB::table('tutors')
                ->leftJoin('attendance_records', function($join) use ($year) {
                    $join->on('tutors.id', '=', 'attendance_records.tutor_id')
                         ->whereYear('attendance_records.class_date', $year);
                })
                ->select(
                    'tutors.first_name',
                    'tutors.last_name',
                    'tutors.id',
                    DB::raw('COUNT(attendance_records.id) as total_classes'),
                    DB::raw('SUM(CASE WHEN attendance_records.status = "approved" THEN 1 ELSE 0 END) as approved_classes')
                )
                ->where('tutors.status', 'active')
                ->groupBy('tutors.id', 'tutors.first_name', 'tutors.last_name')
                ->having('total_classes', '>', 0)
                ->orderBy('total_classes', 'desc')
                ->limit(20)
                ->get()
                ->map(function($tutor) {
                    $tutor->avg_attendance = $tutor->total_classes > 0
                        ? round(($tutor->approved_classes / $tutor->total_classes) * 100, 1)
                        : 0;
                    return $tutor;
                })
                ->sortByDesc('avg_attendance')
                ->values();

            // Tutors with low attendance (<70%)
            $lowAttendanceTutors = $attendanceByTutor->filter(function ($tutor) {
                return $tutor->avg_attendance < 70;
            })->values();

            return [
                'students_per_tutor' => [
                    'labels' => $studentsPerTutor->map(fn($t) => $t->first_name . ' ' . $t->last_name)->toArray(),
                    'datasets' => [
                        [
                            'label' => 'Students Assigned',
                            'data' => $studentsPerTutor->pluck('student_count')->toArray(),
                            'backgroundColor' => 'rgba(124, 58, 237, 0.7)',
                        ]
                    ]
                ],
                'attendance' => [
                    'labels' => $attendanceByTutor->map(fn($t) => $t->first_name . ' ' . $t->last_name)->toArray(),
                    'datasets' => [
                        [
                            'label' => 'Approval Rate %',
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
     * Get course analytics data (JSON) - courses covered in attendance.
     */
    public function getCourseAnalyticsData(Request $request)
    {
        $year = $request->input('year', now()->year);

        // Clear cache for fresh data
        Cache::forget("director.analytics.courses.{$year}");

        $data = Cache::remember("director.analytics.courses.{$year}", 300, function () use ($year) {
            // Get all attendance records with courses_covered for the year
            $attendanceWithCourses = DB::table('attendance_records')
                ->whereYear('class_date', $year)
                ->where('status', 'approved')
                ->whereNotNull('courses_covered')
                ->select('courses_covered')
                ->get();

            // Parse and count courses
            $courseCounts = [];
            foreach ($attendanceWithCourses as $record) {
                $courses = json_decode($record->courses_covered, true);
                if (is_array($courses)) {
                    foreach ($courses as $course) {
                        $course = trim($course);
                        if (!empty($course)) {
                            $courseCounts[$course] = ($courseCounts[$course] ?? 0) + 1;
                        }
                    }
                }
            }

            // Sort by count descending
            arsort($courseCounts);

            // Get top 10 courses
            $topCourses = array_slice($courseCounts, 0, 10, true);

            // Generate colors for each course
            $colors = [
                'rgba(79, 70, 229, 0.7)',
                'rgba(129, 140, 248, 0.7)',
                'rgba(124, 58, 237, 0.7)',
                'rgba(167, 139, 250, 0.7)',
                'rgba(99, 102, 241, 0.7)',
                'rgba(139, 92, 246, 0.7)',
                'rgba(67, 56, 202, 0.7)',
                'rgba(109, 40, 217, 0.7)',
                'rgba(55, 48, 163, 0.7)',
                'rgba(91, 33, 182, 0.7)',
            ];

            return [
                'courses' => [
                    'labels' => array_keys($topCourses),
                    'datasets' => [
                        [
                            'label' => 'Classes Taught',
                            'data' => array_values($topCourses),
                            'backgroundColor' => array_slice($colors, 0, count($topCourses)),
                        ]
                    ]
                ],
                'total_classes' => array_sum($courseCounts),
                'unique_courses' => count($courseCounts),
            ];
        });

        return response()->json($data);
    }

    /**
     * Get assessment metrics data (JSON).
     */
    public function getAssessmentData(Request $request)
    {
        $year = $request->input('year', now()->year);

        // Clear cache for fresh data
        Cache::forget("director.analytics.assessments.{$year}");

        $data = Cache::remember("director.analytics.assessments.{$year}", 300, function () use ($year) {
            // Average performance score by month for selected year - using class_date for proper date ordering
            $monthlyPerformance = DB::table('tutor_assessments')
                ->select(
                    DB::raw("DATE_FORMAT(class_date, '%Y-%m') as month"),
                    DB::raw('AVG(performance_score) as avg_score'),
                    DB::raw('COUNT(*) as count')
                )
                ->whereYear('class_date', $year)
                ->where('status', 'approved-by-director')
                ->whereNotNull('performance_score')
                ->where('performance_score', '>', 0)
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->get();

            // If no data from class_date, try using year field
            if ($monthlyPerformance->isEmpty()) {
                $monthlyPerformance = DB::table('tutor_assessments')
                    ->select(
                        DB::raw("CONCAT(year, '-', LPAD(week, 2, '0')) as month"),
                        DB::raw('AVG(performance_score) as avg_score'),
                        DB::raw('COUNT(*) as count')
                    )
                    ->where('year', $year)
                    ->where('status', 'approved-by-director')
                    ->whereNotNull('performance_score')
                    ->where('performance_score', '>', 0)
                    ->groupBy('year', 'week')
                    ->orderBy('year', 'asc')
                    ->orderBy('week', 'asc')
                    ->limit(12)
                    ->get();
            }

            // Distribution of performance scores for selected year (grouped by ranges)
            $ratingDistribution = TutorAssessment::select(
                    DB::raw('CASE
                        WHEN performance_score >= 90 THEN "90-100 (Excellent)"
                        WHEN performance_score >= 70 THEN "70-89 (Good)"
                        WHEN performance_score >= 50 THEN "50-69 (Fair)"
                        ELSE "0-49 (Needs Improvement)"
                    END as rating_range'),
                    DB::raw('COUNT(*) as count')
                )
                ->whereYear('created_at', $year)
                ->where('status', 'approved-by-director')
                ->whereNotNull('performance_score')
                ->where('performance_score', '>', 0)
                ->groupBy('rating_range')
                ->orderByRaw('MIN(performance_score) DESC')
                ->get();

            // Criteria breakdown - average scores by criteria (filtered by year for consistency)
            $criteriaBreakdown = TutorAssessment::with('ratings.criteria')
                ->where('status', 'approved-by-director')
                ->where(function($query) use ($year) {
                    $query->whereYear('class_date', $year)
                          ->orWhere('year', $year);
                })
                ->get()
                ->flatMap(function($assessment) {
                    return $assessment->ratings ?? collect();
                })
                ->groupBy(function($rating) {
                    return $rating->criteria?->name ?? 'Unknown';
                })
                ->map(function($ratings, $name) {
                    $ratingValues = $ratings->map(function($r) {
                        // Convert rating text to numeric value
                        return match(strtolower($r->rating ?? '')) {
                            'excellent', 'on time' => 5,
                            'good' => 4,
                            'acceptable' => 3,
                            'needs improvement', 'late' => 2,
                            'unacceptable' => 1,
                            default => 3
                        };
                    });
                    return [
                        'name' => $name,
                        'average' => round($ratingValues->avg() * 20, 1), // Convert to percentage
                        'count' => $ratings->count()
                    ];
                })
                ->values();

            return [
                'monthly_performance' => [
                    'labels' => $monthlyPerformance->pluck('month')->toArray(),
                    'datasets' => [
                        [
                            'label' => 'Average Performance Score',
                            'data' => $monthlyPerformance->pluck('avg_score')->map(fn($v) => round($v, 1))->toArray(),
                            'borderColor' => 'rgb(124, 58, 237)',
                            'backgroundColor' => 'rgba(124, 58, 237, 0.1)',
                            'tension' => 0.4,
                            'fill' => true,
                        ]
                    ]
                ],
                'rating_distribution' => [
                    'labels' => $ratingDistribution->pluck('rating_range')->toArray(),
                    'datasets' => [
                        [
                            'label' => 'Performance Score Distribution',
                            'data' => $ratingDistribution->pluck('count')->toArray(),
                            'backgroundColor' => [
                                'rgba(34, 197, 94, 0.7)',   // Excellent - green
                                'rgba(59, 130, 246, 0.7)', // Good - blue
                                'rgba(251, 191, 36, 0.7)', // Fair - yellow
                                'rgba(239, 68, 68, 0.7)',  // Needs Improvement - red
                            ]
                        ]
                    ]
                ],
                'criteria_breakdown' => [
                    'labels' => $criteriaBreakdown->pluck('name')->toArray(),
                    'datasets' => [
                        [
                            'label' => 'Average Score by Criteria',
                            'data' => $criteriaBreakdown->pluck('average')->toArray(),
                            'backgroundColor' => 'rgba(79, 70, 229, 0.7)',
                        ]
                    ]
                ]
            ];
        });

        return response()->json($data);
    }

    /**
     * Get student learning tracker data (courses/topics taught over time).
     */
    public function getStudentLearningData(Request $request)
    {
        try {
            $studentId = $request->input('student_id');
            $dateFrom = $request->input('date_from', now()->subMonths(3)->format('Y-m-d'));
            $dateTo = $request->input('date_to', now()->format('Y-m-d'));

            if (!$studentId) {
                // Return list of students for dropdown
                $students = Student::where('status', 'active')
                    ->orderBy('first_name')
                    ->get(['id', 'first_name', 'last_name', 'student_id']);

                return response()->json([
                    'students' => $students->map(fn($s) => [
                        'id' => $s->id,
                        'name' => $s->first_name . ' ' . $s->last_name,
                        'student_id' => $s->student_id
                    ])
                ]);
            }

            // Get student info
            $student = Student::with(['tutor'])->find($studentId);
            if (!$student) {
                return response()->json(['error' => 'Student not found'], 404);
            }

            // Get attendance records with topics covered
            $attendanceRecords = \App\Models\AttendanceRecord::where('student_id', $studentId)
                ->whereBetween('class_date', [$dateFrom, $dateTo])
                ->where('status', 'approved')
                ->orderBy('class_date', 'desc')
                ->get();

            // Compile topics list from attendance
            $topicsList = collect();

            foreach ($attendanceRecords as $record) {
                if ($record->topic) {
                    $courses = $record->courses_covered;
                    $courseStr = null;
                    if (is_array($courses) && count($courses) > 0) {
                        $courseStr = implode(', ', $courses);
                    } elseif (is_string($courses) && !empty($courses)) {
                        $courseStr = $courses;
                    }

                    $topicsList->push([
                        'date' => $record->class_date ? $record->class_date->format('Y-m-d') : null,
                        'topic' => $record->topic,
                        'course' => $courseStr,
                        'notes' => $record->notes,
                        'type' => 'attendance',
                    ]);
                }
            }

            // Get unique topics count
            $uniqueTopics = $topicsList->pluck('topic')->filter()->unique()->count();

            // Get classes count
            $classesCount = $attendanceRecords->count();

            return response()->json([
                'student' => [
                    'id' => $student->id,
                    'name' => $student->first_name . ' ' . $student->last_name,
                    'student_id' => $student->student_id,
                    'tutor' => $student->tutor ? $student->tutor->first_name . ' ' . $student->tutor->last_name : 'Unassigned',
                    'current_course' => $student->current_level ? 'Level ' . $student->current_level : 'N/A',
                ],
                'summary' => [
                    'total_classes' => $classesCount,
                    'unique_topics' => $uniqueTopics,
                    'date_range' => $dateFrom . ' to ' . $dateTo,
                ],
                'topics' => $topicsList->sortByDesc('date')->values()->toArray(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error loading student data: ' . $e->getMessage(),
                'student' => null,
                'summary' => ['total_classes' => 0, 'unique_topics' => 0],
                'topics' => []
            ], 200); // Return 200 with error message so Alpine.js can display it
        }
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
