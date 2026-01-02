<?php

namespace App\Http\Controllers\Director;

use App\Http\Controllers\Controller;
use App\Models\TutorAssessment;
use App\Models\AssessmentCriteria;
use App\Models\DirectorAction;
use App\Models\PenaltyTransaction;
use App\Models\Tutor;
use App\Models\TutorNotification;
use App\Models\ManagerNotification;
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

        // Get all assessments that are relevant to director
        $query = TutorAssessment::with(['tutor', 'manager', 'director', 'student', 'ratings.criteria', 'directorAction'])
            ->whereIn('status', ['pending_review', 'approved-by-manager', 'approved-by-director'])
            ->orderBy('created_at', 'desc');

        // Filter by tutor
        if ($request->filled('tutor_id')) {
            $query->where('tutor_id', $request->tutor_id);
        }

        // Filter by month
        if ($request->filled('month')) {
            $query->where('assessment_month', $request->month);
        }

        // Filter by year
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        // Filter by week
        if ($request->filled('week')) {
            $query->where('week', $request->week);
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

        // Get years for filter
        $years = TutorAssessment::select('year')
            ->distinct()
            ->whereNotNull('year')
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Get assessment criteria
        $criteria = AssessmentCriteria::active()->ordered()->get();

        // Calculate statistics
        $stats = [
            'total' => TutorAssessment::count(),
            'pending' => TutorAssessment::whereIn('status', ['pending_review', 'approved-by-manager'])->count(),
            'completed' => TutorAssessment::where('status', 'approved-by-director')->count(),
            'awaiting_director' => TutorAssessment::whereIn('status', ['pending_review', 'approved-by-manager'])->count(),
            'avg_score' => TutorAssessment::where('status', 'approved-by-director')
                ->whereNotNull('performance_score')
                ->avg('performance_score') ?? 0,
        ];

        // Prepare chart data
        $chartData = $this->prepareChartData($assessments, $tutors);

        return view('director.assessments.index', compact(
            'assessments',
            'months',
            'years',
            'tutors',
            'criteria',
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
        $assessment->load(['tutor', 'manager', 'director', 'student']);

        return view('director.assessments.show', compact('assessment'));
    }

    /**
     * Approve the assessment with penalty.
     */
    public function approveWithPenalty(Request $request, TutorAssessment $assessment)
    {
        // Authorize
        $this->authorize('approve', $assessment);

        // Validate the request
        $validated = $request->validate([
            'director_comment' => 'nullable|string|max:2000',
            'penalty_amount' => 'required|numeric|min:0',
            'suggested_penalty' => 'nullable|numeric|min:0',
        ]);

        // Check if assessment can be approved
        if (!$assessment->canDirectorApprove()) {
            return redirect()
                ->route('director.assessments.index')
                ->with('error', 'This assessment cannot be approved at this time.');
        }

        try {
            DB::transaction(function () use ($assessment, $validated) {
                // Update assessment status
                $assessment->update([
                    'status' => 'approved-by-director',
                    'director_id' => Auth::id(),
                    'director_comment' => $validated['director_comment'] ?? null,
                    'approved_by_director_at' => now(),
                ]);

                // Create director action record
                $directorAction = DirectorAction::create([
                    'assessment_id' => $assessment->id,
                    'director_id' => Auth::id(),
                    'action_type' => 'approve',
                    'penalty_amount' => $validated['penalty_amount'],
                    'suggested_penalty' => $validated['suggested_penalty'] ?? $validated['penalty_amount'],
                    'director_comment' => $validated['director_comment'] ?? null,
                    'action_date' => now(),
                ]);

                // Create penalty transaction if penalty > 0
                if ($validated['penalty_amount'] > 0) {
                    PenaltyTransaction::create([
                        'tutor_id' => $assessment->tutor_id,
                        'director_action_id' => $directorAction->id,
                        'amount' => $validated['penalty_amount'],
                        'reason' => 'Assessment penalty for Week ' . $assessment->week,
                        'week_number' => $assessment->week ?? 1,
                        'year' => $assessment->year ?? date('Y'),
                        'month' => $assessment->class_date ? $assessment->class_date->month : date('m'),
                        'transaction_date' => now(),
                    ]);
                }

                // Notify tutor
                $this->notifyTutor($assessment, $validated['penalty_amount'], $validated['director_comment'] ?? null);

                // Log the action
                $this->approvalService->logDirectorAction(
                    Auth::user(),
                    'approved_assessment_with_penalty',
                    TutorAssessment::class,
                    $assessment->id
                );
            });

            return redirect()
                ->route('director.assessments.index')
                ->with('success', 'Assessment approved with penalty of ₦' . number_format($validated['penalty_amount'], 2));
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to approve assessment: ' . $e->getMessage());
        }
    }

    /**
     * Approve the assessment without penalty.
     */
    public function approveWithoutPenalty(Request $request, TutorAssessment $assessment)
    {
        // Authorize
        $this->authorize('approve', $assessment);

        // Validate the request
        $validated = $request->validate([
            'director_comment' => 'nullable|string|max:2000',
            'suggested_penalty' => 'nullable|numeric|min:0',
        ]);

        // Check if assessment can be approved
        if (!$assessment->canDirectorApprove()) {
            return redirect()
                ->route('director.assessments.index')
                ->with('error', 'This assessment cannot be approved at this time.');
        }

        try {
            DB::transaction(function () use ($assessment, $validated) {
                // Update assessment status
                $assessment->update([
                    'status' => 'approved-by-director',
                    'director_id' => Auth::id(),
                    'director_comment' => $validated['director_comment'] ?? null,
                    'approved_by_director_at' => now(),
                ]);

                // Create director action record
                DirectorAction::create([
                    'assessment_id' => $assessment->id,
                    'director_id' => Auth::id(),
                    'action_type' => 'approve_no_penalty',
                    'penalty_amount' => 0,
                    'suggested_penalty' => $validated['suggested_penalty'] ?? 0,
                    'director_comment' => $validated['director_comment'] ?? null,
                    'action_date' => now(),
                ]);

                // Notify tutor
                $this->notifyTutor($assessment, 0, $validated['director_comment'] ?? null);

                // Log the action
                $this->approvalService->logDirectorAction(
                    Auth::user(),
                    'approved_assessment_no_penalty',
                    TutorAssessment::class,
                    $assessment->id
                );
            });

            return redirect()
                ->route('director.assessments.index')
                ->with('success', 'Assessment approved without penalty.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to approve assessment: ' . $e->getMessage());
        }
    }

    /**
     * Approve the assessment (legacy method - redirects to appropriate action).
     */
    public function approve(Request $request, TutorAssessment $assessment)
    {
        // Default to approve without penalty for legacy calls
        return $this->approveWithoutPenalty($request, $assessment);
    }

    /**
     * Notify tutor about approved assessment.
     */
    protected function notifyTutor(TutorAssessment $assessment, float $penaltyAmount, ?string $comment)
    {
        $tutorName = $assessment->tutor ? ($assessment->tutor->first_name . ' ' . $assessment->tutor->last_name) : 'Tutor';
        $studentName = $assessment->student ? ($assessment->student->first_name . ' ' . $assessment->student->last_name) : '';

        $title = 'Assessment Approved';
        $body = "Your assessment for Week {$assessment->week}" . ($studentName ? " (Student: {$studentName})" : "") . " has been reviewed.";

        if ($penaltyAmount > 0) {
            $body .= " Penalty applied: ₦" . number_format($penaltyAmount, 2);
        }

        if ($comment) {
            $body .= "\n\nDirector's comment: " . $comment;
        }

        // Create tutor notification
        TutorNotification::create([
            'tutor_id' => $assessment->tutor_id,
            'title' => $title,
            'body' => $body,
            'type' => 'assessment',
            'is_read' => false,
            'meta' => [
                'assessment_id' => $assessment->id,
                'penalty_amount' => $penaltyAmount,
            ],
        ]);

        // Notify manager who created the assessment
        if ($assessment->manager_id) {
            ManagerNotification::create([
                'user_id' => $assessment->manager_id,
                'title' => 'Assessment Approved by Director',
                'body' => "Assessment for {$tutorName}" . ($studentName ? " (Student: {$studentName})" : "") . " - Week {$assessment->week} has been approved.",
                'type' => 'assessment',
                'is_read' => false,
                'meta' => [
                    'assessment_id' => $assessment->id,
                    'link' => route('manager.assessments.show', $assessment->id),
                ],
            ]);
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
