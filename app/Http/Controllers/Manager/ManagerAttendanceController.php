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
     * Display a listing of attendance records (read-only).
     */
    public function index(Request $request)
    {
        $query = AttendanceRecord::with(['student', 'tutor', 'approver']);

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->start_date) {
            $query->where('class_date', '>=', Carbon::parse($request->start_date));
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->where('class_date', '<=', Carbon::parse($request->end_date));
        }

        // Filter by student if provided
        if ($request->has('student_id') && $request->student_id) {
            $query->where('student_id', $request->student_id);
        }

        // Filter by tutor if provided
        if ($request->has('tutor_id') && $request->tutor_id) {
            $query->where('tutor_id', $request->tutor_id);
        }

        $attendanceRecords = $query->orderBy('class_date', 'desc')
            ->orderBy('class_time', 'desc')
            ->paginate(20);

        // Get statistics
        $stats = [
            'total' => AttendanceRecord::count(),
            'pending' => AttendanceRecord::where('status', 'pending')->count(),
            'approved' => AttendanceRecord::where('status', 'approved')->count(),
            'rejected' => AttendanceRecord::where('status', 'rejected')->count(),
        ];

        // Get students and tutors for filter dropdowns
        $students = Student::orderBy('first_name')->get();
        $tutors = Tutor::orderBy('first_name')->get();

        return view('manager.attendance.index', compact(
            'attendanceRecords',
            'stats',
            'students',
            'tutors'
        ));
    }

    /**
     * Display the specified attendance record (read-only).
     */
    public function show(AttendanceRecord $record)
    {
        // Load relationships
        $record->load(['student', 'tutor', 'approver']);

        return view('manager.attendance.show', compact('record'));
    }

    /**
     * Display attendance calendar view (read-only).
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
}
