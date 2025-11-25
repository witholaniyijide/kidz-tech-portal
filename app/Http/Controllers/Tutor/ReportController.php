<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tutor\StoreReportRequest;
use App\Http\Requests\Tutor\UpdateReportRequest;
use App\Models\Student;
use App\Models\TutorNotification;
use App\Models\TutorReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Display a listing of the tutor's reports.
     */
    public function index()
    {
        // Get the authenticated tutor
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            abort(403, 'You do not have a tutor profile.');
        }

        // Get all reports for this tutor
        $reports = TutorReport::where('tutor_id', $tutor->id)
            ->with(['student'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('tutor.reports.index', compact('reports'));
    }

    /**
     * Show the form for creating a new report.
     */
    public function create()
    {
        // Get the authenticated tutor
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            abort(403, 'You do not have a tutor profile.');
        }

        // Get students assigned to this tutor
        $students = $tutor->students()->active()->get();

        return view('tutor.reports.create', compact('students'));
    }

    /**
     * Store a newly created report in storage.
     */
    public function store(StoreReportRequest $request)
    {
        // Get the authenticated tutor
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            abort(403, 'You do not have a tutor profile.');
        }

        // Verify student belongs to this tutor
        $student = Student::findOrFail($request->student_id);

        if ($student->tutor_id !== $tutor->id) {
            abort(403, 'You can only create reports for your assigned students.');
        }

        // Create report
        $report = TutorReport::create([
            'tutor_id' => $tutor->id,
            'student_id' => $request->student_id,
            'month' => $request->month,
            'progress_summary' => $request->progress_summary,
            'strengths' => $request->strengths,
            'weaknesses' => $request->weaknesses,
            'next_steps' => $request->next_steps,
            'attendance_score' => $request->attendance_score ?? 0,
            'performance_rating' => $request->performance_rating,
            'status' => $request->status ?? 'draft',
        ]);

        // If submitted immediately, send notification
        if ($report->status === 'submitted') {
            $report->submitted_at = now();
            $report->save();

            // Create notification for manager
            TutorNotification::create([
                'tutor_id' => $tutor->id,
                'title' => 'Report Submitted',
                'body' => "Report for {$student->fullName()} ({$report->month}) has been submitted for review.",
                'type' => 'system',
                'is_read' => false,
                'meta' => ['report_id' => $report->id],
            ]);
        }

        $message = $report->status === 'draft'
            ? 'Report saved as draft successfully!'
            : 'Report submitted successfully! Awaiting manager review.';

        return redirect()
            ->route('tutor.reports.index')
            ->with('success', $message);
    }

    /**
     * Display the specified report.
     */
    public function show(TutorReport $report)
    {
        // Get the authenticated tutor
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            abort(403, 'You do not have a tutor profile.');
        }

        // Verify report belongs to this tutor
        if ($report->tutor_id !== $tutor->id) {
            abort(403, 'Unauthorized access to this report.');
        }

        // Load relationships
        $report->load(['student', 'comments.user']);

        return view('tutor.reports.show', compact('report'));
    }

    /**
     * Show the form for editing the specified report.
     */
    public function edit(TutorReport $report)
    {
        // Get the authenticated tutor
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            abort(403, 'You do not have a tutor profile.');
        }

        // Verify report belongs to this tutor
        if ($report->tutor_id !== $tutor->id) {
            abort(403, 'Unauthorized access to this report.');
        }

        // Only allow editing draft or returned reports
        if (!in_array($report->status, ['draft', 'returned'])) {
            return redirect()
                ->route('tutor.reports.show', $report)
                ->with('error', 'Only draft or returned reports can be edited.');
        }

        // Get students assigned to this tutor
        $students = $tutor->students()->active()->get();

        return view('tutor.reports.edit', compact('report', 'students'));
    }

    /**
     * Update the specified report in storage.
     */
    public function update(UpdateReportRequest $request, TutorReport $report)
    {
        // Get the authenticated tutor
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            abort(403, 'You do not have a tutor profile.');
        }

        // Verify report belongs to this tutor
        if ($report->tutor_id !== $tutor->id) {
            abort(403, 'Unauthorized access to this report.');
        }

        // Only allow editing draft or returned reports
        if (!in_array($report->status, ['draft', 'returned'])) {
            return redirect()
                ->route('tutor.reports.show', $report)
                ->with('error', 'Only draft or returned reports can be edited.');
        }

        // Verify student belongs to this tutor
        $student = Student::findOrFail($request->student_id);

        if ($student->tutor_id !== $tutor->id) {
            abort(403, 'You can only create reports for your assigned students.');
        }

        // Update report
        $report->update([
            'student_id' => $request->student_id,
            'month' => $request->month,
            'progress_summary' => $request->progress_summary,
            'strengths' => $request->strengths,
            'weaknesses' => $request->weaknesses,
            'next_steps' => $request->next_steps,
            'attendance_score' => $request->attendance_score ?? 0,
            'performance_rating' => $request->performance_rating,
        ]);

        return redirect()
            ->route('tutor.reports.edit', $report)
            ->with('success', 'Report updated successfully!');
    }

    /**
     * Remove the specified report from storage.
     */
    public function destroy(TutorReport $report)
    {
        // Get the authenticated tutor
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            abort(403, 'You do not have a tutor profile.');
        }

        // Verify report belongs to this tutor
        if ($report->tutor_id !== $tutor->id) {
            abort(403, 'Unauthorized access to this report.');
        }

        // Only allow deleting draft reports
        if ($report->status !== 'draft') {
            return redirect()
                ->route('tutor.reports.index')
                ->with('error', 'Only draft reports can be deleted.');
        }

        $report->delete();

        return redirect()
            ->route('tutor.reports.index')
            ->with('success', 'Report deleted successfully!');
    }

    /**
     * Submit a draft report for review.
     */
    public function submit(Request $request, TutorReport $report)
    {
        // Get the authenticated tutor
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            abort(403, 'You do not have a tutor profile.');
        }

        // Verify report belongs to this tutor
        if ($report->tutor_id !== $tutor->id) {
            abort(403, 'Unauthorized access to this report.');
        }

        // Only allow submitting draft reports
        if ($report->status !== 'draft') {
            return redirect()
                ->route('tutor.reports.show', $report)
                ->with('error', 'Only draft reports can be submitted.');
        }

        // Update report status
        $report->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        // Create notification for manager
        TutorNotification::create([
            'tutor_id' => $tutor->id,
            'title' => 'Report Submitted',
            'body' => "Report for {$report->student->fullName()} ({$report->month}) has been submitted for review.",
            'type' => 'system',
            'is_read' => false,
            'meta' => ['report_id' => $report->id],
        ]);

        return redirect()
            ->route('tutor.reports.show', $report)
            ->with('success', 'Report submitted successfully! Awaiting manager review.');
    }
}
