<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = AttendanceRecord::with(['student', 'tutor']);

        if ($request->has('date') && $request->date) {
            $query->whereDate('class_date', $request->date);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $records = $query->orderBy('class_date', 'desc')
                        ->orderBy('created_at', 'desc')
                        ->paginate(20);

        return view('attendance.index', compact('records'));
    }

    public function create(Request $request)
    {
        $selectedDate = $request->date ?? Carbon::today()->format('Y-m-d');
        $students = Student::active()->orderBy('first_name')->get();

        $existingAttendance = AttendanceRecord::whereDate('class_date', $selectedDate)
                                              ->pluck('student_id')
                                              ->toArray();

        return view('attendance.create', compact('students', 'selectedDate', 'existingAttendance'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_date' => 'required|date',
            'class_time' => 'nullable|date_format:H:i',
            'duration_minutes' => 'nullable|integer|min:1',
            'topic' => 'nullable|string|max:255',
            'attendance' => 'required|array',
            'attendance.*.student_id' => 'required|exists:students,id',
            'attendance.*.notes' => 'nullable|string',
        ]);

        $user = Auth::user();
        $tutor = \App\Models\Tutor::where('user_id', $user->id)->first();

        if (!$tutor) {
            return back()->withErrors(['error' => 'Tutor profile not found.']);
        }

        $count = 0;
        foreach ($request->attendance as $record) {
            AttendanceRecord::create([
                'student_id' => $record['student_id'],
                'tutor_id' => $tutor->id,
                'class_date' => $request->class_date,
                'class_time' => $request->class_time ?? now()->format('H:i'),
                'duration_minutes' => $request->duration_minutes ?? 60,
                'topic' => $request->topic,
                'notes' => $record['notes'] ?? null,
                'status' => 'pending',
            ]);
            $count++;
        }

        return redirect()->route('attendance.index')
            ->with('success', "Attendance recorded for {$count} students!");
    }

    public function show(AttendanceRecord $attendance)
    {
        $attendance->load(['student', 'tutor', 'approver']);
        return view('attendance.show', compact('attendance'));
    }

    public function approve(AttendanceRecord $attendance)
    {
        $attendance->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        // Increment student's completed_periods
        if ($attendance->student) {
            $attendance->student->increment('completed_periods');
        }

        return back()->with('success', 'Attendance approved successfully!');
    }

    public function reject(AttendanceRecord $attendance)
    {
        $attendance->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Attendance rejected!');
    }

    public function destroy(AttendanceRecord $attendance)
    {
        $attendance->delete();
        return redirect()->route('attendance.index')
            ->with('success', 'Attendance record deleted successfully!');
    }

    /**
     * Show pending attendance records for approval
     */
    public function pending()
    {
        $records = AttendanceRecord::with(['student', 'tutor'])
            ->where('status', 'pending')
            ->orderBy('class_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('attendance.pending', compact('records'));
    }

    /**
     * Bulk approve attendance records
     */
    public function bulkApprove(Request $request)
    {
        $validated = $request->validate([
            'record_ids' => 'required|array',
            'record_ids.*' => 'exists:attendance_records,id',
        ]);

        $count = 0;
        foreach ($validated['record_ids'] as $id) {
            $attendance = AttendanceRecord::find($id);
            if ($attendance && $attendance->status === 'pending') {
                $attendance->update([
                    'status' => 'approved',
                    'approved_by' => Auth::id(),
                    'approved_at' => now(),
                ]);

                // Increment student's completed_periods
                $student = $attendance->student;
                if ($student) {
                    $student->increment('completed_periods');
                }

                $count++;
            }
        }

        return back()->with('success', "Successfully approved {$count} attendance records!");
    }

    /**
     * Bulk reject attendance records
     */
    public function bulkReject(Request $request)
    {
        $validated = $request->validate([
            'record_ids' => 'required|array',
            'record_ids.*' => 'exists:attendance_records,id',
        ]);

        $count = 0;
        foreach ($validated['record_ids'] as $id) {
            $attendance = AttendanceRecord::find($id);
            if ($attendance && $attendance->status === 'pending') {
                $attendance->update([
                    'status' => 'rejected',
                    'approved_by' => Auth::id(),
                    'approved_at' => now(),
                ]);
                $count++;
            }
        }

        return back()->with('success', "Successfully rejected {$count} attendance records!");
    }
}
