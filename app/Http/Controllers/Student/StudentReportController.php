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
    public function index(Request $request)
    {
        $student = $this->getAuthenticatedStudent();

        // Determine sort column and direction
        $sort = $request->get('sort', 'newest');
        $sortColumn = 'created_at';
        $sortDirection = 'desc';

        if ($sort === 'oldest') {
            $sortDirection = 'asc';
        } elseif ($sort === 'rating') {
            $sortColumn = 'performance_rating';
            $sortDirection = 'desc';
        }

        // Get only director-approved reports for this student with filters
        $reports = TutorReport::query()
            ->where('student_id', $student->id)
            ->where('status', 'approved-by-director')
            ->when($request->q, function ($query) use ($request) {
                $query->where(function ($w) use ($request) {
                    $w->where('progress_summary', 'like', '%' . $request->q . '%')
                      ->orWhere('strengths', 'like', '%' . $request->q . '%')
                      ->orWhere('next_steps', 'like', '%' . $request->q . '%')
                      ->orWhere('month', 'like', '%' . $request->q . '%');
                });
            })
            ->when($request->month, function ($query) use ($request) {
                $query->whereMonth('created_at', \Carbon\Carbon::parse($request->month)->month);
            })
            ->with(['tutor', 'director'])
            ->orderBy($sortColumn, $sortDirection)
            ->paginate(20);

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

        // Prepare radar chart data for Chart.js
        $radarData = [
            'labels' => ['Attendance', 'Performance', 'Progress', 'Engagement', 'Technical Skills'],
            'values' => [
                $report->attendance_score ?? 0,
                ($report->performance_rating ?? 0) * 20, // Convert 1-5 rating to 0-100 scale
                $student->progressPercentage() ?? 0,
                $this->estimateEngagement($report),
                $this->estimateTechnicalSkills($report)
            ]
        ];

        return view('student.reports.show', compact('student', 'report', 'radarData'));
    }

    /**
     * Estimate engagement score based on report data.
     */
    protected function estimateEngagement($report)
    {
        // Base engagement on attendance and report content quality
        $baseScore = ($report->attendance_score ?? 0) * 0.6;
        $contentBonus = strlen($report->progress_summary ?? '') > 100 ? 20 : 10;
        return min(100, $baseScore + $contentBonus);
    }

    /**
     * Estimate technical skills score based on report data.
     */
    protected function estimateTechnicalSkills($report)
    {
        // Base technical skills on performance rating and progress
        $performanceScore = ($report->performance_rating ?? 0) * 18;
        $strengthBonus = strlen($report->strengths ?? '') > 100 ? 15 : 5;
        return min(100, $performanceScore + $strengthBonus);
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

        // Generate PDF using the student report print view
        $pdf = Pdf::loadView('student.reports.print', compact('report'))
            ->setPaper('a4', 'portrait');

        $filename = 'Report-' . $report->id . '-' . $report->month . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Alias for exportPdf for download response.
     */
    public function download(TutorReport $report)
    {
        return $this->exportPdf($report);
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
