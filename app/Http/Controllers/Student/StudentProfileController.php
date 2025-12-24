<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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
        $student = Student::where('email', $user->email)->first();
        if (!$student) {
            $student = Student::where('user_id', $user->id)->first();
        }

        return view('student.profile.index', compact('user', 'student'));
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

    /**
     * Update the student's profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'current_password' => ['nullable', 'required_with:password', 'current_password'],
            'password' => ['nullable', 'min:8', 'confirmed'],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Also update student record if exists
        $student = Student::where('user_id', $user->id)->first();
        if (!$student) {
            $student = Student::where('email', $user->email)->first();
        }

        if ($student) {
            // Parse name into first and last name
            $nameParts = explode(' ', $request->name, 2);
            $student->first_name = $nameParts[0];
            $student->last_name = $nameParts[1] ?? '';
            $student->email = $request->email;
            $student->save();
        }

        return redirect()->route('student.profile.index')
            ->with('success', 'Profile updated successfully.');
    }
}
