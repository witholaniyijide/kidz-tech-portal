<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\MonthlyClassSchedule;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminAttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->hasRole('admin')) {
                abort(403, 'Unauthorized access.');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of attendance records.
     * Admin reviews tutor-submitted attendance and approves/marks late/deletes.
     */
    public function index(Request $request)
    {
        $query = AttendanceRecord::with(['student', 'tutor', 'approver']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by month
        if ($request->filled('month')) {
            $monthDate = Carbon::parse($request->month . '-01');
            $query->whereYear('class_date', $monthDate->year)
                  ->whereMonth('class_date', $monthDate->month);
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('class_date', $request->date);
        }

        // Filter by student
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        // Filter by tutor
        if ($request->filled('tutor_id')) {
            $query->where('tutor_id', $request->tutor_id);
        }

        // Filter late submissions only
        if ($request->boolean('late_only')) {
            $query->where('is_late', true);
        }

        $perPage = $request->get('per_page', 20);
        $attendances = $query->orderBy('created_at', 'desc')->paginate($perPage);

        // Add monthly attendance counter for each record - showing position (1/8, 2/8, 3/8)
        foreach ($attendances as $attendance) {
            if ($attendance->student && $attendance->class_date) {
                // Get all approved NON-stand-in attendance for this student in the same month, ordered chronologically
                $monthlyApproved = AttendanceRecord::where('student_id', $attendance->student_id)
                    ->where('status', 'approved')
                    ->where('is_stand_in', false)
                    ->whereYear('class_date', $attendance->class_date->year)
                    ->whereMonth('class_date', $attendance->class_date->month)
                    ->whereDate('class_date', '<=', $attendance->class_date)
                    ->orderBy('class_date', 'asc')
                    ->orderBy('class_time', 'asc')
                    ->pluck('id')
                    ->toArray();

                // Calculate expected monthly classes based on student's schedule
                $student = $attendance->student;
                $expectedMonthlyClasses = 0;

                if ($student->class_schedule && is_array($student->class_schedule)) {
                    $classesPerWeek = count($student->class_schedule);
                    $monthStart = Carbon::create($attendance->class_date->year, $attendance->class_date->month, 1);
                    $monthEnd = $monthStart->copy()->endOfMonth();
                    $weeksInMonth = ceil($monthEnd->diffInDays($monthStart) / 7);
                    $expectedMonthlyClasses = $classesPerWeek * $weeksInMonth;
                } else {
                    $expectedMonthlyClasses = AttendanceRecord::where('student_id', $attendance->student_id)
                        ->where('is_stand_in', false)
                        ->whereYear('class_date', $attendance->class_date->year)
                        ->whereMonth('class_date', $attendance->class_date->month)
                        ->count();
                }

                // Find position of this record in the chronological approved list (incremental: 1/8, 2/8, 3/8)
                if ($attendance->is_stand_in) {
                    $attendance->monthly_attended = 0; // Stand-in doesn't count
                } else {
                    $position = array_search($attendance->id, $monthlyApproved);
                    if ($attendance->status === 'approved' && $position !== false) {
                        // Approved: show actual position
                        $attendance->monthly_attended = $position + 1;
                    } elseif ($attendance->status === 'pending') {
                        // Pending: show expected position (count of approved before this date + 1)
                        $approvedBeforeCount = AttendanceRecord::where('student_id', $attendance->student_id)
                            ->where('status', 'approved')
                            ->where('is_stand_in', false)
                            ->whereYear('class_date', $attendance->class_date->year)
                            ->whereMonth('class_date', $attendance->class_date->month)
                            ->whereDate('class_date', '<', $attendance->class_date)
                            ->count();
                        $attendance->monthly_attended = $approvedBeforeCount + 1;
                    } else {
                        $attendance->monthly_attended = 0;
                    }
                }
                $attendance->monthly_total = max($expectedMonthlyClasses, 1);
            }
        }

        // Statistics
        $stats = [
            'total' => AttendanceRecord::count(),
            'approved' => AttendanceRecord::where('status', 'approved')->count(),
            'pending' => AttendanceRecord::where('status', 'pending')->count(),
            'late' => AttendanceRecord::where('is_late', true)->count(),
        ];

        $students = Student::where('status', 'active')->orderBy('first_name')->get();
        $tutors = Tutor::where('status', 'active')->orderBy('first_name')->get();

        return view('admin.attendance.index', compact('attendances', 'stats', 'students', 'tutors'));
    }

    /**
     * Display the specified attendance record.
     */
    public function show(AttendanceRecord $attendance)
    {
        $attendance->load(['student', 'tutor', 'approver']);
        return view('admin.attendance.show', compact('attendance'));
    }

    /**
     * Approve an attendance record.
     */
    public function approve(AttendanceRecord $attendance)
    {
        DB::transaction(function() use ($attendance) {
            $attendance->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            // Update monthly class schedule count
            $this->updateMonthlyScheduleCount($attendance);

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'approved',
                'description' => "Approved attendance for {$attendance->student->first_name} by {$attendance->tutor->first_name}",
                'model_type' => AttendanceRecord::class,
                'model_id' => $attendance->id,
            ]);
        });

        return redirect()->back()->with('success', 'Attendance approved successfully.');
    }

    /**
     * Mark an attendance record as late (submission was late).
     */
    public function markLate(AttendanceRecord $attendance)
    {
        DB::transaction(function() use ($attendance) {
            $attendance->update([
                'is_late' => true,
                'is_late_submission' => true,
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            // Update monthly class schedule count
            $this->updateMonthlyScheduleCount($attendance);

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'marked_late',
                'description' => "Marked attendance as late submission - {$attendance->student->first_name} by {$attendance->tutor->first_name}",
                'model_type' => AttendanceRecord::class,
                'model_id' => $attendance->id,
            ]);
        });

        return redirect()->back()->with('success', 'Attendance marked as late and approved.');
    }

    /**
     * Update the monthly class schedule completed count.
     */
    protected function updateMonthlyScheduleCount(AttendanceRecord $attendance)
    {
        if (!$attendance->class_date || !$attendance->tutor_id || !$attendance->student_id) {
            return;
        }

        $year = $attendance->class_date->year;
        $month = $attendance->class_date->month;

        // Find the monthly schedule record
        $schedule = MonthlyClassSchedule::where('student_id', $attendance->student_id)
            ->where('year', $year)
            ->where('month', $month)
            ->first();

        if ($schedule) {
            // Recalculate the completed classes count from approved attendance records
            $completedCount = AttendanceRecord::where('tutor_id', $schedule->tutor_id)
                ->where('student_id', $attendance->student_id)
                ->where('status', 'approved')
                ->whereMonth('class_date', $month)
                ->whereYear('class_date', $year)
                ->count();

            $schedule->update(['completed_classes' => $completedCount]);
        }
    }

    /**
     * Delete attendance record (Admin asks tutor to recreate and resubmit).
     */
    public function destroy(AttendanceRecord $attendance)
    {
        $studentName = $attendance->student->first_name ?? 'Unknown';
        $tutorName = $attendance->tutor->first_name ?? 'Unknown';
        $classDate = $attendance->class_date?->format('M j, Y') ?? 'Unknown';

        DB::transaction(function() use ($attendance, $studentName, $tutorName, $classDate) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'deleted',
                'description' => "Deleted attendance for {$studentName} by {$tutorName} on {$classDate} (requested resubmission)",
                'model_type' => AttendanceRecord::class,
                'model_id' => $attendance->id,
            ]);

            $attendance->delete();
        });

        return redirect()
            ->route('admin.attendance.index')
            ->with('success', "Attendance deleted. Please notify {$tutorName} to resubmit.");
    }

    // Note: Admin does NOT create or edit attendance.
    // Tutors submit attendance after completing classes.
    // Admin only reviews, approves, marks late, or deletes for resubmission.

    /**
     * Export attendance records to CSV.
     */
    public function export(Request $request)
    {
        $period = $request->get('period', 'week');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Determine date range
        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);
        } elseif ($period === 'month') {
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now()->endOfMonth();
        } else {
            // Default to current week
            $start = Carbon::now()->startOfWeek();
            $end = Carbon::now()->endOfWeek();
        }

        $attendances = AttendanceRecord::with(['student', 'tutor', 'approver'])
            ->whereBetween('class_date', [$start, $end])
            ->orderBy('class_date', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        // Get monthly schedules for all students in the date range
        $monthlySchedules = MonthlyClassSchedule::whereIn('student_id', $attendances->pluck('student_id')->unique())
            ->where('year', $start->year)
            ->where('month', $start->month)
            ->get()
            ->keyBy('student_id');

        // Create CSV content
        $filename = "attendance_export_{$start->format('Y-m-d')}_to_{$end->format('Y-m-d')}.csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($attendances, $monthlySchedules, $start, $end) {
            $file = fopen('php://output', 'w');

            // Add BOM for Excel UTF-8 compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // CSV Header
            fputcsv($file, [
                'Date',
                'Student Name',
                'Tutor Name',
                'Class Time',
                'Duration (mins)',
                'Class End',
                'Status',
                'Late Submission',
                'Courses Covered',
                'Topic',
                'Notes',
                'Monthly Classes (Completed/Total)',
                'Submitted At',
                'Approved By',
                'Approved At',
            ]);

            // CSV Data
            foreach ($attendances as $attendance) {
                $monthlySchedule = $monthlySchedules->get($attendance->student_id);
                $monthlyCount = $monthlySchedule
                    ? "{$monthlySchedule->completed_classes}/{$monthlySchedule->total_classes}"
                    : '-';

                $classTime = $attendance->class_time?->format('H:i') ?? '';
                $classEnd = ($attendance->class_time && $attendance->duration_minutes)
                    ? $attendance->class_time->copy()->addMinutes($attendance->duration_minutes)->format('H:i')
                    : '';

                $coursesCovered = is_array($attendance->courses_covered)
                    ? implode('; ', $attendance->courses_covered)
                    : '';

                fputcsv($file, [
                    $attendance->class_date?->format('Y-m-d'),
                    ($attendance->student?->first_name ?? '') . ' ' . ($attendance->student?->last_name ?? ''),
                    ($attendance->tutor?->first_name ?? '') . ' ' . ($attendance->tutor?->last_name ?? ''),
                    $classTime,
                    $attendance->duration_minutes ?? 60,
                    $classEnd,
                    ucfirst($attendance->status),
                    ($attendance->is_late || $attendance->is_late_submission) ? 'Yes' : 'No',
                    $coursesCovered,
                    $attendance->topic ?? '',
                    $attendance->notes ?? '',
                    $monthlyCount,
                    $attendance->created_at?->format('Y-m-d H:i'),
                    $attendance->approver ? ($attendance->approver->name ?? 'Unknown') : '',
                    $attendance->approved_at?->format('Y-m-d H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show export options modal data (AJAX).
     */
    public function exportOptions()
    {
        return response()->json([
            'current_week' => [
                'start' => Carbon::now()->startOfWeek()->format('Y-m-d'),
                'end' => Carbon::now()->endOfWeek()->format('Y-m-d'),
            ],
            'current_month' => [
                'start' => Carbon::now()->startOfMonth()->format('Y-m-d'),
                'end' => Carbon::now()->endOfMonth()->format('Y-m-d'),
            ],
            'last_week' => [
                'start' => Carbon::now()->subWeek()->startOfWeek()->format('Y-m-d'),
                'end' => Carbon::now()->subWeek()->endOfWeek()->format('Y-m-d'),
            ],
            'last_month' => [
                'start' => Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d'),
                'end' => Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d'),
            ],
        ]);
    }
}
