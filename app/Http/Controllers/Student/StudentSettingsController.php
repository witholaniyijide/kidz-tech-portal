<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentSettingsController extends Controller
{
    /**
     * Display the student settings page (read-only).
     */
    public function index()
    {
        $user = Auth::user();

        // Find the student record by email
        $student = Student::where('email', $user->email)->firstOrFail();

        // Ensure this user can only view their own student record
        abort_if(
            $student->email !== $user->email,
            403,
            'Unauthorized access to student settings.'
        );

        // Load tutor relationship
        $student->load('tutor');

        // Calculate progress percentage
        $progressPercentage = $student->progressPercentage();

        return view('student.settings.index', compact('student', 'progressPercentage'));
    }
}
