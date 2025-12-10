<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TutorAssessment;
use App\Models\Tutor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAssessmentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->hasRole('admin')) {
                abort(403, 'Unauthorized access.');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of director-approved assessments.
     */
    public function index(Request $request)
    {
        // Admin sees ONLY director-approved assessments
        $query = TutorAssessment::with(['tutor', 'manager', 'director'])
            ->where('status', 'approved-by-director');

        // Filter by tutor
        if ($request->filled('tutor_id')) {
            $query->where('tutor_id', $request->tutor_id);
        }

        // Filter by month
        if ($request->filled('month')) {
            $query->where('assessment_month', $request->month);
        }

        $assessments = $query->orderBy('created_at', 'desc')->paginate(20);

        $tutors = Tutor::where('status', 'active')->orderBy('first_name')->get();
        
        $months = TutorAssessment::where('status', 'approved-by-director')
            ->select('assessment_month')
            ->distinct()
            ->orderBy('assessment_month', 'desc')
            ->pluck('assessment_month');

        // Statistics
        $stats = [
            'total' => TutorAssessment::where('status', 'approved-by-director')->count(),
            'avg_score' => TutorAssessment::where('status', 'approved-by-director')
                ->whereNotNull('performance_score')
                ->avg('performance_score') ?? 0,
        ];

        return view('admin.assessments.index', compact('assessments', 'tutors', 'months', 'stats'));
    }

    /**
     * Display the specified assessment.
     */
    public function show(TutorAssessment $assessment)
    {
        // Admin can only view director-approved assessments
        if ($assessment->status !== 'approved-by-director') {
            abort(403, 'This assessment is not available for viewing.');
        }

        $assessment->load(['tutor', 'manager', 'director']);
        return view('admin.assessments.show', compact('assessment'));
    }

    /**
     * Print view of the assessment.
     */
    public function print(TutorAssessment $assessment)
    {
        if ($assessment->status !== 'approved-by-director') {
            abort(403, 'This assessment is not available for printing.');
        }

        $assessment->load(['tutor', 'manager', 'director']);
        return view('admin.assessments.print', compact('assessment'));
    }

    // Note: Admin CANNOT create, edit, approve, or comment on assessments per specification
}
