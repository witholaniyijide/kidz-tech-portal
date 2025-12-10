<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Student;
use App\Models\Tutor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * Display a listing of director-approved reports.
     */
    public function index(Request $request)
    {
        // Admin sees ONLY director-approved reports
        $query = Report::with(['student', 'tutor'])
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
            $query->where('report_month', $request->month);
        }

        // Filter by year
        if ($request->filled('year')) {
            $query->where('report_year', $request->year);
        }

        $reports = $query->orderBy('created_at', 'desc')->paginate(20);

        $students = Student::where('status', 'active')->orderBy('first_name')->get();
        $tutors = Tutor::where('status', 'active')->orderBy('first_name')->get();
        
        $months = Report::where('status', 'approved-by-director')
            ->select('report_month')
            ->distinct()
            ->pluck('report_month');
            
        $years = Report::where('status', 'approved-by-director')
            ->select('report_year')
            ->distinct()
            ->orderBy('report_year', 'desc')
            ->pluck('report_year');

        return view('admin.reports.index', compact('reports', 'students', 'tutors', 'months', 'years'));
    }

    /**
     * Display the specified report.
     */
    public function show(Report $report)
    {
        // Admin can only view director-approved reports
        if ($report->status !== 'approved-by-director') {
            abort(403, 'This report is not available for viewing.');
        }

        $report->load(['student', 'tutor', 'manager', 'director']);
        return view('admin.reports.show', compact('report'));
    }

    /**
     * Generate PDF of the report.
     */
    public function pdf(Report $report)
    {
        if ($report->status !== 'approved-by-director') {
            abort(403, 'This report is not available for download.');
        }

        $report->load(['student', 'tutor', 'manager', 'director']);
        
        // For now, return a print view. DomPDF can be integrated later.
        return view('admin.reports.pdf', compact('report'));
    }

    /**
     * Print view of the report.
     */
    public function print(Report $report)
    {
        if ($report->status !== 'approved-by-director') {
            abort(403, 'This report is not available for printing.');
        }

        $report->load(['student', 'tutor', 'manager', 'director']);
        return view('admin.reports.print', compact('report'));
    }

    // Note: Admin CANNOT approve, reject, or comment on reports per specification
}
