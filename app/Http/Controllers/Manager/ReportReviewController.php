<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\TutorReport;
use App\Models\Tutor;
use App\Models\Student;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportReviewController extends Controller
{
    /**
     * Display a listing of submitted reports pending manager review.
     */
    public function index(Request $request)
    {
        $query = TutorReport::with(['student', 'tutor']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
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

        // Filter by student name (search)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        $reports = $query->orderBy('submitted_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Get statistics
        $stats = [
            'total' => TutorReport::count(),
            'approved' => TutorReport::where('status', 'approved-by-manager')->count(),
            'pending' => TutorReport::where('status', 'submitted')->count(),
            'completed' => TutorReport::where('status', 'approved-by-director')->count(),
            'returned' => TutorReport::where('status', 'returned')->count(),
        ];

        // Get unique months from reports for filter
        $months = TutorReport::select('month')
            ->distinct()
            ->orderBy('month', 'desc')
            ->pluck('month');

        // Get unique years from reports for filter
        $years = TutorReport::select('year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Get all tutors for filter
        $tutors = Tutor::where('status', 'active')
            ->orderBy('first_name')
            ->get();

        // Get all students for filter
        $students = Student::where('status', 'active')
            ->orderBy('first_name')
            ->get();

        // Analytics data - Monthly breakdown with totals
        $monthlyAnalytics = TutorReport::select(
                'month',
                'year',
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN status = 'submitted' THEN 1 ELSE 0 END) as pending"),
                DB::raw("SUM(CASE WHEN status = 'approved-by-manager' THEN 1 ELSE 0 END) as approved_by_manager"),
                DB::raw("SUM(CASE WHEN status = 'approved-by-director' THEN 1 ELSE 0 END) as completed"),
                DB::raw("SUM(CASE WHEN status = 'draft' THEN 1 ELSE 0 END) as draft"),
                DB::raw("SUM(CASE WHEN status = 'returned' THEN 1 ELSE 0 END) as returned")
            )
            ->groupBy('month', 'year')
            ->orderBy('year', 'desc')
            ->orderByRaw("FIELD(month, 'December', 'November', 'October', 'September', 'August', 'July', 'June', 'May', 'April', 'March', 'February', 'January')")
            ->get();

        // Student-Tutor report overview with late submission tracking
        $studentTutorReports = Student::where('status', 'active')
            ->with(['tutor:id,first_name,last_name'])
            ->withCount([
                'tutorReports as total_reports',
                'tutorReports as pending_reports' => function ($q) {
                    $q->where('status', 'submitted');
                },
                'tutorReports as approved_reports' => function ($q) {
                    $q->whereIn('status', ['approved-by-manager', 'approved-by-director']);
                },
            ])
            ->orderBy('first_name')
            ->get()
            ->map(function ($student) {
                // Get latest report for this student
                $latestReport = TutorReport::where('student_id', $student->id)
                    ->orderBy('submitted_at', 'desc')
                    ->first();

                $student->latest_report = $latestReport;
                $student->latest_submitted_at = $latestReport?->submitted_at;
                $student->latest_status = $latestReport?->status;
                $student->latest_month = $latestReport ? ($latestReport->month . ' ' . $latestReport->year) : null;

                // Check if latest report is late
                $student->is_late_submission = false;
                if ($latestReport && $latestReport->submitted_at) {
                    $reportMonth = $latestReport->month;
                    $reportYear = $latestReport->year;
                    $monthNumber = date('n', strtotime("1 {$reportMonth} {$reportYear}"));
                    $lastDayOfMonth = \Carbon\Carbon::create($reportYear, $monthNumber, 1)->endOfMonth();
                    $deadline = $lastDayOfMonth->copy()->setTime(12, 0, 0);
                    $student->is_late_submission = $latestReport->submitted_at->gt($deadline);
                }

                return $student;
            });

        // Count late submissions
        $lateSubmissionsCount = TutorReport::whereNotNull('submitted_at')
            ->get()
            ->filter(function ($report) {
                if (!$report->submitted_at || !$report->month || !$report->year) {
                    return false;
                }
                $monthNumber = date('n', strtotime("1 {$report->month} {$report->year}"));
                $lastDayOfMonth = \Carbon\Carbon::create($report->year, $monthNumber, 1)->endOfMonth();
                $deadline = $lastDayOfMonth->copy()->setTime(12, 0, 0);
                return $report->submitted_at->gt($deadline);
            })
            ->count();

        // Students awaiting reports (no report submitted for current month)
        $currentMonth = now()->format('F');
        $currentYear = now()->format('Y');
        $studentsAwaitingReports = Student::where('status', 'active')
            ->whereDoesntHave('tutorReports', function ($q) use ($currentMonth, $currentYear) {
                $q->where('month', $currentMonth)
                  ->where('year', $currentYear)
                  ->whereIn('status', ['submitted', 'approved-by-manager', 'approved-by-director']);
            })
            ->with('tutor:id,first_name,last_name')
            ->get();

        // Add late submissions count to stats
        $stats['late_submissions'] = $lateSubmissionsCount;
        $stats['awaiting_reports'] = $studentsAwaitingReports->count();

        return view('manager.reports.index', compact(
            'reports',
            'stats',
            'months',
            'years',
            'tutors',
            'students',
            'monthlyAnalytics',
            'studentTutorReports',
            'studentsAwaitingReports',
            'currentMonth',
            'currentYear'
        ));
    }

    /**
     * Display the specified report for review.
     */
    public function show(TutorReport $report)
    {
        // Load relationships
        $report->load(['student', 'tutor', 'comments.user']);

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
                ->route('manager.tutor-reports.index')
                ->with('error', 'This report cannot be approved at this time.');
        }

        // Update the report
        DB::transaction(function () use ($report, $validated) {
            $report->update([
                'status' => 'approved-by-manager',
                'manager_comment' => $validated['manager_comment'] ?? null,
                'approved_by_manager_at' => now(),
            ]);

            // Create notification for tutor
            $studentName = $report->student ? $report->student->fullName() : 'Unknown Student';
            \App\Models\TutorNotification::create([
                'tutor_id' => $report->tutor_id,
                'title' => 'Report Approved by Manager',
                'body' => "Your report for {$studentName} ({$report->month}) has been approved by the manager and is awaiting director approval.",
                'type' => 'system',
                'is_read' => false,
                'meta' => ['report_id' => $report->id],
            ]);
        });

        // Notify directors that report was approved by manager (in-app only)
        app(NotificationService::class)->notifyDirectorReportApproved($report);

        return redirect()
            ->route('manager.tutor-reports.index')
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
                ->route('manager.tutor-reports.index')
                ->with('error', 'This report cannot be sent back at this time.');
        }

        // Update the report - set status to returned with tracking
        DB::transaction(function () use ($report, $validated) {
            $report->update([
                'status' => 'returned',
                'manager_comment' => $validated['manager_comment'],
                'returned_at' => now(),
                'returned_by' => 'manager',
            ]);
        });

        // Notify tutor about the returned report
        app(NotificationService::class)->notifyReportReturned($report, $validated['manager_comment']);

        return redirect()
            ->route('manager.tutor-reports.index')
            ->with('success', 'Report has been sent back to the tutor for corrections.');
    }

    /**
     * Export report as PDF.
     */
    public function exportPdf(TutorReport $report)
    {
        // Load relationships
        $report->load(['student', 'tutor']);

        // Generate PDF
        $pdf = Pdf::loadView('tutor.reports.pdf', compact('report'));

        // Generate filename
        $filename = 'report_' . $report->student->first_name . '_' . $report->student->last_name . '_' . $report->month . '.pdf';

        // Return PDF download
        return $pdf->download($filename);
    }

    /**
     * Display printable view of report.
     */
    public function print(TutorReport $report)
    {
        // Load relationships
        $report->load(['student', 'tutor']);

        return view('tutor.reports.print', compact('report'));
    }

    /**
     * Export report as JSON for WhatsApp.
     */
    public function exportWhatsApp(TutorReport $report)
    {
        // Load relationships
        $report->load(['student', 'tutor']);

        $projectsList = collect($report->projects ?? [])
            ->filter(fn($p) => !empty($p['title']))
            ->map(fn($p, $i) => "Project " . ($i + 1) . ": " . $p['title'] . ($p['link'] ? " – " . $p['link'] : ''))
            ->implode("\n");

        $text = "*Kidz Tech Coding Club: Monthly Progress Report*\n\n"
            . "*Student:* " . $report->student->first_name . " " . $report->student->last_name . "\n"
            . "*Month:* " . $report->month . " " . $report->year . "\n"
            . "*Instructor:* " . $report->tutor->first_name . " " . $report->tutor->last_name . "\n"
            . "*Course(s):* " . implode(', ', $report->courses ?? []) . "\n\n"
            . "*1. Progress Overview:*\n"
            . "*Skills Mastered:* " . implode(', ', $report->skills_mastered ?? []) . "\n"
            . "*New Skills:* " . (count($report->new_skills ?? []) > 0 ? implode(', ', $report->new_skills) : 'N/A') . "\n\n"
            . "*2. Projects/Activities Completed:*\n" . $projectsList . "\n\n"
            . "*3. Areas for Improvement:*\n" . ($report->areas_for_improvement ?? 'N/A') . "\n\n"
            . "*4. Goals for Next Month:*\n" . ($report->goals_next_month ?? 'N/A') . "\n\n"
            . "*5. Assignment/Projects during the month:*\n" . ($report->assignments ?? 'N/A') . "\n\n"
            . "*6. Comments/Observation:*\n" . ($report->comments_observation ?? 'N/A');

        return response()->json(['success' => true, 'text' => $text]);
    }

    /**
     * Bulk approve multiple submitted reports.
     */
    public function bulkApprove(Request $request)
    {
        $validated = $request->validate([
            'report_ids' => 'required|array|min:1',
            'report_ids.*' => 'exists:tutor_reports,id',
        ]);

        $reports = TutorReport::whereIn('id', $validated['report_ids'])
            ->where('status', 'submitted')
            ->with('student')
            ->get();

        if ($reports->isEmpty()) {
            return redirect()->back()->with('error', 'No eligible reports found to approve.');
        }

        $approvedCount = 0;
        $notificationService = app(NotificationService::class);

        DB::transaction(function () use ($reports, &$approvedCount) {
            foreach ($reports as $report) {
                $report->update([
                    'status' => 'approved-by-manager',
                    'manager_comment' => null,
                    'approved_by_manager_at' => now(),
                ]);

                $studentName = $report->student ? $report->student->fullName() : 'Unknown Student';
                \App\Models\TutorNotification::create([
                    'tutor_id' => $report->tutor_id,
                    'title' => 'Report Approved by Manager',
                    'body' => "Your report for {$studentName} ({$report->month}) has been approved by the manager and is awaiting director approval.",
                    'type' => 'system',
                    'is_read' => false,
                    'meta' => ['report_id' => $report->id],
                ]);

                $approvedCount++;
            }
        });

        // Notify directors for each approved report
        foreach ($reports as $report) {
            $notificationService->notifyDirectorReportApproved($report);
        }

        return redirect()
            ->route('manager.tutor-reports.index')
            ->with('success', "{$approvedCount} report(s) approved successfully.");
    }

    /**
     * Export reports data to Excel (CSV format).
     * Includes submission timing to track late submissions (after 12noon on last day of month).
     */
    public function exportExcel(Request $request)
    {
        $query = TutorReport::with(['student', 'tutor']);

        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('tutor_id')) {
            $query->where('tutor_id', $request->tutor_id);
        }
        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        $reports = $query->orderBy('submitted_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        // Create CSV content
        $csvData = [];

        // Header row
        $csvData[] = [
            'Student Name',
            'Tutor Name',
            'Report Month',
            'Year',
            'Submitted At',
            'Late Submission?',
            'Manager Approved?',
            'Manager Approved At',
            'Director Approved?',
            'Director Approved At',
            'Current Status',
        ];

        foreach ($reports as $report) {
            $studentName = $report->student
                ? "{$report->student->first_name} {$report->student->last_name}"
                : 'Unknown';
            $tutorName = $report->tutor
                ? "{$report->tutor->first_name} {$report->tutor->last_name}"
                : 'Unknown';

            // Determine if submission was late (after 12noon on last day of month)
            $lateSubmission = 'N/A';
            if ($report->submitted_at) {
                $submittedAt = $report->submitted_at;
                $reportMonth = $report->month; // e.g., "January"
                $reportYear = $report->year;

                // Parse the month name to get the month number
                $monthNumber = date('n', strtotime("1 {$reportMonth} {$reportYear}"));

                // Get the last day of the report month
                $lastDayOfMonth = \Carbon\Carbon::create($reportYear, $monthNumber, 1)->endOfMonth();

                // Deadline is 12:00 PM (noon) on the last day
                $deadline = $lastDayOfMonth->copy()->setTime(12, 0, 0);

                if ($submittedAt->gt($deadline)) {
                    $lateSubmission = 'YES - ' . $submittedAt->format('M d, Y g:i A');
                } else {
                    $lateSubmission = 'No';
                }
            }

            // Manager approval status
            $managerApproved = in_array($report->status, ['approved-by-manager', 'approved-by-director']) ? 'Yes' : 'No';
            $managerApprovedAt = $report->approved_by_manager_at
                ? $report->approved_by_manager_at->format('M d, Y g:i A')
                : '';

            // Director approval status
            $directorApproved = $report->status === 'approved-by-director' ? 'Yes' : 'No';
            $directorApprovedAt = $report->approved_by_director_at
                ? $report->approved_by_director_at->format('M d, Y g:i A')
                : '';

            $csvData[] = [
                $studentName,
                $tutorName,
                $report->month,
                $report->year,
                $report->submitted_at ? $report->submitted_at->format('M d, Y g:i A') : 'Not submitted',
                $lateSubmission,
                $managerApproved,
                $managerApprovedAt,
                $directorApproved,
                $directorApprovedAt,
                ucfirst(str_replace('-', ' ', $report->status)),
            ];
        }

        // Generate filename with current date
        $filename = 'tutor_reports_' . now()->format('Y-m-d_His') . '.csv';

        // Create CSV response
        $callback = function() use ($csvData) {
            $file = fopen('php://output', 'w');

            // Add BOM for Excel UTF-8 compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ]);
    }
}
