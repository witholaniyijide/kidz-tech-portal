<?php

namespace App\Http\Controllers\Director;

use App\Http\Controllers\Controller;
use App\Models\TutorReport;
use App\Models\Tutor;
use App\Models\Student;
use App\Models\AuditLog;
use App\Models\TutorNotification;
use App\Models\ManagerNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReportApprovalController extends Controller
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
     * Display a listing of reports approved by manager, pending director review.
     */
    public function index(Request $request)
    {
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
        // Check authorization
        $this->authorize('view', $report);

        // Load relationships
        $report->load(['student', 'tutor', 'comments.user', 'audits.user']);

        return view('director.reports.show', compact('report'));
    }

    /**
     * Approve the report and move it to director-approved status (final approval).
     */
    public function approve(Request $request, TutorReport $report)
    {
        // Check authorization
        $this->authorize('approve', $report);

        // Validate the request
        $validated = $request->validate([
            'director_comment' => 'nullable|string|max:2000',
        ]);

        // Check if report is in manager-approved status
        if ($report->status !== 'approved-by-manager') {
            return redirect()
                ->route('director.reports.index')
                ->with('error', 'This report cannot be approved at this time.');
        }

        // Check for idempotency - don't approve already approved reports
        if ($report->status === 'approved-by-director') {
            return redirect()
                ->route('director.reports.index')
                ->with('info', 'This report has already been approved.');
        }

        // Update the report within a transaction
        DB::transaction(function () use ($report, $validated, $request) {
            $previousStatus = $report->status;

            // Update report
            $report->update([
                'status' => 'approved-by-director',
                'director_comment' => $validated['director_comment'] ?? null,
                'approved_by_director_at' => now(),
            ]);

            // Create audit log
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'report.approve.director',
                'auditable_type' => TutorReport::class,
                'auditable_id' => $report->id,
                'meta' => [
                    'manager_comment' => $report->manager_comment,
                    'director_comment' => $validated['director_comment'] ?? null,
                    'previous_status' => $previousStatus,
                    'approved_at' => now()->toDateTimeString(),
                ],
            ]);

            // Notify the tutor
            TutorNotification::create([
                'tutor_id' => $report->tutor_id,
                'title' => 'Report Approved - Final Approval',
                'body' => "Your report for {$report->student->first_name} {$report->student->last_name} ({$report->month}) has been given final approval by the director.",
                'type' => 'system',
                'meta' => [
                    'report_id' => $report->id,
                    'action' => 'approved',
                    'link' => route('tutor.reports.show', $report->id),
                ],
            ]);

            // Notify the manager (find managers with the manager role)
            $managers = User::whereHas('roles', function ($query) {
                $query->where('name', 'manager');
            })->get();

            foreach ($managers as $manager) {
                ManagerNotification::create([
                    'user_id' => $manager->id,
                    'title' => 'Report Approved by Director',
                    'body' => "The report for {$report->student->first_name} {$report->student->last_name} ({$report->month}) by {$report->tutor->first_name} {$report->tutor->last_name} has been approved by the director.",
                    'type' => 'report',
                    'meta' => [
                        'report_id' => $report->id,
                        'action' => 'approved',
                        'link' => route('manager.tutor-reports.show', $report->id),
                    ],
                ]);
            }

            // Optional: Dispatch email notifications here
            // Mail::to($report->tutor->email)->send(new ReportApprovedByDirector($report));
        });

        return redirect()
            ->route('director.reports.index')
            ->with('success', 'Report has been approved successfully. Notifications sent to tutor and manager.');
    }

    /**
     * Reject the report with director comment.
     */
    public function reject(Request $request, TutorReport $report)
    {
        // Check authorization
        $this->authorize('sendBackForCorrection', $report);

        // Validate the request - comment is required when rejecting
        $validated = $request->validate([
            'director_comment' => 'required|string|max:2000',
        ], [
            'director_comment.required' => 'Please provide a comment explaining why this report is being rejected.',
        ]);

        // Check if report is in manager-approved status
        if ($report->status !== 'approved-by-manager') {
            return redirect()
                ->route('director.reports.index')
                ->with('error', 'This report cannot be rejected at this time.');
        }

        // Update the report within a transaction
        DB::transaction(function () use ($report, $validated, $request) {
            $previousStatus = $report->status;

            // Update report
            $report->update([
                'status' => 'rejected',
                'director_comment' => $validated['director_comment'],
            ]);

            // Create audit log
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'report.reject.director',
                'auditable_type' => TutorReport::class,
                'auditable_id' => $report->id,
                'meta' => [
                    'manager_comment' => $report->manager_comment,
                    'director_comment' => $validated['director_comment'],
                    'previous_status' => $previousStatus,
                    'rejected_at' => now()->toDateTimeString(),
                ],
            ]);

            // Notify the tutor
            TutorNotification::create([
                'tutor_id' => $report->tutor_id,
                'title' => 'Report Rejected by Director',
                'body' => "Your report for {$report->student->first_name} {$report->student->last_name} ({$report->month}) has been rejected by the director. Reason: {$validated['director_comment']}",
                'type' => 'alert',
                'meta' => [
                    'report_id' => $report->id,
                    'action' => 'rejected',
                    'link' => route('tutor.reports.show', $report->id),
                    'director_comment' => $validated['director_comment'],
                ],
            ]);

            // Notify the manager
            $managers = User::whereHas('roles', function ($query) {
                $query->where('name', 'manager');
            })->get();

            foreach ($managers as $manager) {
                ManagerNotification::create([
                    'user_id' => $manager->id,
                    'title' => 'Report Rejected by Director',
                    'body' => "The report for {$report->student->first_name} {$report->student->last_name} ({$report->month}) by {$report->tutor->first_name} {$report->tutor->last_name} has been rejected by the director.",
                    'type' => 'report',
                    'meta' => [
                        'report_id' => $report->id,
                        'action' => 'rejected',
                        'link' => route('manager.tutor-reports.show', $report->id),
                        'director_comment' => $validated['director_comment'],
                    ],
                ]);
            }

            // Optional: Dispatch email notifications here
            // Mail::to($report->tutor->email)->send(new ReportRejectedByDirector($report));
        });

        return redirect()
            ->route('director.reports.index')
            ->with('success', 'Report has been rejected. Notifications sent to tutor and manager.');
    }

    /**
     * Export report as PDF.
     */
    public function export(TutorReport $report)
    {
        // Load relationships
        $report->load(['student', 'tutor', 'director']);

        // Generate PDF using DomPDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('tutor.reports.pdf', compact('report'));

        // Generate filename
        $studentName = $report->student ? $report->student->first_name . '_' . $report->student->last_name : 'unknown';
        $filename = 'report_' . $studentName . '_' . $report->month . '.pdf';

        // Return PDF download
        return $pdf->download($filename);
    }
}
