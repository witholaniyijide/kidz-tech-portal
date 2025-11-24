<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ManagerReportsController extends Controller
{
    /**
     * Display a listing of tutor reports.
     */
    public function index(Request $request)
    {
        $query = Report::with(['student', 'instructor'])
            ->whereIn('status', ['submitted', 'submitted_to_manager', 'approved_by_manager']);

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by month if provided
        if ($request->has('month') && $request->month) {
            $query->where('month', $request->month);
        }

        // Filter by year if provided
        if ($request->has('year') && $request->year) {
            $query->where('year', $request->year);
        }

        // Filter by student if provided
        if ($request->has('student_id') && $request->student_id) {
            $query->where('student_id', $request->student_id);
        }

        // Filter by instructor/tutor if provided
        if ($request->has('instructor_id') && $request->instructor_id) {
            $query->where('instructor_id', $request->instructor_id);
        }

        $reports = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get statistics for the view
        $stats = [
            'total' => Report::whereIn('status', ['submitted', 'submitted_to_manager', 'approved_by_manager'])->count(),
            'pending' => Report::whereIn('status', ['submitted', 'submitted_to_manager'])->count(),
            'approved' => Report::where('status', 'approved_by_manager')->count(),
        ];

        return view('manager.reports.index', compact('reports', 'stats'));
    }

    /**
     * Display the specified report.
     */
    public function show(Report $report)
    {
        // Load relationships
        $report->load(['student', 'instructor', 'approvedBy']);

        return view('manager.reports.show', compact('report'));
    }

    /**
     * Add manager comment/feedback to a report.
     */
    public function comment(Request $request, Report $report)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        // Add manager comment to the report
        $currentComments = $report->comments ?? '';
        $managerComment = "\n\n--- Manager Feedback (" . Carbon::now()->format('Y-m-d H:i') . ") ---\n" . $request->comment;

        $report->comments = $currentComments . $managerComment;
        $report->save();

        return redirect()
            ->route('manager.reports.show', $report)
            ->with('success', 'Your comment has been added to the report.');
    }

    /**
     * Approve a report (manager approval).
     */
    public function approve(Request $request, Report $report)
    {
        // Validate that the report is in a state that can be approved by manager
        if (!in_array($report->status, ['submitted', 'submitted_to_manager'])) {
            return redirect()
                ->route('manager.reports.show', $report)
                ->with('error', 'This report cannot be approved at this time.');
        }

        // Update report status to approved by manager
        $report->status = 'approved_by_manager';

        // Optionally add approval comment
        if ($request->has('approval_comment') && $request->approval_comment) {
            $currentComments = $report->comments ?? '';
            $approvalComment = "\n\n--- Manager Approval (" . Carbon::now()->format('Y-m-d H:i') . ") ---\n" . $request->approval_comment;
            $report->comments = $currentComments . $approvalComment;
        }

        $report->save();

        // Note: Director will give final approval next
        return redirect()
            ->route('manager.reports.show', $report)
            ->with('success', 'Report approved successfully. Awaiting Director final approval.');
    }

    /**
     * Request changes to a report (manager sends back for revision).
     */
    public function requestChanges(Request $request, Report $report)
    {
        $request->validate([
            'revision_notes' => 'required|string|max:1000',
        ]);

        // Update status to draft and add revision notes
        $report->status = 'draft';

        $currentComments = $report->comments ?? '';
        $revisionNote = "\n\n--- Manager Requested Changes (" . Carbon::now()->format('Y-m-d H:i') . ") ---\n" . $request->revision_notes;
        $report->comments = $currentComments . $revisionNote;
        $report->save();

        return redirect()
            ->route('manager.reports.index')
            ->with('success', 'Report sent back for revision. Tutor has been notified.');
    }
}
