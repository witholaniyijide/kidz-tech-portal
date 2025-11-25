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

            // Enrich with student data
            $todayClasses = $todayClasses->map(function ($class) use ($students) {
                $student = $students->firstWhere('id', $class['student_id']);
                $class['student'] = $student;
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
                    $weekClasses->push($class);
                }
            }
        }

        return view('tutor.schedule.today', compact('todayClasses', 'weekClasses', 'students'));
    }
}
