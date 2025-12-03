<?php

namespace App\Http\Controllers\Director;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\Student;
use App\Models\Tutor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DirectorAttendanceController extends Controller
{
    /**
     * Display a listing of attendance records.
     */
    public function index(Request $request)
    {
        $query = AttendanceRecord::with(['student', 'tutor', 'approver']);

        // Filter by date
        if ($request->has('date') && $request->date) {
            $query->whereDate('class_date', $request->date);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by tutor
        if ($request->has('tutor_id') && $request->tutor_id) {
            $query->where('tutor_id', $request->tutor_id);
        }

        // Filter by student
        if ($request->has('student_id') && $request->student_id) {
            $query->where('student_id', $request->student_id);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('class_date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('class_date', '<=', $request->end_date);
        }

        $records = $query->orderBy('class_date', 'desc')
                        ->orderBy('created_at', 'desc')
                        ->paginate(20);

        // Get statistics
        $stats = [
            'total' => AttendanceRecord::count(),
            'pending' => AttendanceRecord::where('status', 'pending')->count(),
            'approved' => AttendanceRecord::where('status', 'approved')->count(),
            'rejected' => AttendanceRecord::where('status', 'rejected')->count(),
            'this_month' => AttendanceRecord::whereYear('class_date', Carbon::now()->year)
                ->whereMonth('class_date', Carbon::now()->month)
                ->count(),
        ];

        // Get tutors and students for filter dropdowns
        $tutors = Tutor::orderBy('first_name')->get();
        $students = Student::orderBy('first_name')->get();

        return view('director.attendance.index', compact('records', 'stats', 'tutors', 'students'));
    }

    /**
     * Display the specified attendance record.
     */
    public function show(AttendanceRecord $attendance)
    {
        $attendance->load(['student', 'tutor', 'approver']);

        // Get student's attendance history
        $studentHistory = AttendanceRecord::where('student_id', $attendance->student_id)
            ->where('id', '!=', $attendance->id)
            ->with('tutor')
            ->orderBy('class_date', 'desc')
            ->limit(5)
            ->get();

        // Calculate student's attendance rate
        $totalScheduled = AttendanceRecord::where('student_id', $attendance->student_id)->count();
        $totalAttended = AttendanceRecord::where('student_id', $attendance->student_id)
            ->where('status', 'approved')
            ->count();
        $attendanceRate = $totalScheduled > 0 ? round(($totalAttended / $totalScheduled) * 100, 2) : 0;

        return view('director.attendance.show', compact('attendance', 'studentHistory', 'attendanceRate'));
    }

    /**
     * Approve an attendance record.
     */
    public function approve(AttendanceRecord $attendance)
    {
        // Check if already approved
        if ($attendance->status === 'approved') {
            return back()->with('info', 'This attendance record has already been approved.');
        }

        $attendance->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        // Increment student's completed_periods if not already incremented
        if ($attendance->student) {
            $attendance->student->increment('completed_periods');
        }

        return back()->with('success', 'Attendance approved successfully!');
    }

    /**
     * Reject an attendance record.
     */
    public function reject(AttendanceRecord $attendance)
    {
        $attendance->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Attendance rejected!');
    }

    /**
     * Bulk approve attendance records.
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
                if ($attendance->student) {
                    $attendance->student->increment('completed_periods');
                }

                $count++;
            }
        }

        return back()->with('success', "Successfully approved {$count} attendance records!");
    }

    /**
     * Bulk reject attendance records.
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
