<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\TutorAssessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerformanceController extends Controller
{
    /**
     * Display a listing of the tutor's performance assessments.
     * Only shows assessments that have been approved by director (visible to tutor).
     */
    public function index(Request $request)
    {
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            abort(403, 'You do not have a tutor profile.');
        }

        // Only show assessments that are approved by director (final approval)
        // Tutors should not see draft, submitted, or manager-only approved assessments
        $query = TutorAssessment::where('tutor_id', $tutor->id)
            ->where('status', 'approved-by-director')
            ->with(['manager', 'director']);

        // Filter by year
        if ($request->filled('year')) {
            $query->whereYear('assessment_month', $request->year);
        }

        $assessments = $query->orderBy('assessment_month', 'desc')
            ->paginate(12)
            ->appends($request->except('page'));

        // Get available years for filter
        $years = TutorAssessment::where('tutor_id', $tutor->id)
            ->where('status', 'approved-by-director')
            ->selectRaw('YEAR(assessment_month) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Calculate stats
        $stats = $this->calculateStats($tutor->id);

        return view('tutor.performance.index', compact('assessments', 'years', 'stats'));
    }

    /**
     * Display the specified assessment.
     * IMPORTANT: Manager comments are hidden from tutor view.
     */
    public function show(TutorAssessment $assessment)
    {
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            abort(403, 'You do not have a tutor profile.');
        }

        // Verify this assessment belongs to the authenticated tutor
        if ($assessment->tutor_id !== $tutor->id) {
            abort(403, 'Unauthorized access to this assessment.');
        }

        // Only show approved-by-director assessments to tutors
        if ($assessment->status !== 'approved-by-director') {
            abort(404, 'Assessment not found.');
        }

        $assessment->load(['manager', 'director']);

        // Get previous and next assessments for navigation
        $previousAssessment = TutorAssessment::where('tutor_id', $tutor->id)
            ->where('status', 'approved-by-director')
            ->where('assessment_month', '<', $assessment->assessment_month)
            ->orderBy('assessment_month', 'desc')
            ->first();

        $nextAssessment = TutorAssessment::where('tutor_id', $tutor->id)
            ->where('status', 'approved-by-director')
            ->where('assessment_month', '>', $assessment->assessment_month)
            ->orderBy('assessment_month', 'asc')
            ->first();

        return view('tutor.performance.show', compact('assessment', 'previousAssessment', 'nextAssessment'));
    }

    /**
     * Calculate performance statistics for the tutor.
     */
    private function calculateStats($tutorId)
    {
        $assessments = TutorAssessment::where('tutor_id', $tutorId)
            ->where('status', 'approved-by-director')
            ->get();

        if ($assessments->isEmpty()) {
            return [
                'total_assessments' => 0,
                'average_score' => null,
                'average_professionalism' => null,
                'average_communication' => null,
                'average_punctuality' => null,
                'latest_score' => null,
                'score_trend' => null,
            ];
        }

        $latestAssessment = $assessments->sortByDesc('assessment_month')->first();
        $previousAssessment = $assessments->sortByDesc('assessment_month')->skip(1)->first();

        // Calculate trend
        $scoreTrend = null;
        if ($previousAssessment && $latestAssessment) {
            $diff = $latestAssessment->performance_score - $previousAssessment->performance_score;
            if ($diff > 0) {
                $scoreTrend = 'up';
            } elseif ($diff < 0) {
                $scoreTrend = 'down';
            } else {
                $scoreTrend = 'stable';
            }
        }

        return [
            'total_assessments' => $assessments->count(),
            'average_score' => round($assessments->avg('performance_score'), 1),
            'average_professionalism' => round($assessments->avg('professionalism_rating'), 1),
            'average_communication' => round($assessments->avg('communication_rating'), 1),
            'average_punctuality' => round($assessments->avg('punctuality_rating'), 1),
            'latest_score' => $latestAssessment->performance_score ?? null,
            'score_trend' => $scoreTrend,
        ];
    }
}
