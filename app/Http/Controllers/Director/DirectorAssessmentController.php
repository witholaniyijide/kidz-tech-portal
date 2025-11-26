<?php

namespace App\Http\Controllers\Director;

use App\Http\Controllers\Controller;
use App\Models\TutorAssessment;
use App\Models\Tutor;
use App\Services\DirectorApprovalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DirectorAssessmentController extends Controller
{
    protected DirectorApprovalService $approvalService;

    /**
     * Create a new controller instance.
     */
    public function __construct(DirectorApprovalService $approvalService)
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->hasRole('director') && !Auth::user()->hasRole('admin')) {
                abort(403, 'Unauthorized access.');
            }
            return $next($request);
        });

        $this->approvalService = $approvalService;
    }

    /**
     * Display a listing of assessments pending director approval.
     */
    public function index(Request $request)
    {
        // Authorize
        $this->authorize('viewAny', TutorAssessment::class);

        $query = TutorAssessment::with(['tutor', 'manager'])
            ->whereIn('status', ['submitted', 'approved-by-manager'])
            ->orderBy('created_at', 'desc');

        // Filter by tutor
        if ($request->filled('tutor_id')) {
            $query->where('tutor_id', $request->tutor_id);
        }

        // Filter by month
        if ($request->filled('month')) {
            $query->where('assessment_month', $request->month);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $assessments = $query->paginate(15);

        // Get unique months from assessments for filter
        $months = TutorAssessment::select('assessment_month')
            ->distinct()
            ->orderBy('assessment_month', 'desc')
            ->pluck('assessment_month');

        // Get all tutors for filter
        $tutors = Tutor::where('status', 'active')
            ->orderBy('first_name')
            ->get();

        return view('director.assessments.index', compact(
            'assessments',
            'months',
            'tutors'
        ));
    }

    /**
     * Display the specified assessment for director review.
     */
    public function show(TutorAssessment $assessment)
    {
        // Authorize
        $this->authorize('view', $assessment);

        // Load relationships
        $assessment->load(['tutor', 'manager', 'director']);

        return view('director.assessments.show', compact('assessment'));
    }

    /**
     * Approve the assessment (final approval).
     */
    public function approve(Request $request, TutorAssessment $assessment)
    {
        // Authorize
        $this->authorize('approve', $assessment);

        // Validate the request
        $validated = $request->validate([
            'director_comment' => 'nullable|string|max:2000',
        ]);

        // Check if assessment can be approved
        if (!$assessment->canDirectorApprove()) {
            return redirect()
                ->route('director.assessments.index')
                ->with('error', 'This assessment cannot be approved at this time.');
        }

        try {
            // Use the service to approve the assessment
            $this->approvalService->approveTutorAssessment(
                $assessment,
                Auth::user(),
                $validated['director_comment'] ?? null
            );

            return redirect()
                ->route('director.assessments.index')
                ->with('success', 'Assessment has been approved successfully. Notifications sent to tutor and manager.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to approve assessment: ' . $e->getMessage());
        }
    }

    /**
     * Add a comment to the assessment.
     */
    public function comment(Request $request, TutorAssessment $assessment)
    {
        // Authorize
        $this->authorize('addComment', $assessment);

        // Validate the request
        $validated = $request->validate([
            'comment' => 'required|string|max:2000',
        ]);

        // Update the assessment with the director comment
        DB::transaction(function () use ($assessment, $validated) {
            $existingComment = $assessment->director_comment ?? '';
            $newComment = $existingComment
                ? $existingComment . "\n\n---\n\n" . $validated['comment']
                : $validated['comment'];

            $assessment->update([
                'director_comment' => $newComment,
            ]);

            // Log the action
            $this->approvalService->logDirectorAction(
                Auth::user(),
                'commented_on_assessment',
                TutorAssessment::class,
                $assessment->id
            );
        });

        return redirect()
            ->back()
            ->with('success', 'Comment added successfully.');
    }
}
