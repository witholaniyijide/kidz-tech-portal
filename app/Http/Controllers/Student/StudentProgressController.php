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
}
