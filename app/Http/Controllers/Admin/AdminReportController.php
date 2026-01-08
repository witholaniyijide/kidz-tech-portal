<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TutorReport;
use App\Models\Student;
use App\Models\Tutor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->hasRole('admin')) {
                abort(403, 'Unauthorized access.');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of approved reports.
     * Admin sees ONLY reports that have been approved by director.
     */
    public function index(Request $request)
    {
        // Admin sees ONLY director-approved reports
        $query = TutorReport::with(['student', 'tutor', 'director'])
            ->where('status', 'approved-by-director');

        // Filter by student
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
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

        $reports = $query->orderBy('approved_by_director_at', 'desc')->paginate(20);

        $students = Student::where('status', 'active')->orderBy('first_name')->get();
        $tutors = Tutor::where('status', 'active')->orderBy('first_name')->get();

        $months = TutorReport::where('status', 'approved-by-director')
            ->select('month')
            ->distinct()
            ->orderBy('month', 'desc')
            ->pluck('month');

        $years = TutorReport::where('status', 'approved-by-director')
            ->select('year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('admin.reports.index', compact('reports', 'students', 'tutors', 'months', 'years'));
    }

    /**
     * Display the specified report.
     */
    public function show(TutorReport $report)
    {
        // Admin can only view director-approved reports
        if ($report->status !== 'approved-by-director') {
            abort(403, 'This report is not available for viewing.');
        }

        $report->load(['student', 'tutor', 'director']);
        return view('admin.reports.show', compact('report'));
    }

    /**
     * Generate PDF of the report.
     */
    public function exportPdf(TutorReport $report)
    {
        if ($report->status !== 'approved-by-director') {
            abort(403, 'This report is not available for download.');
        }

        $report->load(['student', 'tutor', 'director']);

        $pdf = Pdf::loadView('tutor.reports.pdf', compact('report'));
        $filename = 'report_' . $report->student->first_name . '_' . $report->student->last_name . '_' . $report->month . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Print view of the report.
     */
    public function print(TutorReport $report)
    {
        if ($report->status !== 'approved-by-director') {
            abort(403, 'This report is not available for printing.');
        }

        $report->load(['student', 'tutor', 'director']);
        return view('tutor.reports.print', compact('report'));
    }

    // Note: Admin CANNOT approve, reject, or comment on reports per specification
}
