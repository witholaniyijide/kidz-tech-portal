<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tutor\StoreAttendanceRequest;
use App\Models\AttendanceRecord;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * Show the form for creating a new attendance record.
     */
    public function create()
    {
        // Get the authenticated tutor
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            abort(403, 'You do not have a tutor profile.');
        }

        // Get students assigned to this tutor
        $students = $tutor->students()->active()->get();

        return view('tutor.attendance.create', compact('students'));
    }

    /**
     * Store a newly created attendance record in storage.
     */
    public function store(StoreAttendanceRequest $request)
    {
        // Get the authenticated tutor
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            abort(403, 'You do not have a tutor profile.');
        }

        // Verify student belongs to this tutor
        $student = Student::findOrFail($request->student_id);

        if ($student->tutor_id !== $tutor->id) {
            abort(403, 'You can only submit attendance for your assigned students.');
        }

        // Create attendance record
        $attendance = AttendanceRecord::create([
            'student_id' => $request->student_id,
            'tutor_id' => $tutor->id,
            'class_date' => $request->class_date,
            'class_time' => $request->class_time,
            'duration_minutes' => $request->duration_minutes,
            'topic' => $request->topic,
            'notes' => $request->notes,
            'status' => 'pending', // Manager will approve/reject
        ]);

        return redirect()
            ->route('tutor.attendance.show', $attendance)
            ->with('success', 'Attendance submitted successfully! Awaiting manager approval.');
    }

    /**
     * Display the specified attendance record.
     */
    public function show(AttendanceRecord $attendance)
    {
        // Get the authenticated tutor
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            abort(403, 'You do not have a tutor profile.');
        }

        // Verify attendance belongs to this tutor
        if ($attendance->tutor_id !== $tutor->id) {
            abort(403, 'Unauthorized access to this attendance record.');
        }

        return view('tutor.attendance.show', compact('attendance'));
    }
}
