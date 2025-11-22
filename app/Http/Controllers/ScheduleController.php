<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\DailyClassSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    /**
     * Display today's schedule
     */
    public function index()
    {
        return $this->showToday();
    }

    /**
     * Generate today's schedule from student class_schedule data
     */
    public function generateTodaySchedule()
    {
        $today = Carbon::today();
        $dayName = $today->format('l'); // Monday, Tuesday, etc.

        // Get all active students
        $students = Student::where('status', 'active')
            ->with('tutor')
            ->get();

        $classes = [];

        foreach ($students as $student) {
            // Check if student has a class today
            if (!$student->class_schedule || !is_array($student->class_schedule)) {
                continue;
            }

            // Look for today's schedule in the class_schedule array
            foreach ($student->class_schedule as $schedule) {
                if (isset($schedule['day']) && strtolower($schedule['day']) === strtolower($dayName)) {
                    // Extract time (handle different time formats)
                    $time = $schedule['time'] ?? '';

                    // Convert time to 24-hour format for sorting
                    $sortTime = $this->convertToSortableTime($time);

                    $classes[] = [
                        'student_id' => $student->id,
                        'student_name' => $student->full_name,
                        'tutor_name' => $student->tutor ? $student->tutor->full_name : 'Not Assigned',
                        'time' => $time,
                        'sort_time' => $sortTime,
                    ];
                }
            }
        }

        // Sort by time
        usort($classes, function ($a, $b) {
            return $a['sort_time'] <=> $b['sort_time'];
        });

        return response()->json([
            'day_name' => $dayName,
            'schedule_date' => $today->format('Y-m-d'),
            'classes' => $classes,
            'total_classes' => count($classes),
        ]);
    }

    /**
     * Post/publish today's schedule
     */
    public function postSchedule(Request $request)
    {
        $validated = $request->validate([
            'schedule_date' => 'required|date',
            'day_name' => 'required|string',
            'classes' => 'required|array',
            'footer_note' => 'nullable|string',
        ]);

        $schedule = DailyClassSchedule::updateOrCreate(
            [
                'schedule_date' => $validated['schedule_date'],
            ],
            [
                'day_name' => $validated['day_name'],
                'classes' => $validated['classes'],
                'status' => 'posted',
                'posted_by' => Auth::id(),
                'posted_at' => now(),
                'footer_note' => $validated['footer_note'] ?? 'Have a great day! - Kidz Tech Portal Team',
            ]
        );

        return redirect()->route('schedule.today')
            ->with('success', 'Schedule posted successfully!');
    }

    /**
     * Show today's schedule (auto-generate if doesn't exist)
     */
    public function showToday()
    {
        $today = Carbon::today();
        $dayName = $today->format('l');

        // Try to load existing schedule
        $schedule = DailyClassSchedule::where('schedule_date', $today)->first();

        // If no schedule exists, auto-generate a draft
        if (!$schedule) {
            $students = Student::where('status', 'active')
                ->with('tutor')
                ->get();

            $classes = [];

            foreach ($students as $student) {
                if (!$student->class_schedule || !is_array($student->class_schedule)) {
                    continue;
                }

                foreach ($student->class_schedule as $scheduleItem) {
                    if (isset($scheduleItem['day']) && strtolower($scheduleItem['day']) === strtolower($dayName)) {
                        $time = $scheduleItem['time'] ?? '';
                        $sortTime = $this->convertToSortableTime($time);

                        $classes[] = [
                            'student_id' => $student->id,
                            'student_name' => $student->full_name,
                            'tutor_name' => $student->tutor ? $student->tutor->full_name : 'Not Assigned',
                            'time' => $time,
                            'sort_time' => $sortTime,
                        ];
                    }
                }
            }

            // Sort by time
            usort($classes, function ($a, $b) {
                return $a['sort_time'] <=> $b['sort_time'];
            });

            // Create draft schedule
            $schedule = new DailyClassSchedule([
                'schedule_date' => $today,
                'day_name' => $dayName,
                'classes' => $classes,
                'status' => 'draft',
                'footer_note' => 'Have a great day! - Kidz Tech Portal Team',
            ]);
        }

        return view('schedules.today', compact('schedule'));
    }

    /**
     * Convert time string to sortable 24-hour format
     */
    private function convertToSortableTime($timeString)
    {
        if (empty($timeString)) {
            return '9999'; // Put empty times at the end
        }

        try {
            // Try to parse the time
            $time = Carbon::parse($timeString);
            return $time->format('Hi'); // Returns HHMM format (e.g., 1430 for 2:30 PM)
        } catch (\Exception $e) {
            // If parsing fails, return a high number to sort at the end
            return '9999';
        }
    }
}
