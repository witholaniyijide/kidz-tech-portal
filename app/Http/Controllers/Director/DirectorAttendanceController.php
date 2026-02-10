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

        // Filter by date range (week/month presets)
        if ($request->filled('date_range')) {
            $now = now();
            switch ($request->date_range) {
                case 'today':
                    $query->whereDate('class_date', $now->toDateString());
                    break;
                case 'this_week':
                    $query->whereBetween('class_date', [
                        $now->startOfWeek()->toDateString(),
                        $now->copy()->endOfWeek()->toDateString()
                    ]);
                    break;
                case 'this_month':
                    $query->whereMonth('class_date', $now->month)
                          ->whereYear('class_date', $now->year);
                    break;
                case 'last_week':
                    $lastWeekStart = $now->copy()->subWeek()->startOfWeek();
                    $lastWeekEnd = $now->copy()->subWeek()->endOfWeek();
                    $query->whereBetween('class_date', [
                        $lastWeekStart->toDateString(),
                        $lastWeekEnd->toDateString()
                    ]);
                    break;
                case 'last_month':
                    $lastMonth = $now->copy()->subMonth();
                    $query->whereMonth('class_date', $lastMonth->month)
                          ->whereYear('class_date', $lastMonth->year);
                    break;
            }
        } elseif ($request->filled('date')) {
            // Filter by specific date (only if no date_range preset selected)
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

        // Add monthly attendance counter for each record - showing position (1/8, 2/8, 3/8)
        foreach ($attendance as $record) {
            if ($record->student && $record->class_date) {
                // Get all approved NON-stand-in attendance for this student in the same month, ordered chronologically
                $monthlyApproved = AttendanceRecord::where('student_id', $record->student_id)
                    ->where('status', 'approved')
                    ->where('is_stand_in', false)
                    ->whereYear('class_date', $record->class_date->year)
                    ->whereMonth('class_date', $record->class_date->month)
                    ->whereDate('class_date', '<=', $record->class_date)
                    ->orderBy('class_date', 'asc')
                    ->orderBy('class_time', 'asc')
                    ->pluck('id')
                    ->toArray();

                // Calculate expected monthly classes based on student's schedule
                $student = $record->student;
                $expectedMonthlyClasses = 0;

                if ($student->class_schedule && is_array($student->class_schedule)) {
                    $classesPerWeek = count($student->class_schedule);
                    $monthStart = \Carbon\Carbon::create($record->class_date->year, $record->class_date->month, 1);
                    $monthEnd = $monthStart->copy()->endOfMonth();
                    $weeksInMonth = ceil($monthEnd->diffInDays($monthStart) / 7);
                    $expectedMonthlyClasses = $classesPerWeek * $weeksInMonth;
                } else {
                    $expectedMonthlyClasses = AttendanceRecord::where('student_id', $record->student_id)
                        ->where('is_stand_in', false)
                        ->whereYear('class_date', $record->class_date->year)
                        ->whereMonth('class_date', $record->class_date->month)
                        ->count();
                }

                // Find position of this record in the chronological approved list (incremental: 1/8, 2/8, 3/8)
                if ($record->is_stand_in) {
                    $record->monthly_attended = 0; // Stand-in doesn't count
                } else {
                    $position = array_search($record->id, $monthlyApproved);
                    if ($record->status === 'approved' && $position !== false) {
                        // Approved: show actual position
                        $record->monthly_attended = $position + 1;
                    } elseif ($record->status === 'pending') {
                        // Pending: show expected position (count of approved before this date + 1)
                        $approvedBeforeCount = AttendanceRecord::where('student_id', $record->student_id)
                            ->where('status', 'approved')
                            ->where('is_stand_in', false)
                            ->whereYear('class_date', $record->class_date->year)
                            ->whereMonth('class_date', $record->class_date->month)
                            ->whereDate('class_date', '<', $record->class_date)
                            ->count();
                        $record->monthly_attended = $approvedBeforeCount + 1;
                    } else {
                        $record->monthly_attended = 0;
                    }
                }
                $record->monthly_total = max($expectedMonthlyClasses, 1);
            }
        }

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

            // Check if student's current course should be auto-completed based on attendance
            $student = $attendance->student;
            if ($student && $student->usesExplicitProgression()) {
                $wasAutoCompleted = $student->autoCompleteCourseIfReady();
                if ($wasAutoCompleted) {
                    return back()->with('success', 'Attendance approved. Student\'s current course has been marked as complete based on attendance count. Admin can now assign the next course.');
                }
            }

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
                'class_time' => $validated['class_start_time'] ?? '09:00',
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

    /**
     * Show the form for editing the specified attendance record.
     */
    public function edit(AttendanceRecord $attendance)
    {
        $attendance->load(['student', 'tutor']);
        $students = Student::where('status', 'active')->get();
        $tutors = Tutor::where('status', 'active')->get();

        return view('director.attendance.edit', compact('attendance', 'students', 'tutors'));
    }

    /**
     * Update the specified attendance record.
     */
    public function update(Request $request, AttendanceRecord $attendance)
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
            'status' => 'nullable|in:pending,approved,rejected',
        ]);

        try {
            $attendance->update([
                'student_id' => $validated['student_id'],
                'tutor_id' => $validated['tutor_id'],
                'class_date' => $validated['class_date'],
                'class_time' => $validated['class_start_time'] ?? $attendance->class_time,
                'class_start_time' => $validated['class_start_time'] ?? null,
                'class_end_time' => $validated['class_end_time'] ?? null,
                'topics_covered' => $validated['topics_covered'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'status' => $validated['status'] ?? $attendance->status,
            ]);

            // Log director activity
            DirectorActivityLog::create([
                'director_id' => Auth::id(),
                'action_type' => 'attendance_updated',
                'model_type' => 'AttendanceRecord',
                'model_id' => $attendance->id,
                'payload' => [
                    'description' => 'Attendance record updated',
                    'student_id' => $validated['student_id'],
                    'class_date' => $validated['class_date'],
                    'changes' => $attendance->getChanges(),
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->route('director.attendance.index')
                ->with('success', 'Attendance record updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to update attendance: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified attendance record.
     */
    public function destroy(AttendanceRecord $attendance)
    {
        try {
            $attendanceId = $attendance->id;
            $studentId = $attendance->student_id;
            $classDate = $attendance->class_date;

            $attendance->delete();

            // Log director activity
            DirectorActivityLog::create([
                'director_id' => Auth::id(),
                'action_type' => 'attendance_deleted',
                'model_type' => 'AttendanceRecord',
                'model_id' => $attendanceId,
                'payload' => [
                    'description' => 'Attendance record deleted',
                    'student_id' => $studentId,
                    'class_date' => $classDate,
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return redirect()->route('director.attendance.index')
                ->with('success', 'Attendance record deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete attendance: ' . $e->getMessage());
        }
    }
}
