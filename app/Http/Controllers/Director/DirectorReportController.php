<?php

namespace App\Http\Controllers\Director;

use App\Http\Controllers\Controller;
use App\Models\TutorReport;
use App\Models\Tutor;
use App\Models\Student;
use App\Models\TutorReportComment;
use App\Services\DirectorApprovalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $query = TutorReport::with(['student', 'tutor'])
            ->where('status', 'approved-by-manager')
            ->orderBy('approved_by_manager_at', 'desc');

        // Filter by tutor
        if ($request->filled('tutor_id')) {
            $query->where('tutor_id', $request->tutor_id);
        }

        // Filter by month
        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }

        // Filter by student
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        $reports = $query->paginate(15);

        // Get unique months from reports for filter
        $months = TutorReport::select('month')
            ->distinct()
            ->orderBy('month', 'desc')
            ->pluck('month');

        // Get all tutors for filter
        $tutors = Tutor::where('status', 'active')
            ->orderBy('first_name')
            ->get();

        // Get all students for filter
        $students = Student::where('status', 'active')
            ->orderBy('first_name')
            ->get();

        return view('director.reports.index', compact(
            'reports',
            'months',
            'tutors',
            'students'
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
}
