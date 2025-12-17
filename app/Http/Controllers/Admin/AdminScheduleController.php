<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyClassSchedule;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminScheduleController extends Controller
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
     * Display today's schedule and weekly view.
     */
    public function index(Request $request)
    {
        $date = $request->get('date', Carbon::today()->toDateString());
        $selectedDate = Carbon::parse($date);

        // Get schedule for selected date (one row per day)
        $todaySchedule = DailyClassSchedule::whereDate('schedule_date', $selectedDate)->first();

        $schedulePosted = $todaySchedule && $todaySchedule->posted_at !== null;
        $classes = $todaySchedule ? ($todaySchedule->classes ?? []) : [];

        // Sort classes by time
        usort($classes, function($a, $b) {
            return strcmp($a['time'] ?? '00:00', $b['time'] ?? '00:00');
        });

        // Get weekly schedules
        $weekStart = $selectedDate->copy()->startOfWeek();
        $weekEnd = $selectedDate->copy()->endOfWeek();

        $weeklySchedules = DailyClassSchedule::whereBetween('schedule_date', [$weekStart, $weekEnd])
            ->orderBy('schedule_date')
            ->get()
            ->keyBy(function($schedule) {
                return Carbon::parse($schedule->schedule_date)->format('l');
            });

        $students = Student::where('status', 'active')->orderBy('first_name')->get();
        $tutors = Tutor::where('status', 'active')->orderBy('first_name')->get();

        return view('admin.schedules.index', compact(
            'todaySchedule', 'schedulePosted', 'classes', 'weeklySchedules',
            'selectedDate', 'weekStart', 'weekEnd', 'students', 'tutors'
        ));
    }

    public function weekly(Request $request)
    {
        $weekOffset = $request->get('week', 0);
        $weekStart = Carbon::today()->startOfWeek()->addWeeks($weekOffset);
        $weekEnd = $weekStart->copy()->endOfWeek();

        $weeklySchedule = [];
        for ($i = 0; $i < 7; $i++) {
            $day = $weekStart->copy()->addDays($i);
            $dayName = $day->format('l');
            $schedule = DailyClassSchedule::whereDate('schedule_date', $day)->first();
            $classes = $schedule ? ($schedule->classes ?? []) : [];

            // Sort classes by time
            usort($classes, function($a, $b) {
                return strcmp($a['time'] ?? '00:00', $b['time'] ?? '00:00');
            });

            $weeklySchedule[$dayName] = [
                'date' => $day,
                'schedule' => $schedule,
                'classes' => $classes,
                'total' => count($classes),
            ];
        }

        return view('admin.schedules.weekly', compact('weeklySchedule', 'weekStart', 'weekEnd', 'weekOffset'));
    }

    public function create()
    {
        $students = Student::where('status', 'active')->with('tutor')->orderBy('first_name')->get();
        $tutors = Tutor::where('status', 'active')->orderBy('first_name')->get();
        $date = request('date', Carbon::today()->toDateString());

        // Check if schedule already exists for this date
        $existingSchedule = DailyClassSchedule::whereDate('schedule_date', $date)->first();

        return view('admin.schedules.create', compact('students', 'tutors', 'date', 'existingSchedule'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'schedule_date' => 'required|date',
            'classes' => 'nullable|array',
            'classes.*.student_id' => 'required|exists:students,id',
            'classes.*.tutor_id' => 'required|exists:tutors,id',
            'classes.*.time' => 'required',
            'classes.*.class_link' => 'nullable|url|max:500',
            'classes.*.notes' => 'nullable|string|max:500',
            'footer_note' => 'nullable|string|max:500',
        ]);

        $scheduleDate = Carbon::parse($validated['schedule_date']);

        // Enrich classes with names
        $classes = [];
        if (!empty($validated['classes'])) {
            foreach ($validated['classes'] as $class) {
                $student = Student::find($class['student_id']);
                $tutor = Tutor::find($class['tutor_id']);
                $classes[] = [
                    'student_id' => $class['student_id'],
                    'tutor_id' => $class['tutor_id'],
                    'student_name' => $student ? $student->first_name . ' ' . $student->last_name : 'Unknown',
                    'tutor_name' => $tutor ? $tutor->first_name . ' ' . $tutor->last_name : 'Unknown',
                    'time' => $class['time'],
                    'class_link' => $class['class_link'] ?? null,
                    'notes' => $class['notes'] ?? null,
                ];
            }
        }

        // Sort classes by time
        usort($classes, function($a, $b) {
            return strcmp($a['time'], $b['time']);
        });

        $schedule = DailyClassSchedule::updateOrCreate(
            ['schedule_date' => $scheduleDate->toDateString()],
            [
                'day_name' => $scheduleDate->format('l'),
                'classes' => $classes,
                'footer_note' => $validated['footer_note'] ?? null,
            ]
        );

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'created',
            'description' => "Created/updated schedule for " . $scheduleDate->format('M j, Y'),
            'model_type' => DailyClassSchedule::class,
            'model_id' => $schedule->id,
        ]);

        return redirect()->route('admin.schedules.index', ['date' => $validated['schedule_date']])
            ->with('success', 'Schedule saved successfully.');
    }

    public function edit(DailyClassSchedule $schedule)
    {
        $students = Student::where('status', 'active')->orderBy('first_name')->get();
        $tutors = Tutor::where('status', 'active')->orderBy('first_name')->get();

        return view('admin.schedules.edit', compact('schedule', 'students', 'tutors'));
    }

    public function update(Request $request, DailyClassSchedule $schedule)
    {
        $validated = $request->validate([
            'classes' => 'nullable|array',
            'classes.*.student_id' => 'required|exists:students,id',
            'classes.*.tutor_id' => 'required|exists:tutors,id',
            'classes.*.time' => 'required',
            'classes.*.class_link' => 'nullable|url|max:500',
            'classes.*.notes' => 'nullable|string|max:500',
            'footer_note' => 'nullable|string|max:500',
        ]);

        // Enrich classes with names
        $classes = [];
        if (!empty($validated['classes'])) {
            foreach ($validated['classes'] as $class) {
                $student = Student::find($class['student_id']);
                $tutor = Tutor::find($class['tutor_id']);
                $classes[] = [
                    'student_id' => $class['student_id'],
                    'tutor_id' => $class['tutor_id'],
                    'student_name' => $student ? $student->first_name . ' ' . $student->last_name : 'Unknown',
                    'tutor_name' => $tutor ? $tutor->first_name . ' ' . $tutor->last_name : 'Unknown',
                    'time' => $class['time'],
                    'class_link' => $class['class_link'] ?? null,
                    'notes' => $class['notes'] ?? null,
                ];
            }
        }

        // Sort classes by time
        usort($classes, function($a, $b) {
            return strcmp($a['time'], $b['time']);
        });

        $schedule->update([
            'classes' => $classes,
            'footer_note' => $validated['footer_note'] ?? null,
        ]);

        return redirect()->route('admin.schedules.index', ['date' => $schedule->schedule_date->toDateString()])
            ->with('success', 'Schedule updated successfully.');
    }

    public function destroy(DailyClassSchedule $schedule)
    {
        $date = $schedule->schedule_date;
        $schedule->delete();

        return redirect()->route('admin.schedules.index', ['date' => $date->toDateString()])
            ->with('success', 'Schedule deleted.');
    }

    public function postSchedule(Request $request)
    {
        $date = $request->get('date', Carbon::today()->toDateString());

        $schedule = DailyClassSchedule::whereDate('schedule_date', $date)->first();

        if ($schedule) {
            $schedule->update([
                'status' => 'posted',
                'posted_at' => now(),
                'posted_by' => Auth::id(),
            ]);

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'posted_schedule',
                'description' => "Posted schedule for " . Carbon::parse($date)->format('l, M j, Y'),
                'model_type' => DailyClassSchedule::class,
                'model_id' => $schedule->id,
            ]);

            return redirect()->back()->with('success', 'Schedule posted successfully!');
        }

        return redirect()->back()->with('error', 'No schedule found for this date.');
    }

    public function getWhatsAppFormat(Request $request)
    {
        $date = $request->get('date', Carbon::today()->toDateString());
        $selectedDate = Carbon::parse($date);

        $schedule = DailyClassSchedule::whereDate('schedule_date', $selectedDate)->first();
        $classes = $schedule ? ($schedule->classes ?? []) : [];

        // Sort classes by time
        usort($classes, function($a, $b) {
            return strcmp($a['time'] ?? '00:00', $b['time'] ?? '00:00');
        });

        $format = "📚 *Classes Scheduled for Today*\n";
        $format .= "📅 " . $selectedDate->format('l, M j, Y') . "\n\n";

        $count = 1;
        foreach ($classes as $class) {
            $studentName = $class['student_name'] ?? 'Unknown';
            $tutorName = $class['tutor_name'] ?? 'Unknown';
            $time = $class['time'] ?? '00:00';

            // Format time nicely
            try {
                $time = Carbon::parse($time)->format('g:ia');
            } catch (\Exception $e) {
                // Keep original time if parsing fails
            }

            $format .= "{$count}. {$studentName} - {$time} by {$tutorName}\n";
            $count++;
        }

        $format .= "\n✅ Total: " . ($count - 1) . " classes";

        if ($schedule && $schedule->footer_note) {
            $format .= "\n\n📌 " . $schedule->footer_note;
        }

        return response()->json(['format' => $format]);
    }

    public function generate(Request $request)
    {
        $date = $request->get('date', Carbon::today()->toDateString());
        $selectedDate = Carbon::parse($date);
        $dayOfWeek = strtolower($selectedDate->format('l'));

        // Get students with class schedules
        $students = Student::where('status', 'active')
            ->whereNotNull('class_schedule')
            ->with('tutor')
            ->get();

        $classes = [];

        foreach ($students as $student) {
            $classSchedule = $student->class_schedule;
            if (is_string($classSchedule)) {
                $classSchedule = json_decode($classSchedule, true);
            }
            if (!is_array($classSchedule)) continue;

            foreach ($classSchedule as $schedule) {
                if (isset($schedule['day']) && strtolower($schedule['day']) === $dayOfWeek) {
                    $classes[] = [
                        'student_id' => $student->id,
                        'tutor_id' => $student->tutor_id,
                        'student_name' => $student->first_name . ' ' . $student->last_name,
                        'tutor_name' => $student->tutor ? $student->tutor->first_name . ' ' . $student->tutor->last_name : 'Unassigned',
                        'time' => $schedule['time'] ?? '09:00',
                        'class_link' => $student->class_link ?? null,
                        'notes' => null,
                    ];
                }
            }
        }

        // Sort classes by time
        usort($classes, function($a, $b) {
            return strcmp($a['time'], $b['time']);
        });

        // Create or update the schedule for the day
        $schedule = DailyClassSchedule::updateOrCreate(
            ['schedule_date' => $selectedDate->toDateString()],
            [
                'day_name' => $selectedDate->format('l'),
                'classes' => $classes,
            ]
        );

        return redirect()->route('admin.schedules.index', ['date' => $date])
            ->with('success', "Generated schedule with " . count($classes) . " classes.");
    }
}
