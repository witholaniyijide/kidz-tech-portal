<?php

namespace App\Http\Controllers\Director;

use App\Http\Controllers\Controller;
use App\Models\TutorReport;
use App\Models\Tutor;
use App\Models\Student;
use App\Models\TutorReportComment;
use App\Services\DirectorApprovalService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class DirectorReportController extends Controller
{
    protected DirectorApprovalService $approvalService;

    /**
     * Create a new controller instance.
     */
    public function __construct(DirectorApprovalService $approvalService)
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->hasRole('director') && !Auth::user()->hasRole('admin')) {
                abort(403, 'Unauthorized access.');
            }
            return $next($request);
        });

        $this->approvalService = $approvalService;
    }

    /**
     * Display a listing of reports pending director approval.
     */
    public function index(Request $request)
    {
        // Authorize
        $this->authorize('viewAny', TutorReport::class);

        // Determine which status to filter by
        $statusFilter = $request->get('status', 'pending');

        $query = TutorReport::with(['student', 'tutor']);

        if ($statusFilter === 'approved') {
            // Show approved reports
            $query->where('status', 'approved-by-director')
                ->orderBy('approved_by_director_at', 'desc');
        } else {
            // Show pending reports (default)
            $query->where('status', 'approved-by-manager')
                ->orderBy('approved_by_manager_at', 'desc');
        }

        // Filter by tutor
        if ($request->filled('tutor_id')) {
            $query->where('tutor_id', $request->tutor_id);
        }

        // Filter by month
        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }

        // Filter by year
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        // Filter by student
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        // Filter by student name (search)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        $reports = $query->paginate(15);

        // Get counts for tabs
        $pendingCount = TutorReport::where('status', 'approved-by-manager')->count();
        $approvedCount = TutorReport::where('status', 'approved-by-director')->count();

        // Get statistics for enhanced stats cards
        $stats = [
            'total' => TutorReport::count(),
            'pending' => TutorReport::where('status', 'approved-by-manager')->count(),
            'approved' => TutorReport::where('status', 'approved-by-director')->count(),
            'manager_pending' => TutorReport::where('status', 'submitted')->count(),
            'returned' => TutorReport::where('status', 'returned')->count(),
        ];

        // Get unique months from reports for filter
        $months = TutorReport::select('month')
            ->distinct()
            ->orderBy('month', 'desc')
            ->pluck('month');

        // Get unique years from reports for filter
        $years = TutorReport::select('year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        // Get all tutors for filter
        $tutors = Tutor::where('status', 'active')
            ->orderBy('first_name')
            ->get();

        // Get all students for filter
        $students = Student::where('status', 'active')
            ->orderBy('first_name')
            ->get();

        // Analytics data - Monthly breakdown with totals
        $monthlyAnalytics = TutorReport::select(
                'month',
                'year',
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN status = 'submitted' THEN 1 ELSE 0 END) as pending"),
                DB::raw("SUM(CASE WHEN status = 'approved-by-manager' THEN 1 ELSE 0 END) as approved_by_manager"),
                DB::raw("SUM(CASE WHEN status = 'approved-by-director' THEN 1 ELSE 0 END) as completed"),
                DB::raw("SUM(CASE WHEN status = 'draft' THEN 1 ELSE 0 END) as draft"),
                DB::raw("SUM(CASE WHEN status = 'returned' THEN 1 ELSE 0 END) as returned")
            )
            ->groupBy('month', 'year')
            ->orderBy('year', 'desc')
            ->orderByRaw("FIELD(month, 'December', 'November', 'October', 'September', 'August', 'July', 'June', 'May', 'April', 'March', 'February', 'January')")
            ->get();

        // Get filter month/year for student overview
        $overviewMonth = $request->get('overview_month');
        $overviewYear = $request->get('overview_year');

        // Student-Tutor report overview with late submission tracking
        $studentTutorReports = Student::where('status', 'active')
            ->with(['tutor:id,first_name,last_name'])
            ->withCount([
                'tutorReports as total_reports',
                'tutorReports as pending_reports' => function ($q) use ($overviewMonth, $overviewYear) {
                    $q->whereIn('status', ['submitted', 'approved-by-manager']);
                    if ($overviewMonth) {
                        $q->where('month', $overviewMonth);
                    }
                    if ($overviewYear) {
                        $q->where('year', $overviewYear);
                    }
                },
                'tutorReports as approved_reports' => function ($q) use ($overviewMonth, $overviewYear) {
                    $q->where('status', 'approved-by-director');
                    if ($overviewMonth) {
                        $q->where('month', $overviewMonth);
                    }
                    if ($overviewYear) {
                        $q->where('year', $overviewYear);
                    }
                },
                'tutorReports as manager_approved_reports' => function ($q) use ($overviewMonth, $overviewYear) {
                    $q->where('status', 'approved-by-manager');
                    if ($overviewMonth) {
                        $q->where('month', $overviewMonth);
                    }
                    if ($overviewYear) {
                        $q->where('year', $overviewYear);
                    }
                },
            ])
            ->orderBy('first_name')
            ->get()
            ->map(function ($student) use ($overviewMonth, $overviewYear) {
                // Get latest report for this student (optionally filtered by month/year)
                $latestReportQuery = TutorReport::where('student_id', $student->id);
                if ($overviewMonth) {
                    $latestReportQuery->where('month', $overviewMonth);
                }
                if ($overviewYear) {
                    $latestReportQuery->where('year', $overviewYear);
                }
                $latestReport = $latestReportQuery->orderBy('submitted_at', 'desc')->first();

                $student->latest_report = $latestReport;
                $student->latest_submitted_at = $latestReport?->submitted_at;
                $student->latest_status = $latestReport?->status;
                $student->latest_month = $latestReport ? ($latestReport->month . ' ' . $latestReport->year) : null;

                // Check if latest report is late
                $student->is_late_submission = false;
                if ($latestReport && $latestReport->submitted_at) {
                    $reportMonth = $latestReport->month;
                    $reportYear = $latestReport->year;
                    $monthNumber = date('n', strtotime("1 {$reportMonth} {$reportYear}"));
                    $lastDayOfMonth = \Carbon\Carbon::create($reportYear, $monthNumber, 1)->endOfMonth();
                    $deadline = $lastDayOfMonth->copy()->setTime(12, 0, 0);
                    $student->is_late_submission = $latestReport->submitted_at->gt($deadline);
                }

                return $student;
            });

        // Count late submissions
        $lateSubmissionsCount = TutorReport::whereNotNull('submitted_at')
            ->get()
            ->filter(function ($report) {
                if (!$report->submitted_at || !$report->month || !$report->year) {
                    return false;
                }
                $monthNumber = date('n', strtotime("1 {$report->month} {$report->year}"));
                $lastDayOfMonth = \Carbon\Carbon::create($report->year, $monthNumber, 1)->endOfMonth();
                $deadline = $lastDayOfMonth->copy()->setTime(12, 0, 0);
                return $report->submitted_at->gt($deadline);
            })
            ->count();

        // Analytics filter - defaults to current month
        $analyticsMonth = $request->get('analytics_month', now()->format('F'));
        $analyticsYear = $request->get('analytics_year', now()->format('Y'));
        $currentMonth = now()->format('F');
        $currentYear = now()->format('Y');

        // Students awaiting reports (no report submitted for selected month)
        $studentsAwaitingReports = Student::where('status', 'active')
            ->whereDoesntHave('tutorReports', function ($q) use ($analyticsMonth, $analyticsYear) {
                $q->where('month', $analyticsMonth)
                  ->where('year', $analyticsYear)
                  ->whereIn('status', ['submitted', 'approved-by-manager', 'approved-by-director']);
            })
            ->with('tutor:id,first_name,last_name')
            ->get();

        // Late submissions for selected month
        $lateSubmissionsForMonth = TutorReport::whereNotNull('submitted_at')
            ->where('month', $analyticsMonth)
            ->where('year', $analyticsYear)
            ->with(['student', 'tutor'])
            ->get()
            ->filter(function ($report) {
                if (!$report->submitted_at || !$report->month || !$report->year) {
                    return false;
                }
                $monthNumber = date('n', strtotime("1 {$report->month} {$report->year}"));
                $lastDayOfMonth = \Carbon\Carbon::create($report->year, $monthNumber, 1)->endOfMonth();
                $deadline = $lastDayOfMonth->copy()->setTime(12, 0, 0);
                return $report->submitted_at->gt($deadline);
            });

        // Completed (Director approved) reports for selected month
        $completedReportsForMonth = TutorReport::where('status', 'approved-by-director')
            ->where('month', $analyticsMonth)
            ->where('year', $analyticsYear)
            ->with(['student', 'tutor'])
            ->orderBy('approved_by_director_at', 'desc')
            ->get();

        // Manager approved reports for selected month
        $managerApprovedForMonth = TutorReport::where('status', 'approved-by-manager')
            ->where('month', $analyticsMonth)
            ->where('year', $analyticsYear)
            ->with(['student', 'tutor'])
            ->orderBy('approved_by_manager_at', 'desc')
            ->get();

        // Add late submissions count to stats
        $stats['late_submissions'] = $lateSubmissionsCount;
        $stats['awaiting_reports'] = $studentsAwaitingReports->count();
        $stats['late_submissions_month'] = $lateSubmissionsForMonth->count();
        $stats['completed_month'] = $completedReportsForMonth->count();
        $stats['manager_approved_month'] = $managerApprovedForMonth->count();

        return view('director.reports.index', compact(
            'reports',
            'months',
            'years',
            'tutors',
            'students',
            'statusFilter',
            'pendingCount',
            'approvedCount',
            'stats',
            'monthlyAnalytics',
            'studentTutorReports',
            'studentsAwaitingReports',
            'currentMonth',
            'currentYear',
            'analyticsMonth',
            'analyticsYear',
            'lateSubmissionsForMonth',
            'completedReportsForMonth',
            'managerApprovedForMonth',
            'overviewMonth',
            'overviewYear'
        ));
    }

    /**
     * Display the specified report for director review.
     */
    public function show(TutorReport $report)
    {
        // Authorize
        $this->authorize('view', $report);

        // Load relationships
        $report->load(['student', 'tutor', 'comments.user', 'audits.user']);

        return view('director.reports.show', compact('report'));
    }

    /**
     * Show form to edit the report before approval.
     */
    public function edit(TutorReport $report)
    {
        // Authorize
        $this->authorize('view', $report);

        // Only allow editing of reports pending director approval
        if ($report->status !== 'approved-by-manager') {
            return redirect()
                ->route('director.reports.show', $report)
                ->with('error', 'Only reports pending director approval can be edited.');
        }

        // Load relationships
        $report->load(['student', 'tutor']);

        return view('director.reports.edit', compact('report'));
    }

    /**
     * Update the report before approval.
     */
    public function update(Request $request, TutorReport $report)
    {
        // Authorize
        $this->authorize('view', $report);

        // Only allow editing of reports pending director approval
        if ($report->status !== 'approved-by-manager') {
            return redirect()
                ->route('director.reports.show', $report)
                ->with('error', 'Only reports pending director approval can be edited.');
        }

        // Validate the request
        $validated = $request->validate([
            'areas_for_improvement' => 'nullable|string|max:5000',
            'goals_next_month' => 'nullable|string|max:5000',
            'assignments' => 'nullable|string|max:5000',
            'comments_observation' => 'nullable|string|max:5000',
            'attendance_score' => 'nullable|integer|min:0|max:100',
            'performance_rating' => 'nullable|string|in:excellent,good,satisfactory,needs-improvement',
        ]);

        // Update the report
        $report->update($validated);

        // Log the action
        $this->approvalService->logDirectorAction(
            Auth::user(),
            'edited_report',
            TutorReport::class,
            $report->id
        );

        return redirect()
            ->route('director.reports.show', $report)
            ->with('success', 'Report has been updated successfully. You can now approve it.');
    }

    /**
     * Approve the report (final approval).
     */
    public function approve(Request $request, TutorReport $report)
    {
        // Authorize
        $this->authorize('approve', $report);

        // Validate the request
        $validated = $request->validate([
            'director_comment' => 'nullable|string|max:2000',
            'director_signature' => 'nullable|string|max:500',
        ]);

        // Check if report can be approved
        if (!$report->canDirectorApprove()) {
            return redirect()
                ->route('director.reports.index')
                ->with('error', 'This report cannot be approved at this time.');
        }

        try {
            // Use the service to approve the report
            $this->approvalService->approveTutorReport(
                $report,
                Auth::user(),
                $validated['director_comment'] ?? null,
                $validated['director_signature'] ?? null
            );

            return redirect()
                ->route('director.reports.index')
                ->with('success', 'Report has been approved successfully. Notifications sent to tutor and manager.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to approve report: ' . $e->getMessage());
        }
    }

    /**
     * Reject the report.
     */
    public function reject(Request $request, TutorReport $report)
    {
        // Authorize
        $this->authorize('approve', $report);

        // Validate the request
        $validated = $request->validate([
            'director_comment' => 'required|string|max:2000',
        ]);

        try {
            // Update report status to draft with returned_at timestamp
            // This allows the tutor to edit while tracking that it was returned
            $report->update([
                'status' => 'draft',
                'director_comment' => $validated['director_comment'],
                'director_reviewed_at' => now(),
                'director_id' => Auth::id(),
                'returned_at' => now(),
                'returned_by' => 'director',
            ]);

            // Log the action
            $this->approvalService->logDirectorAction(
                Auth::user(),
                'rejected_report',
                TutorReport::class,
                $report->id
            );

            // Notify tutor and manager
            app(NotificationService::class)->notifyReportRejectedByDirector(
                $report,
                $validated['director_comment']
            );

            return redirect()
                ->route('director.reports.index')
                ->with('success', 'Report has been returned to the tutor for revision. Tutor and manager have been notified.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to reject report: ' . $e->getMessage());
        }
    }

    /**
     * Add a comment to the report.
     */
    public function comment(Request $request, TutorReport $report)
    {
        // Authorize
        $this->authorize('addComment', $report);

        // Validate the request
        $validated = $request->validate([
            'comment' => 'required|string|max:2000',
        ]);

        // Create the comment
        TutorReportComment::create([
            'report_id' => $report->id,
            'user_id' => Auth::id(),
            'comment' => $validated['comment'],
        ]);

        // Log the action
        $this->approvalService->logDirectorAction(
            Auth::user(),
            'commented_on_report',
            TutorReport::class,
            $report->id
        );

        return redirect()
            ->back()
            ->with('success', 'Comment added successfully.');
    }

    /**
     * Export report as PDF.
     */
    public function exportPdf(TutorReport $report)
    {
        // Authorize
        $this->authorize('export', $report);

        // Load relationships
        $report->load(['student', 'tutor', 'director']);

        // Generate PDF
        $pdf = Pdf::loadView('tutor.reports.pdf', compact('report'));

        // Generate filename
        $filename = 'report_' . $report->student->first_name . '_' . $report->student->last_name . '_' . $report->month . '.pdf';

        // Log the action
        $this->approvalService->logDirectorAction(
            Auth::user(),
            'exported_report',
            TutorReport::class,
            $report->id
        );

        // Return PDF download
        return $pdf->download($filename);
    }

    /**
     * Display printable view of report.
     */
    public function print(TutorReport $report)
    {
        // Authorize
        $this->authorize('print', $report);

        // Load relationships
        $report->load(['student', 'tutor', 'director']);

        // Log the action
        $this->approvalService->logDirectorAction(
            Auth::user(),
            'printed_report',
            TutorReport::class,
            $report->id
        );

        return view('tutor.reports.print', compact('report'));
    }

    /**
     * Export report as JSON for WhatsApp.
     */
    public function exportWhatsApp(TutorReport $report)
    {
        // Authorize
        $this->authorize('export', $report);

        // Load relationships
        $report->load(['student', 'tutor']);

        $projectsList = collect($report->projects ?? [])
            ->filter(fn($p) => !empty($p['title']))
            ->map(fn($p, $i) => "Project " . ($i + 1) . ": " . $p['title'] . ($p['link'] ? " – " . $p['link'] : ''))
            ->implode("\n");

        $text = "*Kidz Tech Coding Club: Monthly Progress Report*\n\n"
            . "*Student:* " . $report->student->first_name . " " . $report->student->last_name . "\n"
            . "*Month:* " . $report->month . " " . $report->year . "\n"
            . "*Instructor:* " . $report->tutor->first_name . " " . $report->tutor->last_name . "\n"
            . "*Course(s):* " . implode(', ', $report->courses ?? []) . "\n\n"
            . "*1. Progress Overview:*\n"
            . "*Skills Mastered:* " . implode(', ', $report->skills_mastered ?? []) . "\n"
            . "*New Skills:* " . (count($report->new_skills ?? []) > 0 ? implode(', ', $report->new_skills) : 'N/A') . "\n\n"
            . "*2. Projects/Activities Completed:*\n" . $projectsList . "\n\n"
            . "*3. Areas for Improvement:*\n" . ($report->areas_for_improvement ?? 'N/A') . "\n\n"
            . "*4. Goals for Next Month:*\n" . ($report->goals_next_month ?? 'N/A') . "\n\n"
            . "*5. Assignment/Projects during the month:*\n" . ($report->assignments ?? 'N/A') . "\n\n"
            . "*6. Comments/Observation:*\n" . ($report->comments_observation ?? 'N/A');

        // Log the action
        $this->approvalService->logDirectorAction(
            Auth::user(),
            'exported_whatsapp',
            TutorReport::class,
            $report->id
        );

        return response()->json(['success' => true, 'text' => $text]);
    }

    /**
     * Bulk approve multiple reports pending director approval.
     */
    public function bulkApprove(Request $request)
    {
        $validated = $request->validate([
            'report_ids' => 'required|array|min:1',
            'report_ids.*' => 'exists:tutor_reports,id',
        ]);

        $reports = TutorReport::whereIn('id', $validated['report_ids'])
            ->where('status', 'approved-by-manager')
            ->with(['student', 'tutor'])
            ->get();

        if ($reports->isEmpty()) {
            return redirect()->back()->with('error', 'No eligible reports found to approve.');
        }

        $approvedCount = 0;

        foreach ($reports as $report) {
            try {
                $this->approvalService->approveTutorReport(
                    $report,
                    Auth::user(),
                    null,
                    null
                );
                $approvedCount++;
            } catch (\Exception $e) {
                \Log::error("Failed to bulk approve report {$report->id}: " . $e->getMessage());
            }
        }

        return redirect()
            ->route('director.reports.index')
            ->with('success', "{$approvedCount} report(s) approved successfully.");
    }
}
