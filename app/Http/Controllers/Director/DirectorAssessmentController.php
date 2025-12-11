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
     * Display a listing of assessments for director review.
     */
    public function index(Request $request)
    {
        // Authorize
        $this->authorize('viewAny', TutorAssessment::class);

        // Get all assessments that are relevant to director (manager-approved or director-approved)
        $query = TutorAssessment::with(['tutor', 'manager', 'director'])
            ->whereIn('status', ['approved-by-manager', 'approved-by-director'])
            ->orderBy('created_at', 'desc');

        // Filter by tutor
        if ($request->filled('tutor_id')) {
            $query->where('tutor_id', $request->tutor_id);
        }

        // Filter by month
        if ($request->filled('month')) {
            $query->where('assessment_month', $request->month);
        }

        // Get all assessments (not paginated for client-side filtering)
        $assessments = $query->get();

        // Get unique months from assessments for filter
        $months = TutorAssessment::select('assessment_month')
            ->distinct()
            ->orderBy('assessment_month', 'desc')
            ->pluck('assessment_month');

        // Get all tutors for filter
        $tutors = Tutor::where('status', 'active')
            ->orderBy('first_name')
            ->get();

        // Calculate statistics
        $stats = [
            'total' => TutorAssessment::count(),
            'pending' => TutorAssessment::where('status', 'approved-by-manager')->count(),
            'completed' => TutorAssessment::where('status', 'approved-by-director')->count(),
            'awaiting_director' => TutorAssessment::where('status', 'approved-by-manager')->count(),
            'avg_score' => TutorAssessment::where('status', 'approved-by-director')
                ->whereNotNull('performance_score')
                ->avg('performance_score') ?? 0,
        ];

        // Prepare chart data
        $chartData = $this->prepareChartData($assessments, $tutors);

        return view('director.assessments.index', compact(
            'assessments',
            'months',
            'tutors',
            'stats',
            'chartData'
        ));
    }

    /**
     * Prepare chart data for analytics.
     */
    protected function prepareChartData($assessments, $tutors)
    {
        // Performance trend by month
        $completedAssessments = $assessments->where('status', 'approved-by-director');
        
        $monthlyData = $completedAssessments
            ->groupBy('assessment_month')
            ->map(function ($group) {
                return round($group->avg('performance_score') ?? 0, 1);
            });

        // Tutor comparison
        $tutorScores = [];
        $tutorNames = [];
        foreach ($tutors->take(10) as $tutor) {
            $tutorNames[] = $tutor->first_name;
            $avgScore = $completedAssessments
                ->where('tutor_id', $tutor->id)
                ->avg('performance_score') ?? 0;
            $tutorScores[] = round($avgScore, 1);
        }

        // Criteria breakdown
        $criteriaScores = [
            round($completedAssessments->avg('professionalism_rating') ?? 0, 2),
            round($completedAssessments->avg('communication_rating') ?? 0, 2),
            round($completedAssessments->avg('punctuality_rating') ?? 0, 2),
            round(($completedAssessments->avg('performance_score') ?? 0) / 25, 2), // Convert to 1-5 scale
        ];

        return [
            'months' => $monthlyData->keys()->toArray(),
            'scores' => $monthlyData->values()->toArray(),
            'tutorNames' => $tutorNames,
            'tutorScores' => $tutorScores,
            'criteriaScores' => $criteriaScores,
        ];
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
     * Print/export assessment as report card.
     */
    public function print(TutorAssessment $assessment)
    {
        // Authorize
        $this->authorize('view', $assessment);

        // Load relationships
        $assessment->load(['tutor', 'manager', 'director']);

        return view('director.assessments.print', compact('assessment'));
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

    /**
     * Export assessments to CSV.
     */
    public function export()
    {
        $assessments = TutorAssessment::with(['tutor'])
            ->whereIn('status', ['approved-by-manager', 'approved-by-director'])
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'director-assessments-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($assessments) {
            $file = fopen('php://output', 'w');

            // Add headers
            fputcsv($file, ['Tutor', 'Month', 'Score', 'Status', 'Director Comment']);

            // Add data
            foreach ($assessments as $a) {
                fputcsv($file, [
                    ($a->tutor?->first_name ?? '') . ' ' . ($a->tutor?->last_name ?? ''),
                    $a->assessment_month ?? '',
                    $a->performance_score ?? 0,
                    $a->status ?? '',
                    $a->director_comment ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
