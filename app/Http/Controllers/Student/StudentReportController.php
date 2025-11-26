<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\TutorReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentReportController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->hasRole('student') && !Auth::user()->hasRole('admin')) {
                abort(403, 'Unauthorized access.');
            }
            return $next($request);
        });
    }

    /**
     * Get the student associated with the authenticated user.
     */
    protected function getAuthenticatedStudent()
    {
        // Assuming students table has a user_id column linking to users
        // If not, we need to establish this relationship
        $student = Student::where('user_id', Auth::id())->first();

        if (!$student) {
            abort(404, 'Student profile not found. Please contact administration.');
        }

        return $student;
    }

    /**
     * Display a listing of director-approved reports for the authenticated student.
     */
    public function index()
    {
        $student = $this->getAuthenticatedStudent();

        // Get only director-approved reports for this student
        $reports = $student->approvedReports()
                          ->with(['tutor'])
                          ->orderBy('month', 'desc')
                          ->paginate(10);

        return view('student.reports.index', compact('student', 'reports'));
    }

    /**
     * Display the specified director-approved report.
     */
    public function show(TutorReport $report)
    {
        $student = $this->getAuthenticatedStudent();

        // Ensure this report belongs to the authenticated student
        if ($report->student_id !== $student->id) {
            abort(403, 'Unauthorized: You can only view your own reports.');
        }

        // Ensure this report is director-approved
        if ($report->status !== 'approved-by-director') {
            abort(403, 'Unauthorized: Only approved reports are visible.');
        }

        // Load relationships
        $report->load(['tutor', 'student', 'director']);

        return view('student.reports.show', compact('student', 'report'));
    }

    /**
     * Export report as PDF.
     */
    public function exportPdf(TutorReport $report)
    {
        $student = $this->getAuthenticatedStudent();

        // Ensure this report belongs to the authenticated student
        if ($report->student_id !== $student->id) {
            abort(403, 'Unauthorized access.');
        }

        // Ensure this report is director-approved
        if ($report->status !== 'approved-by-director') {
            abort(403, 'Only approved reports can be exported.');
        }

        // Load relationships
        $report->load(['tutor', 'student', 'director']);

        // Generate PDF using the tutor report PDF view
        $pdf = Pdf::loadView('tutor.reports.pdf', compact('report'));

        $filename = 'report_' . $student->first_name . '_' . $student->last_name . '_' . $report->month . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Print-friendly view of the report.
     */
    public function print(TutorReport $report)
    {
        $student = $this->getAuthenticatedStudent();

        // Ensure this report belongs to the authenticated student
        if ($report->student_id !== $student->id) {
            abort(403, 'Unauthorized access.');
        }

        // Ensure this report is director-approved
        if ($report->status !== 'approved-by-director') {
            abort(403, 'Only approved reports can be printed.');
        }

        // Load relationships
        $report->load(['tutor', 'student', 'director']);

        return view('student.reports.print', compact('student', 'report'));
    }
}
