<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\Student;
use App\Models\Tutor;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ManagerAttendanceController extends Controller
{
    /**
     * Display a listing of attendance records.
     */
    public function index(Request $request)
    {
        $query = AttendanceRecord::with(['student', 'tutor', 'approver']);

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->has('date') && $request->date) {
            $query->whereDate('class_date', $request->date);
        }

        // Filter by student if provided
        if ($request->has('student_id') && $request->student_id) {
            $query->where('student_id', $request->student_id);
        }

        // Filter by tutor if provided
        if ($request->has('tutor_id') && $request->tutor_id) {
            $query->where('tutor_id', $request->tutor_id);
        }

        // Get per page count from request (default 20)
        $perPage = $request->input('per_page', 20);
        $perPage = in_array($perPage, [20, 50, 100]) ? $perPage : 20;

        $attendanceRecords = $query->orderBy('class_date', 'desc')
            ->orderBy('class_time', 'desc')
            ->paginate($perPage);

        // Get statistics
        $stats = [
            'total' => AttendanceRecord::count(),
            'approved' => AttendanceRecord::where('status', 'approved')->count(),
            'pending' => AttendanceRecord::where('status', 'pending')->count(),
            'late' => AttendanceRecord::where('is_late_submission', true)->count(),
        ];

        // Get students and tutors for filter dropdowns
        $students = Student::where('status', 'active')->orderBy('first_name')->get();
        $tutors = Tutor::where('status', 'active')->orderBy('first_name')->get();

        return view('manager.attendance.index', compact(
            'attendanceRecords',
            'stats',
            'students',
            'tutors',
            'perPage'
        ));
    }

    /**
     * Store a newly created attendance record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'tutor_id' => 'required|exists:tutors,id',
            'class_date' => 'required|date',
            'class_time' => 'required',
            'duration' => 'required|integer|min:30|max:180',
            'topic' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Check if it's a late submission (more than 24 hours after class date)
        $classDate = Carbon::parse($validated['class_date']);
        $isLate = $classDate->diffInHours(now()) > 24;

        $attendance = AttendanceRecord::create([
            'student_id' => $validated['student_id'],
            'tutor_id' => $validated['tutor_id'],
            'class_date' => $validated['class_date'],
            'class_time' => $validated['class_time'],
            'duration' => $validated['duration'],
            'topic' => $validated['topic'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
            'is_late_submission' => $isLate,
            'submitted_by' => auth()->id(),
            'submitted_at' => now(),
        ]);

        return redirect()->route('manager.attendance.index')
            ->with('success', 'Attendance submitted successfully.');
    }

    /**
     * Display the specified attendance record.
     */
    public function show(AttendanceRecord $record)
    {
        $record->load(['student', 'tutor', 'approver']);

        return view('manager.attendance.show', compact('record'));
    }

    /**
     * Display attendance calendar view.
     */
    public function calendar(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        $attendanceRecords = AttendanceRecord::with(['student', 'tutor'])
            ->whereYear('class_date', $year)
            ->whereMonth('class_date', $month)
            ->get()
            ->groupBy(function($record) {
                return $record->class_date->format('Y-m-d');
            });

        return view('manager.attendance.calendar', compact(
            'attendanceRecords',
            'month',
            'year'
        ));
    }

    /**
     * Display pending attendance records for approval.
     */
    public function pending(Request $request)
    {
        $query = AttendanceRecord::where('status', 'pending')
            ->with(['student', 'tutor']);

        // Filter by tutor
        if ($request->filled('tutor_id')) {
            $query->where('tutor_id', $request->tutor_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('class_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('class_date', '<=', $request->date_to);
        }

        // Filter by student
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        $records = $query->orderBy('class_date', 'desc')
            ->orderBy('class_time', 'desc')
            ->paginate(20);

        $tutors = Tutor::orderBy('first_name')->get();
        $students = Student::orderBy('first_name')->get();

        return view('manager.attendance.pending', compact('records', 'tutors', 'students'));
    }

    /**
     * Approve a single attendance record.
     */
    public function approve(AttendanceRecord $attendance)
    {
        \DB::transaction(function () use ($attendance) {
            $attendance->status = 'approved';
            $attendance->approved_by = auth()->id();
            $attendance->approved_at = now();
            $attendance->save();

            // Increment student's completed_periods
            if ($attendance->student) {
                $attendance->student->increment('completed_periods', 1);
            }
        });

        return redirect()->back()->with('success', 'Attendance approved successfully.');
    }

    /**
     * Reject a single attendance record.
     */
    public function reject(AttendanceRecord $attendance)
    {
        $attendance->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Attendance rejected.');
    }

    /**
     * Bulk approve attendance records.
     */
    public function bulkApprove(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return redirect()->back()->with('error', 'No records selected.');
        }

        \DB::transaction(function () use ($ids) {
            $records = AttendanceRecord::whereIn('id', $ids)
                ->where('status', 'pending')
                ->get();

            foreach ($records as $attendance) {
                $attendance->update([
                    'status' => 'approved',
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),
                ]);

                if ($attendance->student) {
                    $attendance->student->increment('completed_periods', 1);
                }
            }
        });

        return redirect()->back()->with('success', 'Selected attendance records approved successfully.');
    }
}
