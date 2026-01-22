<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\TutorAssessment;
use App\Models\AssessmentCriteria;
use App\Models\AssessmentRating;
use App\Models\Tutor;
use App\Models\Student;
use App\Models\DirectorNotification;
use App\Models\User;
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
        $query = TutorAssessment::with(['tutor', 'manager', 'student', 'ratings.criteria']);

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
            'pending_review' => TutorAssessment::where('status', 'pending_review')->count(),
            'awaiting_director' => TutorAssessment::whereIn('status', ['pending_review', 'approved-by-manager'])->count(),
            'completed' => TutorAssessment::where('status', 'approved-by-director')->count(),
        ];

        // Get active tutors for filter
        $tutors = Tutor::where('status', 'active')->orderBy('first_name')->get();

        // Get active students with their tutors for the form
        $students = Student::with('tutor')
            ->where('status', 'active')
            ->orderBy('first_name')
            ->get();

        // Get assessment criteria from database
        $criteria = AssessmentCriteria::active()->ordered()->get();

        return view('manager.assessments.index', compact('assessments', 'stats', 'tutors', 'students', 'criteria'));
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
        try {
            $validated = $request->validate([
                'tutor_id' => 'required|exists:tutors,id',
                'student_id' => 'nullable|exists:students,id',
                'assessment_month' => 'required|string|max:50',
                'class_date' => 'nullable|date',
                'week' => 'nullable|integer|min:1|max:53',
                'year' => 'nullable|integer',
                'performance_score' => 'nullable|integer|min:0|max:100',
                'professionalism_rating' => 'nullable|integer|min:1|max:5',
                'communication_rating' => 'nullable|integer|min:1|max:5',
                'punctuality_rating' => 'nullable|integer|min:1|max:5',
                'strengths' => 'nullable|string|max:2000',
                'weaknesses' => 'nullable|string|max:2000',
                'recommendations' => 'nullable|string|max:2000',
                'manager_comment' => 'nullable|string|max:2000',
                'session' => 'nullable|integer|min:1|max:3',
                'criteria_assessed' => 'nullable|array',
                'criteria_ratings' => 'nullable|array',
            ]);

            $validated['manager_id'] = Auth::id();
            $validated['status'] = 'draft';

            // Extract ratings for separate processing
            $criteriaRatings = $validated['criteria_ratings'] ?? [];
            $criteriaAssessed = $validated['criteria_assessed'] ?? [];

            // Store criteria as JSON for legacy support
            if (isset($validated['criteria_assessed'])) {
                $validated['criteria_assessed'] = $criteriaAssessed;
            }
            if (isset($validated['criteria_ratings'])) {
                $validated['criteria_ratings'] = $criteriaRatings;
            }

            DB::transaction(function () use ($validated, $criteriaRatings) {
                $assessment = TutorAssessment::create($validated);

                // Create individual rating records (if new tables exist)
                if (!empty($criteriaRatings)) {
                    try {
                        $criteriaMap = AssessmentCriteria::active()->pluck('id', 'code');

                        foreach ($criteriaRatings as $criteriaCode => $rating) {
                            if (isset($criteriaMap[$criteriaCode]) && !empty($rating)) {
                                AssessmentRating::create([
                                    'assessment_id' => $assessment->id,
                                    'criteria_id' => $criteriaMap[$criteriaCode],
                                    'rating' => $rating,
                                    'score' => function_exists('ratingScore') ? ratingScore($rating) : 0,
                                ]);
                            }
                        }
                    } catch (\Exception $e) {
                        // If new tables don't exist, ratings are still stored in JSON fields
                        \Log::warning('Could not create assessment ratings: ' . $e->getMessage());
                    }
                }
            });

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Assessment created successfully.']);
            }

            return redirect()
                ->route('manager.assessments.index')
                ->with('success', 'Assessment created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Assessment creation failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'data' => $request->all()
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create assessment: ' . $e->getMessage()
                ], 500);
            }

            return redirect()
                ->back()
                ->with('error', 'Failed to create assessment: ' . $e->getMessage())
                ->withInput();
        }
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
        try {
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
                'criteria_assessed' => 'nullable|array',
                'criteria_ratings' => 'nullable|array',
            ]);

            $assessment->update($validated);

            return redirect()
                ->route('manager.assessments.show', $assessment)
                ->with('success', 'Assessment updated successfully.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('Error updating assessment: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'user' => Auth::id(),
                'assessment_id' => $assessment->id
            ]);

            return redirect()
                ->back()
                ->with('error', 'An error occurred while updating the assessment. Please try again.')
                ->withInput();
        }
    }

    /**
     * Mark assessment as complete (ready for director review).
     */
    public function markComplete(TutorAssessment $assessment)
    {
        try {
            if ($assessment->status !== 'draft') {
                return redirect()
                    ->route('manager.assessments.index')
                    ->with('error', 'Only draft assessments can be marked complete.');
            }

            // Verify all selected criteria have ratings
            $criteriaRatings = $assessment->criteria_ratings ?? [];
            $criteriaAssessed = $assessment->criteria_assessed ?? [];

            if (empty($criteriaAssessed)) {
                return redirect()
                    ->route('manager.assessments.edit', $assessment)
                    ->with('error', 'Please select and rate at least one criteria before marking complete.');
            }

            DB::transaction(function () use ($assessment) {
                $assessment->update([
                    'status' => 'pending_review',
                    'approved_by_manager_at' => now(),
                ]);

                // Notify all directors
                $directors = User::whereHas('roles', function($q) {
                    $q->where('name', 'director');
                })->get();

                $tutorName = $assessment->tutor ? ($assessment->tutor->first_name . ' ' . $assessment->tutor->last_name) : 'Unknown Tutor';
                $studentName = $assessment->student ? ($assessment->student->first_name . ' ' . $assessment->student->last_name) : '';

                foreach ($directors as $director) {
                    DirectorNotification::create([
                        'user_id' => $director->id,
                        'title' => 'Assessment Ready for Review',
                        'body' => "Assessment for {$tutorName}" . ($studentName ? " (Student: {$studentName})" : "") . " - Week {$assessment->week} is pending your review.",
                        'type' => 'assessment',
                        'is_read' => false,
                        'meta' => [
                            'assessment_id' => $assessment->id,
                            'link' => route('director.assessments.show', $assessment->id),
                        ],
                    ]);
                }
            });

            return redirect()
                ->route('manager.assessments.index')
                ->with('success', 'Assessment marked complete and sent for director review.');

        } catch (\Exception $e) {
            \Log::error('Error marking assessment complete: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'user' => Auth::id(),
                'assessment_id' => $assessment->id
            ]);

            return redirect()
                ->back()
                ->with('error', 'An error occurred while marking the assessment complete. Please try again or contact support if the problem persists.');
        }
    }

    /**
     * Submit assessment for director approval (legacy method).
     */
    public function submit(TutorAssessment $assessment)
    {
        return $this->markComplete($assessment);
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

    /**
     * Delete a draft assessment.
     */
    public function destroy(TutorAssessment $assessment)
    {
        // Only allow deleting draft assessments
        if ($assessment->status !== 'draft') {
            return redirect()
                ->route('manager.assessments.index')
                ->with('error', 'Only draft assessments can be deleted.');
        }

        // Ensure this manager created the assessment
        if ($assessment->manager_id !== Auth::id()) {
            abort(403, 'Unauthorized to delete this assessment.');
        }

        $assessment->delete();

        return redirect()
            ->route('manager.assessments.index')
            ->with('success', 'Draft assessment deleted successfully.');
    }
}
