<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\TutorAssessment;
use App\Models\Tutor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AssessmentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:manager']);
    }

    /**
     * Display a listing of assessments.
     */
    public function index(Request $request)
    {
        $query = TutorAssessment::with(['tutor', 'manager']);

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
            $query->where('assessment_month', 'like', '%' . $request->month . '%');
        }

        $assessments = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get statistics
        $stats = [
            'total' => TutorAssessment::count(),
            'pending' => TutorAssessment::whereIn('status', ['draft', 'submitted'])->count(),
            'awaiting_director' => TutorAssessment::where('status', 'approved-by-manager')->count(),
            'completed' => TutorAssessment::where('status', 'approved-by-director')->count(),
        ];

        // Get active tutors for filter
        $tutors = Tutor::where('status', 'active')->orderBy('first_name')->get();

        return view('manager.assessments.index', compact('assessments', 'stats', 'tutors'));
    }

    /**
     * Show form for creating a new assessment.
     */
    public function create()
    {
        $tutors = Tutor::where('status', 'active')->orderBy('first_name')->get();
        
        return view('manager.assessments.create', compact('tutors'));
    }

    /**
     * Store a newly created assessment.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tutor_id' => 'required|exists:tutors,id',
            'assessment_month' => 'required|string|max:50',
            'performance_score' => 'nullable|integer|min:0|max:100',
            'professionalism_rating' => 'nullable|integer|min:1|max:5',
            'communication_rating' => 'nullable|integer|min:1|max:5',
            'punctuality_rating' => 'nullable|integer|min:1|max:5',
            'strengths' => 'nullable|string|max:2000',
            'weaknesses' => 'nullable|string|max:2000',
            'recommendations' => 'nullable|string|max:2000',
            'manager_comment' => 'nullable|string|max:2000',
        ]);

        $validated['manager_id'] = Auth::id();
        $validated['status'] = 'draft';

        TutorAssessment::create($validated);

        return redirect()
            ->route('manager.assessments.index')
            ->with('success', 'Assessment created successfully.');
    }

    /**
     * Display the specified assessment.
     */
    public function show(TutorAssessment $assessment)
    {
        $assessment->load(['tutor', 'manager', 'director']);

        return view('manager.assessments.show', compact('assessment'));
    }

    /**
     * Show form for editing the assessment.
     */
    public function edit(TutorAssessment $assessment)
    {
        // Only allow editing drafts and pending assessments
        if (!in_array($assessment->status, ['draft', 'submitted'])) {
            return redirect()
                ->route('manager.assessments.show', $assessment)
                ->with('error', 'This assessment cannot be edited.');
        }

        $tutors = Tutor::where('status', 'active')->orderBy('first_name')->get();

        return view('manager.assessments.edit', compact('assessment', 'tutors'));
    }

    /**
     * Update the specified assessment.
     */
    public function update(Request $request, TutorAssessment $assessment)
    {
        // Only allow updating drafts
        if (!in_array($assessment->status, ['draft', 'submitted'])) {
            return redirect()
                ->route('manager.assessments.show', $assessment)
                ->with('error', 'This assessment cannot be edited.');
        }

        $validated = $request->validate([
            'performance_score' => 'nullable|integer|min:0|max:100',
            'professionalism_rating' => 'nullable|integer|min:1|max:5',
            'communication_rating' => 'nullable|integer|min:1|max:5',
            'punctuality_rating' => 'nullable|integer|min:1|max:5',
            'strengths' => 'nullable|string|max:2000',
            'weaknesses' => 'nullable|string|max:2000',
            'recommendations' => 'nullable|string|max:2000',
            'manager_comment' => 'nullable|string|max:2000',
        ]);

        $assessment->update($validated);

        return redirect()
            ->route('manager.assessments.show', $assessment)
            ->with('success', 'Assessment updated successfully.');
    }

    /**
     * Submit assessment for director approval.
     */
    public function submit(TutorAssessment $assessment)
    {
        if ($assessment->status !== 'draft') {
            return redirect()
                ->route('manager.assessments.index')
                ->with('error', 'This assessment has already been submitted.');
        }

        DB::transaction(function () use ($assessment) {
            $assessment->update([
                'status' => 'approved-by-manager',
                'approved_by_manager_at' => now(),
            ]);

            // TODO: Notify director
        });

        return redirect()
            ->route('manager.assessments.index')
            ->with('success', 'Assessment submitted for director approval.');
    }

    /**
     * Add manager comment to an assessment.
     */
    public function comment(Request $request, TutorAssessment $assessment)
    {
        $request->validate([
            'manager_comment' => 'required|string|max:2000',
        ]);

        $assessment->update([
            'manager_comment' => $request->manager_comment,
        ]);

        return redirect()
            ->route('manager.assessments.show', $assessment)
            ->with('success', 'Comment added successfully.');
    }
}
