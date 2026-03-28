<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tutor\StoreAttendanceRequest;
use App\Models\AttendanceRecord;
use App\Models\DailyClassSchedule;
use App\Models\MonthlyClassSchedule;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\User;
use App\Models\ManagerNotification;
use App\Models\AdminNotification;
use App\Services\NotificationService;
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

        // Add monthly attendance counter for each record
        foreach ($attendanceRecords as $attendance) {
            $classMonth = $attendance->class_date->format('Y-m');

            // Get student's assigned tutor
            $student = $attendance->student;
            $assignedTutorId = $student->tutor_id;

            // For main tutor's count: only count NON-stand-in approved attendance
            // This ensures stand-in classes don't interrupt the main tutor's count
            $monthlyApproved = AttendanceRecord::where('student_id', $attendance->student_id)
                ->where('status', 'approved')
                ->where('is_stand_in', false) // Exclude stand-in records
                ->whereYear('class_date', $attendance->class_date->year)
                ->whereMonth('class_date', $attendance->class_date->month)
                ->whereDate('class_date', '<=', $attendance->class_date)
                ->orderBy('class_date', 'asc')
                ->orderBy('class_time', 'asc')
                ->pluck('id')
                ->toArray();

            // Calculate expected monthly classes
            // First check if tutor set up MonthlyClassSchedule for this student/month
            $expectedMonthlyClasses = 0;
            $monthlySchedule = MonthlyClassSchedule::where('student_id', $attendance->student_id)
                ->where('year', $attendance->class_date->year)
                ->where('month', $attendance->class_date->month)
                ->first();

            if ($monthlySchedule && $monthlySchedule->total_classes > 0) {
                // Use tutor-set monthly schedule
                $expectedMonthlyClasses = $monthlySchedule->total_classes;
            } elseif ($student) {
                // Fall back to calculating from student's weekly schedule
                $expectedMonthlyClasses = $student->getExpectedClassesForMonth(
                    $attendance->class_date->year,
                    $attendance->class_date->month
                );
            }

            // Final fallback: count actual attendance records
            if ($expectedMonthlyClasses === 0) {
                $expectedMonthlyClasses = AttendanceRecord::where('student_id', $attendance->student_id)
                    ->where('is_stand_in', false)
                    ->whereYear('class_date', $attendance->class_date->year)
                    ->whereMonth('class_date', $attendance->class_date->month)
                    ->count();
            }

            // Find position of this attendance in the approved list
            // Stand-in attendance won't have a position in the main count
            if ($attendance->is_stand_in) {
                $attendance->monthly_position = 0; // Stand-in doesn't count towards main tutor's tally
                $attendance->is_stand_in_display = true;
            } else {
                $position = array_search($attendance->id, $monthlyApproved);
                if ($attendance->status === 'approved' && $position !== false) {
                    // Approved: show actual position
                    $attendance->monthly_position = $position + 1;
                } elseif ($attendance->status === 'pending') {
                    // Pending: show expected position (count of approved before this date + 1)
                    $approvedBeforeCount = AttendanceRecord::where('student_id', $attendance->student_id)
                        ->where('status', 'approved')
                        ->where('is_stand_in', false)
                        ->whereYear('class_date', $attendance->class_date->year)
                        ->whereMonth('class_date', $attendance->class_date->month)
                        ->whereDate('class_date', '<', $attendance->class_date)
                        ->count();
                    $attendance->monthly_position = $approvedBeforeCount + 1;
                } else {
                    $attendance->monthly_position = 0;
                }
                $attendance->is_stand_in_display = false;
            }
            $attendance->monthly_total = $expectedMonthlyClasses;
        }

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

        $classDate = Carbon::parse($request->class_date);
        $classTime = $request->class_time ?? '18:00'; // Default to 6pm if no time provided
        $durationMinutes = $request->duration_minutes ?? 60;

        // Build the full class datetime
        $classDateTime = Carbon::parse($request->class_date . ' ' . $classTime);

        // VALIDATION 1: Cannot submit attendance before the class time
        // The class must have already started (or be in progress) to submit attendance
        if (now()->lt($classDateTime)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'You cannot submit attendance before the class time. The class is scheduled for ' . $classDateTime->format('M j, Y \a\t g:i A') . '. Please wait until the class has started.');
        }

        // VALIDATION 2: Check if there's a scheduled class for this student on this date
        // Skip this validation if user explicitly confirmed (force_submit flag)
        if (!$request->boolean('force_submit', false)) {
            $dayName = $classDate->format('l');
            $hasSchedule = false;

            // Check DailyClassSchedule for this date
            $dailySchedule = DailyClassSchedule::where('schedule_date', $classDate->toDateString())
                ->where('status', 'posted')
                ->first();

            if ($dailySchedule) {
                // Check regular classes
                if ($dailySchedule->classes) {
                    foreach ($dailySchedule->classes as $class) {
                        if (isset($class['student_id']) && $class['student_id'] == $student->id) {
                            $hasSchedule = true;
                            break;
                        }
                    }
                }

                // Check rescheduled classes
                if (!$hasSchedule && $dailySchedule->rescheduled_classes) {
                    foreach ($dailySchedule->rescheduled_classes as $class) {
                        if (isset($class['student_id']) && $class['student_id'] == $student->id) {
                            $hasSchedule = true;
                            break;
                        }
                    }
                }
            }

            // Also check student's weekly class_schedule
            if (!$hasSchedule && $student->class_schedule && is_array($student->class_schedule)) {
                foreach ($student->class_schedule as $schedule) {
                    if (isset($schedule['day']) && $schedule['day'] === $dayName) {
                        $hasSchedule = true;
                        break;
                    }
                }
            }

            // If no schedule found, show warning but allow override
            if (!$hasSchedule) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('schedule_warning', [
                        'message' => "No scheduled class found for {$student->first_name} {$student->last_name} on {$dayName}, {$classDate->format('M j, Y')}. If you believe this is correct (e.g., make-up class, special arrangement), please confirm to proceed.",
                        'student_name' => $student->first_name . ' ' . $student->last_name,
                        'date' => $classDate->format('l, M j, Y'),
                    ]);
            }
        }

        // Determine if submission is late
        // Policy: Tutor has 6-hour grace period after class ends
        // Class end time = class_date + class_time + duration_minutes
        // Deadline = class end time + 6 hours
        $classEndTime = Carbon::parse($request->class_date . ' ' . $classTime)
            ->addMinutes($durationMinutes);
        $deadline = $classEndTime->copy()->addHours(6);
        $isLate = now()->gt($deadline);

        // Check if this is a rescheduled class
        $isRescheduled = $request->boolean('is_rescheduled', false);

        // Create attendance record
        $attendance = AttendanceRecord::create([
            'student_id' => $request->student_id,
            'tutor_id' => $tutor->id,
            'class_date' => $request->class_date,
            'class_time' => $request->class_time,
            'duration_minutes' => $request->duration_minutes,
            'courses_covered' => $request->courses_covered,
            'topic' => $request->topic,
            'notes' => $request->notes,
            'is_stand_in' => $isActuallyStandIn,
            'stand_in_reason' => $isActuallyStandIn ? $request->stand_in_reason : null,
            'status' => 'pending',
            'is_late' => $isLate,
            'is_late_submission' => $isLate,
            'is_rescheduled' => $isRescheduled,
            'original_scheduled_time' => $isRescheduled ? $request->original_scheduled_time : null,
            'reschedule_reason' => $isRescheduled ? $request->reschedule_reason : null,
            'reschedule_notes' => $isRescheduled ? $request->reschedule_notes : null,
        ]);

        // Notify managers about the submitted attendance
        $managers = User::whereHas('roles', function ($query) {
            $query->where('name', 'manager');
        })->get();

        $notificationTitle = $isActuallyStandIn ? 'Stand-in Attendance Submitted' : 'Attendance Submitted';
        $notificationBody = "{$tutor->first_name} {$tutor->last_name} submitted attendance for {$student->first_name} {$student->last_name} on {$classDate->format('M j, Y')}.";

        foreach ($managers as $manager) {
            ManagerNotification::create([
                'user_id' => $manager->id,
                'title' => $notificationTitle,
                'body' => $notificationBody,
                'type' => 'attendance',
                'is_read' => false,
                'meta' => [
                    'attendance_id' => $attendance->id,
                    'action' => 'submitted',
                    'is_stand_in' => $isActuallyStandIn,
                ],
            ]);
        }

        // Notify admins about the submitted attendance
        $admins = User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->get();

        foreach ($admins as $admin) {
            AdminNotification::create([
                'user_id' => $admin->id,
                'title' => $notificationTitle,
                'body' => $notificationBody,
                'type' => 'attendance',
                'is_read' => false,
                'meta' => [
                    'attendance_id' => $attendance->id,
                    'action' => 'submitted',
                    'is_stand_in' => $isActuallyStandIn,
                ],
            ]);
        }

        // Notify directors about the submitted attendance (in-app only)
        app(NotificationService::class)->notifyDirectorAttendanceSubmitted($attendance);

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

    /**
     * Check for duplicate attendance records.
     * Enhanced to check time, topic, and courses for exact duplicates.
     */
    public function checkDuplicate(Request $request)
    {
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            return response()->json(['error' => 'No tutor profile'], 403);
        }

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'class_date' => 'required|date',
            'class_time' => 'nullable|string',
            'topic' => 'nullable|string',
            'courses_covered' => 'nullable|array',
        ]);

        $student = Student::find($request->student_id);

        // Check for existing attendance on the same date
        $duplicates = AttendanceRecord::where('student_id', $request->student_id)
            ->whereDate('class_date', $request->class_date)
            ->get();

        if ($duplicates->isEmpty()) {
            return response()->json([
                'has_duplicate' => false,
                'has_exact_duplicate' => false,
            ]);
        }

        // Check for exact duplicate (same time, topic, and courses)
        $exactDuplicate = null;
        $requestedTime = $request->class_time;
        $requestedTopic = $request->topic;
        $requestedCourses = $request->courses_covered ?? [];

        foreach ($duplicates as $record) {
            $recordTime = $record->class_time ? Carbon::parse($record->class_time)->format('H:i') : null;
            $timesMatch = $requestedTime && $recordTime && $requestedTime === $recordTime;

            $topicsMatch = $requestedTopic && $record->topic &&
                           strtolower(trim($requestedTopic)) === strtolower(trim($record->topic));

            $recordCourses = $record->courses_covered ?? [];
            sort($requestedCourses);
            sort($recordCourses);
            $coursesMatch = !empty($requestedCourses) && !empty($recordCourses) &&
                           $requestedCourses == $recordCourses;

            // If time, topic, and courses all match, it's an exact duplicate
            if ($timesMatch && $topicsMatch && $coursesMatch) {
                $exactDuplicate = $record;
                break;
            }
        }

        // Format the duplicates for display
        $duplicateInfo = $duplicates->map(function ($record) use ($requestedTime, $requestedTopic, $requestedCourses) {
            $recordTime = $record->class_time ? Carbon::parse($record->class_time)->format('H:i') : null;
            $timesMatch = $requestedTime && $recordTime && $requestedTime === $recordTime;

            $topicsMatch = $requestedTopic && $record->topic &&
                           strtolower(trim($requestedTopic)) === strtolower(trim($record->topic));

            $recordCourses = $record->courses_covered ?? [];
            $sortedRequested = $requestedCourses;
            $sortedRecord = $recordCourses;
            sort($sortedRequested);
            sort($sortedRecord);
            $coursesMatch = !empty($sortedRequested) && !empty($sortedRecord) &&
                           $sortedRequested == $sortedRecord;

            return [
                'id' => $record->id,
                'time' => $record->class_time ? Carbon::parse($record->class_time)->format('g:i A') : 'N/A',
                'topic' => $record->topic ?? 'N/A',
                'courses' => $record->courses_covered ? implode(', ', $record->courses_covered) : 'N/A',
                'status' => ucfirst($record->status),
                'tutor' => $record->tutor ? $record->tutor->first_name . ' ' . $record->tutor->last_name : 'Unknown',
                'submitted_at' => $record->created_at->format('M j, Y g:i A'),
                'time_matches' => $timesMatch,
                'topic_matches' => $topicsMatch,
                'courses_match' => $coursesMatch,
                'is_exact_match' => $timesMatch && $topicsMatch && $coursesMatch,
            ];
        });

        return response()->json([
            'has_duplicate' => true,
            'has_exact_duplicate' => $exactDuplicate !== null,
            'count' => $duplicates->count(),
            'student_name' => $student->first_name . ' ' . $student->last_name,
            'date' => Carbon::parse($request->class_date)->format('l, M j, Y'),
            'duplicates' => $duplicateInfo,
        ]);
    }

    /**
     * Check if a student has a scheduled class on the given date.
     * Returns schedule info or null if no schedule exists.
     */
    public function checkSchedule(Request $request)
    {
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            return response()->json(['error' => 'No tutor profile'], 403);
        }

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'class_date' => 'required|date',
        ]);

        $student = Student::find($request->student_id);
        $classDate = Carbon::parse($request->class_date);
        $dayName = $classDate->format('l');

        // Check 1: Is there a posted DailyClassSchedule for this date with this student?
        $dailySchedule = DailyClassSchedule::where('schedule_date', $classDate->toDateString())
            ->where('status', 'posted')
            ->first();

        $scheduledInDaily = false;
        $scheduledTime = null;
        $isRescheduled = false;

        if ($dailySchedule) {
            // Check regular classes
            if ($dailySchedule->classes) {
                foreach ($dailySchedule->classes as $class) {
                    if (isset($class['student_id']) && $class['student_id'] == $student->id) {
                        $scheduledInDaily = true;
                        $scheduledTime = $class['time'] ?? null;
                        break;
                    }
                }
            }

            // Check rescheduled classes
            if (!$scheduledInDaily && $dailySchedule->rescheduled_classes) {
                foreach ($dailySchedule->rescheduled_classes as $class) {
                    if (isset($class['student_id']) && $class['student_id'] == $student->id) {
                        $scheduledInDaily = true;
                        $scheduledTime = $class['time'] ?? null;
                        $isRescheduled = true;
                        break;
                    }
                }
            }
        }

        // Check 2: Does the student have this day in their weekly class_schedule?
        $scheduledInWeekly = false;
        $weeklyScheduleTime = null;

        if ($student->class_schedule && is_array($student->class_schedule)) {
            foreach ($student->class_schedule as $schedule) {
                if (isset($schedule['day']) && $schedule['day'] === $dayName) {
                    $scheduledInWeekly = true;
                    $weeklyScheduleTime = $schedule['time'] ?? null;
                    break;
                }
            }
        }

        $hasSchedule = $scheduledInDaily || $scheduledInWeekly;
        $time = $scheduledTime ?? $weeklyScheduleTime;

        return response()->json([
            'has_schedule' => $hasSchedule,
            'scheduled_in_daily' => $scheduledInDaily,
            'scheduled_in_weekly' => $scheduledInWeekly,
            'is_rescheduled' => $isRescheduled,
            'scheduled_time' => $time,
            'day_name' => $dayName,
            'student_name' => $student->first_name . ' ' . $student->last_name,
        ]);
    }
}
