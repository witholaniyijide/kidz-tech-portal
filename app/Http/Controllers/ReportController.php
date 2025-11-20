<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ReportApprovedNotification;
use App\Notifications\ReportSubmittedNotification;
use Illuminate\Support\Facades\Notification;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::with(['student', 'instructor']);

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('month') && $request->month) {
            $query->where('month', $request->month);
        }

        if ($request->has('year') && $request->year) {
            $query->where('year', $request->year);
        }

        $reports = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('reports.index', compact('reports'));
    }

    public function create()
    {
        $students = Student::active()->orderBy('first_name')->get();
        return view('reports.create', compact('students'));
    }

        public function store(Request $request)
{
    $validated = $request->validate([
        'student_id' => 'required|exists:students,id',
        'month' => 'required|string',
        'year' => 'required|string',
        'courses' => 'required|array|min:1',
        'skills_mastered' => 'required|string',
        'skills_new' => 'nullable|string',
        'projects' => 'required|array|min:1',
        'improvement' => 'required|string',
        'goals' => 'required|string',
        'assignments' => 'required|string',
        'comments' => 'required|string',
    ]);

    $validated['skills_mastered'] = json_decode($validated['skills_mastered'], true);
    $validated['skills_new'] = $validated['skills_new'] ? json_decode($validated['skills_new'], true) : [];
        $validated['instructor_id'] = Auth::id();
        $validated['status'] = $request->has('submit') ? 'submitted' : 'draft';

        Report::create($validated);
    
    // Send notification to directors when report is submitted
if (isset($validated['submit'])) {
    $directors = \App\Models\User::role('director')->get();
    Notification::send($directors, new ReportSubmittedNotification($report));
}

return redirect()->route('reports.index')
    ->with('success', 'Report created successfully!');
    }

    public function show(Report $report)
    {
        $report->load(['student', 'instructor', 'approvedBy']);
        return view('reports.show', compact('report'));
    }

    public function edit(Report $report)
    {
        $students = Student::active()->orderBy('first_name')->get();
        return view('reports.edit', compact('report', 'students'));
    }

        public function update(Request $request, Report $report)
{
    $validated = $request->validate([
        'student_id' => 'required|exists:students,id',
        'month' => 'required|string',
        'year' => 'required|string',
        'courses' => 'required|array|min:1',
        'skills_mastered' => 'required|string',
        'skills_new' => 'nullable|string',
        'projects' => 'required|array|min:1',
        'improvement' => 'required|string',
        'goals' => 'required|string',
        'assignments' => 'required|string',
        'comments' => 'required|string',
    ]);

    $validated['skills_mastered'] = json_decode($validated['skills_mastered'], true);
    $validated['skills_new'] = $validated['skills_new'] ? json_decode($validated['skills_new'], true) : [];

        $validated['status'] = $request->has('submit') ? 'submitted' : 'draft';

        $report->update($validated);

        return redirect()->route('reports.show', $report)
            ->with('success', 'Report updated successfully!');
    }

    public function approve(Report $report)
    {
        $report->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);
        // Send notification to instructor and parent
$report->instructor->notify(new ReportApprovedNotification($report));

if ($report->student->parent) {
    $report->student->parent->notify(new ReportApprovedNotification($report));
}

        return back()->with('success', 'Report approved successfully!');
    }

    public function reject(Report $report)
    {
        $report->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Report rejected!');
    }

    public function destroy(Report $report)
    {
        $report->delete();
        return redirect()->route('reports.index')
            ->with('success', 'Report deleted successfully!');
    }
}
