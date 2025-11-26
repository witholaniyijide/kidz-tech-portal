<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\TutorReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentReportController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->hasRole('parent') && !Auth::user()->hasRole('admin')) {
                abort(403, 'Unauthorized access.');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of director-approved reports for a specific child.
     */
    public function index(Student $student)
    {
        // Ensure this student belongs to the logged-in parent
        if ($student->parent_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized: You can only view reports for your own children.');
        }

        // Get only director-approved reports
        $reports = $student->approvedReports()
                          ->with(['tutor'])
                          ->paginate(10);

        return view('parent.reports.index', compact('student', 'reports'));
    }

    /**
     * Display the specified director-approved report.
     */
    public function show(Student $student, TutorReport $report)
    {
        // Ensure this student belongs to the logged-in parent
        if ($student->parent_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized: You can only view reports for your own children.');
        }

        // Ensure this report belongs to this student
        if ($report->student_id !== $student->id) {
            abort(403, 'Unauthorized: This report does not belong to this student.');
        }

        // Ensure this report is director-approved
        if ($report->status !== 'approved-by-director') {
            abort(403, 'Unauthorized: Only director-approved reports are visible to parents.');
        }

        // Load relationships
        $report->load(['tutor', 'student']);

        return view('parent.reports.show', compact('student', 'report'));
    }

    /**
     * Export report as PDF (future implementation).
     */
    public function exportPdf(Student $student, TutorReport $report)
    {
        // Ensure this student belongs to the logged-in parent
        if ($student->parent_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized access.');
        }

        // Ensure this report belongs to this student
        if ($report->student_id !== $student->id) {
            abort(403, 'Unauthorized access.');
        }

        // Ensure this report is director-approved
        if ($report->status !== 'approved-by-director') {
            abort(403, 'Only approved reports can be exported.');
        }

        // TODO: Implement PDF generation
        return redirect()
            ->back()
            ->with('info', 'PDF export feature coming soon.');
    }

    /**
     * Print-friendly view of the report.
     */
    public function print(Student $student, TutorReport $report)
    {
        // Ensure this student belongs to the logged-in parent
        if ($student->parent_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized access.');
        }

        // Ensure this report belongs to this student
        if ($report->student_id !== $student->id) {
            abort(403, 'Unauthorized access.');
        }

        // Ensure this report is director-approved
        if ($report->status !== 'approved-by-director') {
            abort(403, 'Only approved reports can be printed.');
        }

        // Load relationships
        $report->load(['tutor', 'student']);

        return view('parent.reports.print', compact('student', 'report'));
    }
}
