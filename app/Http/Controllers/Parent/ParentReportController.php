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
     * Display all director-approved reports for all children.
     */
    public function indexAll(Request $request)
    {
        $user = Auth::user();
        $children = $user->guardiansOf()->get();

        if ($children->isEmpty()) {
            return view('parent.reports.index', [
                'children' => $children,
                'reports' => collect(),
                'selectedChild' => null,
            ]);
        }

        $studentIds = $children->pluck('id');

        // Filter by child if specified
        $selectedChildId = $request->input('child');
        $query = TutorReport::where('status', 'approved-by-director')
            ->with(['student', 'tutor']);

        if ($selectedChildId && $children->contains('id', $selectedChildId)) {
            $query->where('student_id', $selectedChildId);
            $selectedChild = $children->find($selectedChildId);
        } else {
            $query->whereIn('student_id', $studentIds);
            $selectedChild = null;
        }

        $reports = $query->orderBy('approved_by_director_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('parent.reports.index', compact('children', 'reports', 'selectedChild'));
    }

    /**
     * Display a listing of director-approved reports for a specific child.
     */
    public function index(Student $student)
    {
        // Ensure this student belongs to the logged-in parent (use guardian relationship)
        abort_unless(
            $student->guardians->contains(Auth::id()) || Auth::user()->hasRole('admin'),
            403,
            'Unauthorized: You can only view reports for your own children.'
        );

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
        // Ensure this student belongs to the logged-in parent (use guardian relationship)
        abort_unless(
            $student->guardians->contains(Auth::id()) || Auth::user()->hasRole('admin'),
            403,
            'Unauthorized: You can only view reports for your own children.'
        );

        // Ensure this report belongs to this student
        abort_unless(
            $report->student_id === $student->id,
            403,
            'Unauthorized: This report does not belong to this student.'
        );

        // Ensure this report is director-approved
        abort_unless(
            $report->status === 'approved-by-director',
            403,
            'Unauthorized: Only director-approved reports are visible to parents.'
        );

        // Load relationships
        $report->load(['tutor', 'student']);

        return view('parent.reports.show', compact('student', 'report'));
    }

    /**
     * Export report as PDF.
     */
    public function exportPdf(Student $student, TutorReport $report)
    {
        // Ensure this student belongs to the logged-in parent (use guardian relationship)
        abort_unless(
            $student->guardians->contains(Auth::id()) || Auth::user()->hasRole('admin'),
            403,
            'Unauthorized access.'
        );

        // Ensure this report belongs to this student and is director-approved
        abort_unless(
            $report->student_id === $student->id && $report->status === 'approved-by-director',
            403,
            'Unauthorized access.'
        );

        // Load relationships
        $report->load(['student', 'tutor', 'director']);

        // Generate PDF using DomPDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('tutor.reports.pdf', compact('report'));

        // Generate filename
        $filename = 'report_' . $student->first_name . '_' . $student->last_name . '_' . $report->month . '.pdf';

        // Return PDF download
        return $pdf->download($filename);
    }

    /**
     * Print-friendly view of the report.
     */
    public function print(Student $student, TutorReport $report)
    {
        // Ensure this student belongs to the logged-in parent (use guardian relationship)
        abort_unless(
            $student->guardians->contains(Auth::id()) || Auth::user()->hasRole('admin'),
            403,
            'Unauthorized access.'
        );

        // Ensure this report belongs to this student and is director-approved
        abort_unless(
            $report->student_id === $student->id && $report->status === 'approved-by-director',
            403,
            'Unauthorized access.'
        );

        // Load relationships
        $report->load(['tutor', 'student']);

        return view('parent.reports.print', compact('student', 'report'));
    }
}
