<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentScheduleController extends Controller
{
    /**
     * Display the student's class schedule.
     */
    public function index()
    {
        $user = Auth::user();

        // Get student profile (could be the user themselves or via relationship)
        $student = null;
        if ($user->role === 'student') {
            // If user has student relationship
            $student = Student::where('email', $user->email)->first();
            if (!$student) {
                $student = Student::where('user_id', $user->id)->first();
            }
        }

        if (!$student) {
            return view('student.schedule.index', [
                'weeklySchedule' => [],
                'todayClasses' => [],
                'today' => now()->format('l'),
                'tutor' => null,
            ]);
        }

        // Build weekly schedule from student's class_schedule
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $weeklySchedule = [];

        foreach ($days as $day) {
            $weeklySchedule[$day] = [];
        }

        if ($student->class_schedule && is_array($student->class_schedule)) {
            foreach ($student->class_schedule as $schedule) {
                if (is_array($schedule) && isset($schedule['day'])) {
                    $day = $schedule['day'];
                    if (isset($weeklySchedule[$day])) {
                        $weeklySchedule[$day][] = [
                            'time' => $schedule['time'] ?? 'TBD',
                            'course' => $schedule['course'] ?? $student->current_course ?? 'Coding Class',
                            'tutor' => $student->tutor,
                        ];
                    }
                }
            }
        }

        // Today's classes
        $today = now()->format('l');
        $todayClasses = $weeklySchedule[$today] ?? [];

        return view('student.schedule.index', compact(
            'student',
            'weeklySchedule',
            'todayClasses',
            'today'
        ));
    }
}
