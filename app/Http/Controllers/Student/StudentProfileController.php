<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentProfileController extends Controller
{
    /**
     * Display the student's own profile.
     * For student users viewing their own profile.
     */
    public function index()
    {
        $user = Auth::user();

        // Find the student record by email
        $student = Student::where('email', $user->email)->firstOrFail();

        // Authorize that the user can view this student
        $this->authorize('view', $student);

        return view('student.profile.index', compact('student'));
    }

    /**
     * Display a specific student's profile.
     * For parents viewing their child's profile.
     */
    public function show(Student $student)
    {
        // Authorize that the user can view this student
        $this->authorize('view', $student);

        return view('student.profile.show', compact('student'));
    }
}
