<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentProgressController extends Controller
{
    /**
     * Display all progress milestones for the authenticated student.
     */
    public function index()
    {
        $user = Auth::user();

        // Find the student record by email
        $student = Student::where('email', $user->email)->firstOrFail();

        // Get all progress items for this student
        $progressItems = $student->progress()
            ->orderBy('completed', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        // Authorize that the user can view progress items
        foreach ($progressItems as $progress) {
            $this->authorize('view', $progress);
        }

        return view('student.progress.index', compact('student', 'progressItems'));
    }

    /**
     * Display a specific progress milestone.
     */
    public function show(StudentProgress $milestone)
    {
        // Authorize that the user can view this progress item
        $this->authorize('view', $milestone);

        $student = $milestone->student;

        return view('student.progress.show', compact('milestone', 'student'));
    }

    /**
     * Mark a progress milestone as complete.
     * Only accessible by tutors and admins.
     */
    public function markComplete(Request $request, StudentProgress $progress)
    {
        // Authorize - only tutors and admins can mark milestones complete
        $this->authorize('update', $progress);

        // Validate
        $request->validate([
            'completed' => 'required|boolean',
        ]);

        // Update milestone
        $progress->completed = $request->completed;
        $progress->completed_at = $request->completed ? now() : null;
        $progress->save();

        // Recalculate student roadmap progress
        $student = $progress->student;
        $this->recalculateRoadmapProgress($student);

        return redirect()->back()->with('success', 'Milestone updated successfully.');
    }

    /**
     * Recalculate student roadmap progress percentage.
     */
    protected function recalculateRoadmapProgress(Student $student)
    {
        $totalMilestones = $student->progress()->count();

        if ($totalMilestones === 0) {
            $student->roadmap_progress = 0;
            $student->save();
            return;
        }

        $completedMilestones = $student->progress()->where('completed', true)->count();
        $progressPercentage = (int) (($completedMilestones / $totalMilestones) * 100);

        $student->roadmap_progress = $progressPercentage;
        $student->save();
    }
}
