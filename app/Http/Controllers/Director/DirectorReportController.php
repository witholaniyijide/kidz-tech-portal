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

        $reports = $query->paginate(15);

        // Get counts for tabs
        $pendingCount = TutorReport::where('status', 'approved-by-manager')->count();
        $approvedCount = TutorReport::where('status', 'approved-by-director')->count();

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

        return view('director.reports.index', compact(
            'reports',
            'months',
            'years',
            'tutors',
            'students',
            'statusFilter',
            'pendingCount',
            'approvedCount'
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
            // Update report status
            $report->update([
                'status' => 'returned',
                'director_comment' => $validated['director_comment'],
                'director_reviewed_at' => now(),
                'director_id' => Auth::id(),
            ]);

            // Log the action
            $this->approvalService->logDirectorAction(
                Auth::user(),
                'rejected_report',
                TutorReport::class,
                $report->id
            );

            return redirect()
                ->route('director.reports.index')
                ->with('success', 'Report has been rejected and returned to the tutor for revision.');
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
}
