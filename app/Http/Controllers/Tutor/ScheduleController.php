<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\DailyClassSchedule;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    /**
     * Display today's schedule for the tutor.
     */
    public function today()
    {
        // Get the authenticated tutor
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            abort(403, 'You do not have a tutor profile.');
        }

        // Get students assigned to this tutor
        $students = $tutor->students()->active()->get();
        $studentIds = $students->pluck('id')->toArray();

        // Get today's schedule
        $todaySchedule = DailyClassSchedule::where('schedule_date', Carbon::today())
            ->where('status', 'posted')
            ->first();

        $todayClasses = collect();
        if ($todaySchedule && $todaySchedule->classes) {
            // Filter classes for this tutor's students
            $todayClasses = collect($todaySchedule->classes)->filter(function ($class) use ($studentIds) {
                return isset($class['student_id']) && in_array($class['student_id'], $studentIds);
            });

            // Enrich with student data and fallback class time
            $todayClasses = $todayClasses->map(function ($class) use ($students) {
                $student = $students->firstWhere('id', $class['student_id']);
                $class['student'] = $student;

                // If time is not set, get it from student's class_schedule
                if (empty($class['time']) && $student && $student->class_schedule && is_array($student->class_schedule)) {
                    $today = Carbon::today()->format('l'); // Day name (e.g., "Monday")
                    foreach ($student->class_schedule as $schedule) {
                        if (isset($schedule['day']) && $schedule['day'] === $today && isset($schedule['time'])) {
                            $class['time'] = $schedule['time'];
                            break;
                        }
                    }
                }

                return $class;
            });
        }

        // Get this week's schedule
        $weekStart = Carbon::now()->startOfWeek();
        $weekEnd = Carbon::now()->endOfWeek();

        $weekSchedules = DailyClassSchedule::whereBetween('schedule_date', [$weekStart, $weekEnd])
            ->where('status', 'posted')
            ->orderBy('schedule_date')
            ->get();

        $weekClasses = collect();
        foreach ($weekSchedules as $schedule) {
            if ($schedule->classes) {
                $filteredClasses = collect($schedule->classes)->filter(function ($class) use ($studentIds) {
                    return isset($class['student_id']) && in_array($class['student_id'], $studentIds);
                });

                foreach ($filteredClasses as $class) {
                    $class['schedule_date'] = $schedule->schedule_date;
                    $student = $students->firstWhere('id', $class['student_id']);
                    $class['student'] = $student;

                    // If time is not set, get it from student's class_schedule
                    if (empty($class['time']) && $student && $student->class_schedule && is_array($student->class_schedule)) {
                        $dayName = Carbon::parse($schedule->schedule_date)->format('l');
                        foreach ($student->class_schedule as $studentSchedule) {
                            if (isset($studentSchedule['day']) && $studentSchedule['day'] === $dayName && isset($studentSchedule['time'])) {
                                $class['time'] = $studentSchedule['time'];
                                break;
                            }
                        }
                    }

                    $weekClasses->push($class);
                }
            }
        }

        return view('tutor.schedule.today', compact('todayClasses', 'weekClasses', 'students'));
    }
}
