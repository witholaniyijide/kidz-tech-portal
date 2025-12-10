<?php

namespace App\Http\Controllers\Director;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\DirectorActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DirectorAttendanceController extends Controller
{
    /**
     * Display a listing of attendance records.
     */
    public function index(Request $request)
    {
        $query = AttendanceRecord::with(['student', 'tutor']);

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('class_date', $request->date);
        }

        // Filter by approval status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by student
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        // Filter by tutor
        if ($request->filled('tutor_id')) {
            $query->where('tutor_id', $request->tutor_id);
        }

        // Get counts for cards
        $totalAttendance = AttendanceRecord::count();
        $approvedAttendance = AttendanceRecord::where('status', 'approved')->count();
        $pendingAttendance = AttendanceRecord::where('status', 'pending')->count();
        $lateSubmissions = AttendanceRecord::where('is_late_submission', true)->count();

        // Get today's attendance
        $todayAttendance = AttendanceRecord::whereDate('class_date', today())
            ->with(['student', 'tutor'])
            ->get();

        $attendance = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get students and tutors for filters
        $students = Student::where('status', 'active')->get();
        $tutors = Tutor::where('status', 'active')->get();

        return view('director.attendance.index', compact(
            'attendance',
            'totalAttendance',
            'approvedAttendance',
            'pendingAttendance',
            'lateSubmissions',
            'todayAttendance',
            'students',
            'tutors'
        ));
    }

    /**
     * Display the specified attendance record.
     */
    public function show(AttendanceRecord $attendance)
    {
        $attendance->load(['student', 'tutor']);
        
        return view('director.attendance.show', compact('attendance'));
    }

    /**
     * Approve an attendance record.
     */
    public function approve(Request $request, AttendanceRecord $attendance)
    {
        $request->validate([
            'comment' => 'nullable|string|max:500',
        ]);

        try {
            $attendance->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'approval_comment' => $request->comment,
            ]);

            // Log director activity
            DirectorActivityLog::create([
                'director_id' => Auth::id(),
                'action_type' => 'attendance_approved',
                'model_type' => 'AttendanceRecord',
                'model_id' => $attendance->id,
                'payload' => json_encode([
                    'student_id' => $attendance->student_id,
                    'class_date' => $attendance->class_date,
                    'comment' => $request->comment,
                ]),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return back()->with('success', 'Attendance approved successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to approve attendance: ' . $e->getMessage());
        }
    }

    /**
     * Store a new attendance record (submitted by Director).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'tutor_id' => 'required|exists:tutors,id',
            'class_date' => 'required|date',
            'attendance_status' => 'required|in:present,absent,late,excused',
            'class_start_time' => 'nullable|date_format:H:i',
            'class_end_time' => 'nullable|date_format:H:i',
            'topics_covered' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:500',
            'auto_approve' => 'nullable|boolean',
        ]);

        try {
            $status = $request->boolean('auto_approve') ? 'approved' : 'pending';
            
            $attendance = AttendanceRecord::create([
                'student_id' => $validated['student_id'],
                'tutor_id' => $validated['tutor_id'],
                'class_date' => $validated['class_date'],
                'status' => $status,
                'class_start_time' => $validated['class_start_time'] ?? null,
                'class_end_time' => $validated['class_end_time'] ?? null,
                'topics_covered' => $validated['topics_covered'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'submitted_by' => Auth::id(),
                'approved_by' => $request->boolean('auto_approve') ? Auth::id() : null,
                'approved_at' => $request->boolean('auto_approve') ? now() : null,
            ]);

            // Log director activity
            DirectorActivityLog::create([
                'director_id' => Auth::id(),
                'action_type' => 'attendance_submitted',
                'model_type' => 'AttendanceRecord',
                'model_id' => $attendance->id,
                'payload' => [
                    'description' => 'Attendance record submitted for student',
                    'student_id' => $validated['student_id'],
                    'class_date' => $validated['class_date'],
                    'auto_approved' => $request->boolean('auto_approve'),
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->route('director.attendance.index')
                ->with('success', 'Attendance record created successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to create attendance: ' . $e->getMessage());
        }
    }
}
