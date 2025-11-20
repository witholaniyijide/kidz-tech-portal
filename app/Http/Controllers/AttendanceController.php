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
        $query = AttendanceRecord::with(['student', 'submittedBy']);

        if ($request->has('date') && $request->date) {
            $query->whereDate('attendance_date', $request->date);
        }

        if ($request->has('status') && $request->status) {
            $query->where('approval_status', $request->status);
        }

        $records = $query->orderBy('attendance_date', 'desc')
                        ->orderBy('created_at', 'desc')
                        ->paginate(20);

        return view('attendance.index', compact('records'));
    }

    public function create(Request $request)
    {
        $selectedDate = $request->date ?? Carbon::today()->format('Y-m-d');
        $students = Student::active()->orderBy('first_name')->get();
        
        $existingAttendance = AttendanceRecord::whereDate('attendance_date', $selectedDate)
                                              ->pluck('student_id')
                                              ->toArray();

        return view('attendance.create', compact('students', 'selectedDate', 'existingAttendance'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'attendance_date' => 'required|date',
            'session' => 'nullable|string|max:255',
            'attendance' => 'required|array',
            'attendance.*.student_id' => 'required|exists:students,id',
            'attendance.*.status' => 'required|in:present,absent,late,excused',
            'attendance.*.notes' => 'nullable|string',
        ]);

        $count = 0;
        foreach ($request->attendance as $record) {
            AttendanceRecord::updateOrCreate(
                [
                    'student_id' => $record['student_id'],
                    'attendance_date' => $request->attendance_date,
                    'session' => $request->session,
                ],
                [
                    'status' => $record['status'],
                    'notes' => $record['notes'] ?? null,
                    'submitted_by' => Auth::id(),
                    'approval_status' => 'pending',
                ]
            );
            $count++;
        }

        return redirect()->route('attendance.index')
            ->with('success', "Attendance recorded for {$count} students!");
    }

    public function show(AttendanceRecord $attendance)
    {
        $attendance->load(['student', 'submittedBy', 'approvedBy']);
        return view('attendance.show', compact('attendance'));
    }

    public function approve(AttendanceRecord $attendance)
    {
        $attendance->update([
            'approval_status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Attendance approved successfully!');
    }

    public function reject(AttendanceRecord $attendance)
    {
        $attendance->update([
            'approval_status' => 'rejected',
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
}
