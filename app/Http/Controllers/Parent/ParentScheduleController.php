<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentScheduleController extends Controller
{
    /**
     * Display class schedules for all children.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $children = $user->guardiansOf()->with(['tutor'])->get();

        if ($children->isEmpty()) {
            return view('parent.no-children');
        }

        // Get selected child filter
        $selectedChildId = $request->get('child_id');

        // Get schedules for all children or selected child
        $studentIds = $selectedChildId ? [$selectedChildId] : $children->pluck('id')->toArray();

        // Try to get schedules from Schedule model if it exists
        $schedules = collect();
        try {
            $schedules = Schedule::whereIn('student_id', $studentIds)
                ->with(['student', 'tutor'])
                ->orderByRaw("FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')")
                ->orderBy('start_time')
                ->get()
                ->groupBy('day_of_week');
        } catch (\Exception $e) {
            // If Schedule model doesn't exist, use class_schedule from Student
            $schedules = collect();
        }

        // Build weekly schedule from student's class_schedule if no Schedule model
        $weeklySchedule = [];
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        foreach ($days as $day) {
            $weeklySchedule[$day] = [];
        }

        foreach ($children as $child) {
            if ($selectedChildId && $child->id != $selectedChildId) {
                continue;
            }

            if ($child->class_schedule && is_array($child->class_schedule)) {
                foreach ($child->class_schedule as $schedule) {
                    if (is_array($schedule) && isset($schedule['day'])) {
                        $day = $schedule['day'];
                        if (isset($weeklySchedule[$day])) {
                            $weeklySchedule[$day][] = [
                                'student' => $child,
                                'tutor' => $child->tutor,
                                'time' => $schedule['time'] ?? 'TBD',
                                'course' => $schedule['course'] ?? $child->current_course ?? 'Coding Class',
                            ];
                        }
                    }
                }
            }
        }

        // Today's classes
        $today = now()->format('l');
        $todayClasses = $weeklySchedule[$today] ?? [];

        return view('parent.schedule.index', compact(
            'children',
            'weeklySchedule',
            'todayClasses',
            'selectedChildId',
            'today'
        ));
    }
}
