<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\MonthlyClassSchedule;
use App\Models\Student;
use App\Models\AttendanceRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MonthlyScheduleController extends Controller
{
    /**
     * Display the monthly class schedules for the tutor.
     */
    public function index(Request $request)
    {
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            abort(403, 'You do not have a tutor profile.');
        }

        // Get current month/year or from request
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        // Get all students assigned to this tutor
        $students = $tutor->students()->active()->orderBy('first_name')->get();

        // Get monthly schedules for this tutor's students
        $schedules = MonthlyClassSchedule::where('tutor_id', $tutor->id)
            ->where('year', $year)
            ->where('month', $month)
            ->with('student')
            ->get()
            ->keyBy('student_id');

        // Get approved attendance counts for this month
        $attendanceCounts = AttendanceRecord::where('tutor_id', $tutor->id)
            ->where('status', 'approved')
            ->whereMonth('class_date', $month)
            ->whereYear('class_date', $year)
            ->selectRaw('student_id, COUNT(*) as count')
            ->groupBy('student_id')
            ->pluck('count', 'student_id');

        // Build the schedule data
        $scheduleData = [];
        foreach ($students as $student) {
            $schedule = $schedules->get($student->id);
            $completedClasses = $attendanceCounts->get($student->id, 0);

            $scheduleData[] = [
                'student' => $student,
                'schedule' => $schedule,
                'total_classes' => $schedule ? $schedule->total_classes : 0,
                'completed_classes' => $completedClasses,
                'class_days' => $schedule ? $schedule->class_days : [],
                'has_schedule' => $schedule !== null,
            ];
        }

        $monthName = Carbon::create($year, $month, 1)->format('F Y');

        return view('tutor.monthly-schedule.index', compact(
            'scheduleData',
            'year',
            'month',
            'monthName',
            'students'
        ));
    }

    /**
     * Auto-generate monthly schedules for all students.
     */
    public function generate(Request $request)
    {
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            abort(403, 'You do not have a tutor profile.');
        }

        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        $students = $tutor->students()->active()->get();
        $generated = 0;

        foreach ($students as $student) {
            // Get class schedule from student profile
            $classSchedule = $student->class_schedule;
            if (is_string($classSchedule)) {
                $classSchedule = json_decode($classSchedule, true);
            }

            if (empty($classSchedule)) {
                continue;
            }

            // Extract class days
            $classDays = [];
            foreach ($classSchedule as $schedule) {
                if (isset($schedule['day'])) {
                    $classDays[] = ucfirst(strtolower($schedule['day']));
                }
            }

            if (empty($classDays)) {
                continue;
            }

            // Calculate total classes for the month
            $totalClasses = MonthlyClassSchedule::calculateTotalClasses($year, $month, $classDays);

            // Get current approved attendance count
            $completedClasses = AttendanceRecord::where('tutor_id', $tutor->id)
                ->where('student_id', $student->id)
                ->where('status', 'approved')
                ->whereMonth('class_date', $month)
                ->whereYear('class_date', $year)
                ->count();

            // Create or update monthly schedule
            MonthlyClassSchedule::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'year' => $year,
                    'month' => $month,
                ],
                [
                    'tutor_id' => $tutor->id,
                    'total_classes' => $totalClasses,
                    'completed_classes' => $completedClasses,
                    'class_days' => $classDays,
                ]
            );

            $generated++;
        }

        return redirect()->route('tutor.monthly-schedule.index', ['year' => $year, 'month' => $month])
            ->with('success', "Generated monthly schedules for {$generated} student(s).");
    }

    /**
     * Update a single student's monthly schedule.
     */
    public function update(Request $request, Student $student)
    {
        $tutor = Auth::user()->tutor;

        if (!$tutor || $student->tutor_id !== $tutor->id) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'year' => 'required|integer',
            'month' => 'required|integer|min:1|max:12',
            'total_classes' => 'required|integer|min:0|max:31',
            'class_days' => 'nullable|array',
            'class_days.*' => 'string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'notes' => 'nullable|string|max:500',
        ]);

        // Get current approved attendance count
        $completedClasses = AttendanceRecord::where('tutor_id', $tutor->id)
            ->where('student_id', $student->id)
            ->where('status', 'approved')
            ->whereMonth('class_date', $validated['month'])
            ->whereYear('class_date', $validated['year'])
            ->count();

        MonthlyClassSchedule::updateOrCreate(
            [
                'student_id' => $student->id,
                'year' => $validated['year'],
                'month' => $validated['month'],
            ],
            [
                'tutor_id' => $tutor->id,
                'total_classes' => $validated['total_classes'],
                'completed_classes' => $completedClasses,
                'class_days' => $validated['class_days'] ?? [],
                'notes' => $validated['notes'],
            ]
        );

        return redirect()->route('tutor.monthly-schedule.index', [
            'year' => $validated['year'],
            'month' => $validated['month'],
        ])->with('success', "Updated monthly schedule for {$student->first_name} {$student->last_name}.");
    }

    /**
     * Show the edit form for a student's monthly schedule.
     */
    public function edit(Request $request, Student $student)
    {
        $tutor = Auth::user()->tutor;

        if (!$tutor || $student->tutor_id !== $tutor->id) {
            abort(403, 'Unauthorized access.');
        }

        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        $schedule = MonthlyClassSchedule::where('student_id', $student->id)
            ->where('year', $year)
            ->where('month', $month)
            ->first();

        // Get class schedule from student profile for suggestions
        $studentClassSchedule = $student->class_schedule;
        if (is_string($studentClassSchedule)) {
            $studentClassSchedule = json_decode($studentClassSchedule, true);
        }

        $suggestedDays = [];
        if (!empty($studentClassSchedule)) {
            foreach ($studentClassSchedule as $s) {
                if (isset($s['day'])) {
                    $suggestedDays[] = ucfirst(strtolower($s['day']));
                }
            }
        }

        $monthName = Carbon::create($year, $month, 1)->format('F Y');

        return view('tutor.monthly-schedule.edit', compact(
            'student',
            'schedule',
            'year',
            'month',
            'monthName',
            'suggestedDays'
        ));
    }
}
