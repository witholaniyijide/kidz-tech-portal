<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\TutorReport;
use App\Models\Tutor;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportReviewController extends Controller
{
    /**
     * Display a listing of submitted reports pending manager review.
     */
    public function index(Request $request)
    {
        $query = TutorReport::with(['student', 'tutor'])
            ->where('status', 'submitted')
            ->orderBy('submitted_at', 'desc');

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

        return view('manager.reports.index', compact(
            'reports',
            'months',
            'tutors',
            'students'
        ));
    }

    /**
     * Display the specified report for review.
     */
    public function show(TutorReport $report)
    {
        // Load relationships
        $report->load(['student', 'tutor', 'comments.user']);

        // Check if report is in submitted status
        if ($report->status !== 'submitted') {
            return redirect()
                ->route('manager.reports.index')
                ->with('warning', 'This report is not available for review.');
        }

        return view('manager.reports.show', compact('report'));
    }

    /**
     * Approve the report and move it to manager-approved status.
     */
    public function approve(Request $request, TutorReport $report)
    {
        // Validate the request
        $validated = $request->validate([
            'manager_comment' => 'nullable|string|max:2000',
        ]);

        // Check if report is in submitted status
        if ($report->status !== 'submitted') {
            return redirect()
                ->route('manager.reports.index')
                ->with('error', 'This report cannot be approved at this time.');
        }

        // Update the report
        DB::transaction(function () use ($report, $validated) {
            $report->update([
                'status' => 'approved-by-manager',
                'manager_comment' => $validated['manager_comment'] ?? null,
                'approved_by_manager_at' => now(),
            ]);
        });

        return redirect()
            ->route('manager.reports.index')
            ->with('success', 'Report has been approved successfully.');
    }

    /**
     * Send the report back to the tutor for corrections.
     */
    public function sendBackForCorrection(Request $request, TutorReport $report)
    {
        // Validate the request - comment is required when sending back
        $validated = $request->validate([
            'manager_comment' => 'required|string|max:2000',
        ], [
            'manager_comment.required' => 'Please provide feedback explaining what needs to be corrected.',
        ]);

        // Check if report is in submitted status
        if ($report->status !== 'submitted') {
            return redirect()
                ->route('manager.reports.index')
                ->with('error', 'This report cannot be sent back at this time.');
        }

        // Update the report
        DB::transaction(function () use ($report, $validated) {
            $report->update([
                'status' => 'draft',
                'manager_comment' => $validated['manager_comment'],
            ]);
        });

        return redirect()
            ->route('manager.reports.index')
            ->with('success', 'Report has been sent back to the tutor for corrections.');
    }
}
