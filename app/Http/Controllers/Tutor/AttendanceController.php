<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tutor\StoreAttendanceRequest;
use App\Models\AttendanceRecord;
use App\Models\Student;
use App\Models\Tutor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display a listing of attendance records.
     */
    public function index(Request $request)
    {
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            abort(403, 'You do not have a tutor profile.');
        }

        $tab = $request->get('tab', 'my-students');
        $month = $request->get('month', now()->format('Y-m'));
        $status = $request->get('status');

        // Get student IDs assigned to this tutor
        $myStudentIds = $tutor->students()->pluck('id')->toArray();

        // Base query for this tutor's attendance
        $query = AttendanceRecord::where('tutor_id', $tutor->id)
            ->with('student');

        // Filter by tab
        if ($tab === 'stand-in') {
            // Stand-in: students NOT assigned to this tutor
            $query->whereNotIn('student_id', $myStudentIds);
        } else {
            // My Students: only assigned students
            $query->whereIn('student_id', $myStudentIds);
        }

        // Filter by month
        if ($month) {
            $startDate = Carbon::parse($month . '-01')->startOfMonth();
            $endDate = Carbon::parse($month . '-01')->endOfMonth();
            $query->whereBetween('class_date', [$startDate, $endDate]);
        }

        // Filter by status
        if ($status) {
            $query->where('status', $status);
        }

        $attendanceRecords = $query->orderBy('class_date', 'desc')
            ->orderBy('class_time', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Get stats
        $stats = [
            'total' => AttendanceRecord::where('tutor_id', $tutor->id)
                ->whereIn('student_id', $myStudentIds)
                ->whereMonth('class_date', now()->month)
                ->whereYear('class_date', now()->year)
                ->count(),
            'approved' => AttendanceRecord::where('tutor_id', $tutor->id)
                ->whereIn('student_id', $myStudentIds)
                ->where('status', 'approved')
                ->whereMonth('class_date', now()->month)
                ->whereYear('class_date', now()->year)
                ->count(),
            'pending' => AttendanceRecord::where('tutor_id', $tutor->id)
                ->whereIn('student_id', $myStudentIds)
                ->where('status', 'pending')
                ->count(),
            'standin_count' => AttendanceRecord::where('tutor_id', $tutor->id)
                ->whereNotIn('student_id', $myStudentIds)
                ->whereMonth('class_date', now()->month)
                ->whereYear('class_date', now()->year)
                ->count(),
        ];

        return view('tutor.attendance.index', compact(
            'attendanceRecords',
            'tab',
            'month',
            'status',
            'stats'
        ));
    }

    /**
     * Show the form for creating a new attendance record.
     */
    public function create(Request $request)
    {
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            abort(403, 'You do not have a tutor profile.');
        }

        $isStandIn = $request->get('standin', false);

        // Get students assigned to this tutor
        $myStudents = $tutor->students()->active()->get();

        // For stand-in, get all active students NOT assigned to this tutor
        $standInStudents = collect();
        if ($isStandIn) {
            $myStudentIds = $myStudents->pluck('id')->toArray();
            $standInStudents = Student::where('status', 'active')
                ->whereNotIn('id', $myStudentIds)
                ->with('tutor')
                ->orderBy('first_name')
                ->get();
        }

        return view('tutor.attendance.create', compact('myStudents', 'standInStudents', 'isStandIn'));
    }

    /**
     * Store a newly created attendance record in storage.
     */
    public function store(StoreAttendanceRequest $request)
    {
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            abort(403, 'You do not have a tutor profile.');
        }

        $student = Student::findOrFail($request->student_id);
        $isStandIn = $request->boolean('is_stand_in', false);

        // Check if this is a stand-in submission
        $myStudentIds = $tutor->students()->pluck('id')->toArray();
        $isActuallyStandIn = !in_array($student->id, $myStudentIds);

        // If NOT a stand-in and student doesn't belong to tutor, reject
        if (!$isActuallyStandIn && $student->tutor_id !== $tutor->id) {
            abort(403, 'You can only submit attendance for your assigned students.');
        }

        // Determine if submission is late
        // Policy: Class typically ends at 6pm, tutor has 6-hour grace period (until midnight)
        // If submitting after midnight of the class date, it's late
        $classDate = Carbon::parse($request->class_date);
        $deadline = $classDate->copy()->endOfDay(); // Midnight of class date
        $isLate = now()->gt($deadline);

        // Create attendance record
        $attendance = AttendanceRecord::create([
            'student_id' => $request->student_id,
            'tutor_id' => $tutor->id,
            'class_date' => $request->class_date,
            'class_time' => $request->class_time,
            'duration_minutes' => $request->duration_minutes,
            'topic' => $request->topic,
            'notes' => $request->notes,
            'is_stand_in' => $isActuallyStandIn,
            'stand_in_reason' => $isActuallyStandIn ? $request->stand_in_reason : null,
            'status' => 'pending',
            'is_late' => $isLate,
        ]);

        $message = $isActuallyStandIn 
            ? 'Stand-in attendance submitted successfully! Awaiting manager approval.'
            : 'Attendance submitted successfully! Awaiting manager approval.';

        return redirect()
            ->route('tutor.attendance.show', $attendance)
            ->with('success', $message);
    }

    /**
     * Display the specified attendance record.
     */
    public function show(AttendanceRecord $attendance)
    {
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            abort(403, 'You do not have a tutor profile.');
        }

        // Verify attendance belongs to this tutor
        if ($attendance->tutor_id !== $tutor->id) {
            abort(403, 'Unauthorized access to this attendance record.');
        }

        // Load student with their assigned tutor for stand-in context
        $attendance->load(['student.tutor']);

        // Determine if this is a stand-in
        $myStudentIds = $tutor->students()->pluck('id')->toArray();
        $isStandIn = !in_array($attendance->student_id, $myStudentIds);

        return view('tutor.attendance.show', compact('attendance', 'isStandIn'));
    }
}
