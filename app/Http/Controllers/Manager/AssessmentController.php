<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AssessmentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:manager']);
    }

    /**
     * Display a listing of assessments (reports).
     */
    public function index(Request $request)
    {
        $query = Report::with(['student', 'instructor'])
            ->whereIn('status', ['submitted', 'submitted_to_manager', 'approved_by_manager']);

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by month if provided
        if ($request->has('month') && $request->month) {
            $query->where('month', $request->month);
        }

        // Filter by year if provided
        if ($request->has('year') && $request->year) {
            $query->where('year', $request->year);
        }

        $reports = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('assessments.index', compact('reports'));
    }

    /**
     * Display the specified assessment.
     */
    public function show(Report $assessment)
    {
        // Load relationships
        $assessment->load(['student', 'instructor', 'approvedBy']);

        return view('assessments.show', compact('assessment'));
    }

    /**
     * Add manager comment to an assessment.
     */
    public function comment(Request $request, Report $assessment)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        // Add manager comment
        $currentComments = $assessment->comments ?? '';
        $managerComment = "\n\n--- Manager Feedback (" . Carbon::now()->format('Y-m-d H:i') . ") ---\n" . $request->comment;

        $assessment->comments = $currentComments . $managerComment;
        $assessment->save();

        return redirect()
            ->route('manager.assessments.show', $assessment)
            ->with('success', 'Your comment has been added.');
    }

    /**
     * Create a new assessment (placeholder for manager-initiated assessments).
     */
    public function create()
    {
        return view('assessments.create');
    }

    /**
     * Store a newly created assessment.
     */
    public function store(Request $request)
    {
        // Managers can initiate assessments but not finalize them
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer',
            'comments' => 'nullable|string',
        ]);

        $assessment = Report::create([
            'student_id' => $request->student_id,
            'instructor_id' => Auth::id(),
            'month' => $request->month,
            'year' => $request->year,
            'comments' => $request->comments,
            'status' => 'draft',
        ]);

        return redirect()
            ->route('manager.assessments.index')
            ->with('success', 'Assessment created successfully.');
    }

    /**
     * Update the specified assessment.
     */
    public function update(Request $request, Report $assessment)
    {
        // Managers can comment but not change core assessment data
        return redirect()
            ->route('manager.assessments.show', $assessment)
            ->with('info', 'Managers cannot directly edit assessments. Use the comment feature instead.');
    }
}
