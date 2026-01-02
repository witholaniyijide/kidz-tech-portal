<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\TutorAssessment;
use App\Models\AssessmentCriteria;
use App\Models\PenaltyTransaction;
use App\Models\Student;
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
        $query = TutorAssessment::where('tutor_id', $tutor->id)
            ->where('status', 'approved-by-director')
            ->with(['manager', 'director', 'student', 'ratings.criteria', 'directorAction']);

        // Filter by year
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        // Filter by month
        if ($request->filled('month')) {
            $query->whereMonth('class_date', $request->month);
        }

        // Filter by student
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        $assessments = $query->orderBy('class_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12)
            ->appends($request->except('page'));

        // Get available years for filter
        $years = TutorAssessment::where('tutor_id', $tutor->id)
            ->where('status', 'approved-by-director')
            ->whereNotNull('year')
            ->select('year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Get students for filter
        $students = Student::where('tutor_id', $tutor->id)
            ->where('status', 'active')
            ->orderBy('first_name')
            ->get();

        // Calculate stats
        $stats = $this->calculateStats($tutor->id);

        // Get assessment criteria
        $criteria = AssessmentCriteria::active()->ordered()->get();

        return view('tutor.performance.index', compact('assessments', 'years', 'students', 'stats', 'criteria'));
    }

    /**
     * Display the specified assessment as a performance report card.
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

        $assessment->load(['manager', 'director', 'student', 'ratings.criteria', 'directorAction']);

        // Get criteria list
        $criteriaList = AssessmentCriteria::active()->ordered()->get();

        // Calculate overall score
        $overallScore = $assessment->calculateOverallScore();
        $overallInfo = getEmojiAndLabel($overallScore);

        // Get strengths and weaknesses
        $strengths = $assessment->strengths_list;
        $weaknesses = $assessment->weaknesses_list;
        $strengthSummary = getStrengthSummary($strengths);
        $weaknessSummary = getWeaknessSummary($weaknesses);

        // Get previous and next assessments for navigation
        $previousAssessment = TutorAssessment::where('tutor_id', $tutor->id)
            ->where('status', 'approved-by-director')
            ->where('id', '<', $assessment->id)
            ->orderBy('id', 'desc')
            ->first();

        $nextAssessment = TutorAssessment::where('tutor_id', $tutor->id)
            ->where('status', 'approved-by-director')
            ->where('id', '>', $assessment->id)
            ->orderBy('id', 'asc')
            ->first();

        return view('tutor.performance.show', compact(
            'assessment',
            'criteriaList',
            'overallScore',
            'overallInfo',
            'strengths',
            'weaknesses',
            'strengthSummary',
            'weaknessSummary',
            'previousAssessment',
            'nextAssessment'
        ));
    }

    /**
     * Display the full report card view (printable).
     */
    public function reportCard(TutorAssessment $assessment)
    {
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            abort(403, 'You do not have a tutor profile.');
        }

        if ($assessment->tutor_id !== $tutor->id) {
            abort(403, 'Unauthorized access to this assessment.');
        }

        if ($assessment->status !== 'approved-by-director') {
            abort(404, 'Assessment not found.');
        }

        $assessment->load(['manager', 'director', 'student', 'ratings.criteria', 'directorAction']);

        $criteriaList = AssessmentCriteria::active()->ordered()->get();
        $overallScore = $assessment->calculateOverallScore();
        $overallInfo = getEmojiAndLabel($overallScore);
        $strengths = $assessment->strengths_list;
        $weaknesses = $assessment->weaknesses_list;
        $strengthSummary = getStrengthSummary($strengths);
        $weaknessSummary = getWeaknessSummary($weaknesses);

        // Get total sessions for this student/tutor combo in this period
        $totalSessions = TutorAssessment::where('tutor_id', $tutor->id)
            ->where('student_id', $assessment->student_id)
            ->where('status', 'approved-by-director')
            ->where('year', $assessment->year)
            ->whereMonth('class_date', $assessment->class_date?->month ?? date('m'))
            ->count();

        return view('tutor.performance.report-card', compact(
            'assessment',
            'criteriaList',
            'overallScore',
            'overallInfo',
            'strengths',
            'weaknesses',
            'strengthSummary',
            'weaknessSummary',
            'totalSessions'
        ));
    }

    /**
     * Calculate performance statistics for the tutor.
     */
    private function calculateStats($tutorId)
    {
        $assessments = TutorAssessment::where('tutor_id', $tutorId)
            ->where('status', 'approved-by-director')
            ->with(['ratings', 'directorAction'])
            ->get();

        if ($assessments->isEmpty()) {
            return [
                'total_assessments' => 0,
                'average_score' => 0,
                'this_month_count' => 0,
                'total_penalties' => 0,
                'latest_score' => null,
                'score_trend' => null,
            ];
        }

        // Calculate average score from ratings
        $totalScores = 0;
        $scoreCount = 0;
        foreach ($assessments as $assessment) {
            $score = $assessment->calculateOverallScore();
            if ($score > 0) {
                $totalScores += $score;
                $scoreCount++;
            }
        }
        $averageScore = $scoreCount > 0 ? round($totalScores / $scoreCount, 1) : 0;

        // Count this month's assessments
        $thisMonthCount = $assessments->filter(function ($a) {
            return $a->class_date && $a->class_date->isCurrentMonth();
        })->count();

        // Calculate total penalties
        $totalPenalties = $assessments->sum(function ($a) {
            return $a->directorAction?->penalty_amount ?? 0;
        });

        // Get latest assessment score
        $latestAssessment = $assessments->sortByDesc('created_at')->first();
        $latestScore = $latestAssessment ? $latestAssessment->calculateOverallScore() : null;

        // Calculate trend
        $scoreTrend = null;
        $previousAssessment = $assessments->sortByDesc('created_at')->skip(1)->first();
        if ($previousAssessment && $latestAssessment) {
            $latestScoreCalc = $latestAssessment->calculateOverallScore();
            $previousScoreCalc = $previousAssessment->calculateOverallScore();
            $diff = $latestScoreCalc - $previousScoreCalc;
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
            'average_score' => $averageScore,
            'this_month_count' => $thisMonthCount,
            'total_penalties' => $totalPenalties,
            'latest_score' => $latestScore,
            'score_trend' => $scoreTrend,
        ];
    }
}
