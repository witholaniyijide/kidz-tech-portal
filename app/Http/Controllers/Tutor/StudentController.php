<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    /**
     * Display a listing of the tutor's students.
     */
    public function index()
    {
        // Get the authenticated tutor
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            abort(403, 'You do not have a tutor profile.');
        }

        // Get students assigned to this tutor
        $students = $tutor->students()
            ->with(['tutorReports' => function ($query) {
                $query->orderBy('created_at', 'desc')->take(3);
            }])
            ->orderBy('first_name')
            ->paginate(15);

        return view('tutor.students.index', compact('students'));
    }

    /**
     * Display the specified student.
     */
    public function show(Student $student)
    {
        // Get the authenticated tutor
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            abort(403, 'You do not have a tutor profile.');
        }

        // Verify student belongs to this tutor
        if ($student->tutor_id !== $tutor->id) {
            abort(403, 'Unauthorized access to this student.');
        }

        // Load student relationships
        $student->load([
            'tutorReports' => function ($query) {
                $query->orderBy('created_at', 'desc');
            },
            'attendanceRecords' => function ($query) {
                $query->orderBy('class_date', 'desc')->take(10);
            }
        ]);

        // Calculate attendance stats
        $totalClasses = $student->attendanceRecords->count();
        $presentCount = $student->attendanceRecords->where('status', 'present')->count();
        $attendanceRate = $totalClasses > 0 ? round(($presentCount / $totalClasses) * 100, 1) : 0;

        return view('tutor.students.show', compact('student', 'attendanceRate'));
    }
}
