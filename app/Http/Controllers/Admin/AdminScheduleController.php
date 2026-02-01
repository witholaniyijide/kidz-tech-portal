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

        // If no schedule exists for this date, check for repeat_weekly schedules from previous weeks
        $classes = [];
        $rescheduledClasses = [];
        $inheritedFromWeekly = false;

        if (!$todaySchedule) {
            // Find a schedule from a previous week on the same day that has repeat_weekly enabled
            try {
                $repeatSchedule = DailyClassSchedule::where('repeat_weekly', true)
                    ->where('day_name', $selectedDate->format('l'))
                    ->whereDate('schedule_date', '<', $selectedDate)
                    ->orderBy('schedule_date', 'desc')
                    ->first();

                if ($repeatSchedule) {
                    $classes = $repeatSchedule->classes ?? [];
                    $inheritedFromWeekly = true;
                }
            } catch (\Exception $e) {
                // repeat_weekly column may not exist yet - migration pending
                $repeatSchedule = null;
            }
        } else {
            $classes = $todaySchedule->classes ?? [];
            $rescheduledClasses = $todaySchedule->rescheduled_classes ?? [];
        }

        $schedulePosted = $todaySchedule && $todaySchedule->posted_at !== null;

        // Sort classes by time
        usort($classes, function($a, $b) {
            return strcmp($a['time'] ?? '00:00', $b['time'] ?? '00:00');
        });

        // Sort rescheduled classes by time
        usort($rescheduledClasses, function($a, $b) {
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

        // For days without schedules, check for repeat_weekly schedules
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        foreach ($daysOfWeek as $day) {
            if (!isset($weeklySchedules[$day])) {
                try {
                    $repeatSchedule = DailyClassSchedule::where('repeat_weekly', true)
                        ->where('day_name', $day)
                        ->whereDate('schedule_date', '<', $weekStart)
                        ->orderBy('schedule_date', 'desc')
                        ->first();

                    if ($repeatSchedule) {
                        // Create a virtual schedule entry for display purposes
                        $weeklySchedules[$day] = $repeatSchedule;
                    }
                } catch (\Exception $e) {
                    // repeat_weekly column may not exist yet - migration pending
                }
            }
        }

        $students = Student::where('status', 'active')->orderBy('first_name')->get();
        $tutors = Tutor::where('status', 'active')->orderBy('first_name')->get();

        return view('admin.schedules.index', compact(
            'todaySchedule', 'schedulePosted', 'classes', 'rescheduledClasses', 'weeklySchedules',
            'selectedDate', 'weekStart', 'weekEnd', 'students', 'tutors', 'inheritedFromWeekly'
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
        $selectedDate = Carbon::parse($date);

        // Check if schedule already exists for this date
        $existingSchedule = DailyClassSchedule::whereDate('schedule_date', $date)->first();
        $inheritedFromWeekly = false;

        // If no schedule exists, check for inherited weekly schedule
        if (!$existingSchedule) {
            $repeatSchedule = DailyClassSchedule::where('repeat_weekly', true)
                ->where('day_name', $selectedDate->format('l'))
                ->whereDate('schedule_date', '<', $selectedDate)
                ->orderBy('schedule_date', 'desc')
                ->first();

            if ($repeatSchedule) {
                $existingSchedule = $repeatSchedule;
                $inheritedFromWeekly = true;
            }
        }

        return view('admin.schedules.create', compact('students', 'tutors', 'date', 'existingSchedule', 'inheritedFromWeekly'));
    }

    public function store(Request $request)
    {
        // Support both old 'classes' format and new 'entries' format
        $entriesKey = $request->has('entries') ? 'entries' : 'classes';

        $validated = $request->validate([
            'schedule_date' => 'required|date',
            'repeat_weekly' => 'nullable|boolean',
            $entriesKey => 'required|array|min:1',
            $entriesKey . '.*.student_id' => 'required|exists:students,id',
            $entriesKey . '.*.tutor_id' => 'required|exists:tutors,id',
            $entriesKey . '.*.start_time' => 'nullable',
            $entriesKey . '.*.end_time' => 'nullable',
            $entriesKey . '.*.time' => 'nullable', // For backwards compatibility
            $entriesKey . '.*.class_link' => 'nullable|url|max:500',
            $entriesKey . '.*.notes' => 'nullable|string|max:500',
            'rescheduled_classes' => 'nullable|array',
            'rescheduled_classes.*.student_id' => 'required|exists:students,id',
            'rescheduled_classes.*.tutor_id' => 'required|exists:tutors,id',
            'rescheduled_classes.*.start_time' => 'nullable',
            'rescheduled_classes.*.end_time' => 'nullable',
            'rescheduled_classes.*.time' => 'nullable',
            'rescheduled_classes.*.class_link' => 'nullable|url|max:500',
            'rescheduled_classes.*.notes' => 'nullable|string|max:500',
            'rescheduled_classes.*.original_date' => 'nullable|date',
            'footer_note' => 'nullable|string|max:500',
        ]);

        $scheduleDate = Carbon::parse($validated['schedule_date']);
        $entries = $validated[$entriesKey] ?? [];
        $rescheduledEntries = $validated['rescheduled_classes'] ?? [];

        // Enrich classes with names
        $classes = [];
        foreach ($entries as $entry) {
            $student = Student::find($entry['student_id']);
            $tutor = Tutor::find($entry['tutor_id']);

            // Support both new format (start_time/end_time) and old format (time)
            $time = $entry['start_time'] ?? $entry['time'] ?? '09:00';
            $endTime = $entry['end_time'] ?? null;

            $classes[] = [
                'student_id' => $entry['student_id'],
                'tutor_id' => $entry['tutor_id'],
                'student_name' => $student ? $student->first_name . ' ' . $student->last_name : 'Unknown',
                'tutor_name' => $tutor ? $tutor->first_name . ' ' . $tutor->last_name : 'Unknown',
                'time' => $time,
                'end_time' => $endTime,
                'class_link' => $entry['class_link'] ?? null,
                'notes' => $entry['notes'] ?? null,
            ];
        }

        // Sort classes by time
        usort($classes, function($a, $b) {
            return strcmp($a['time'], $b['time']);
        });

        // Process rescheduled classes
        $rescheduledClasses = [];
        foreach ($rescheduledEntries as $entry) {
            $student = Student::find($entry['student_id']);
            $tutor = Tutor::find($entry['tutor_id']);

            $time = $entry['start_time'] ?? $entry['time'] ?? '09:00';
            $endTime = $entry['end_time'] ?? null;

            $rescheduledClasses[] = [
                'student_id' => $entry['student_id'],
                'tutor_id' => $entry['tutor_id'],
                'student_name' => $student ? $student->first_name . ' ' . $student->last_name : 'Unknown',
                'tutor_name' => $tutor ? $tutor->first_name . ' ' . $tutor->last_name : 'Unknown',
                'time' => $time,
                'end_time' => $endTime,
                'class_link' => $entry['class_link'] ?? null,
                'notes' => $entry['notes'] ?? null,
                'original_date' => $entry['original_date'] ?? null,
            ];
        }

        // Sort rescheduled classes by time
        usort($rescheduledClasses, function($a, $b) {
            return strcmp($a['time'], $b['time']);
        });

        $schedule = DailyClassSchedule::updateOrCreate(
            ['schedule_date' => $scheduleDate->toDateString()],
            [
                'day_name' => $scheduleDate->format('l'),
                'classes' => $classes,
                'rescheduled_classes' => $rescheduledClasses,
                'footer_note' => $validated['footer_note'] ?? null,
                'repeat_weekly' => $request->boolean('repeat_weekly'),
            ]
        );

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'created',
            'description' => "Created/updated schedule for " . $scheduleDate->format('M j, Y'),
            'model_type' => DailyClassSchedule::class,
            'model_id' => $schedule->id,
        ]);

        $totalEntries = count($classes) + count($rescheduledClasses);
        $message = 'Schedule saved successfully with ' . count($classes) . ' regular class(es)';
        if (count($rescheduledClasses) > 0) {
            $message .= ' and ' . count($rescheduledClasses) . ' rescheduled class(es)';
        }
        $message .= '.';

        return redirect()->route('admin.schedules.index', ['date' => $validated['schedule_date']])
            ->with('success', $message);
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
            'classes.*.end_time' => 'nullable',
            'classes.*.class_link' => 'nullable|url|max:500',
            'classes.*.notes' => 'nullable|string|max:500',
            'rescheduled_classes' => 'nullable|array',
            'rescheduled_classes.*.student_id' => 'required|exists:students,id',
            'rescheduled_classes.*.tutor_id' => 'required|exists:tutors,id',
            'rescheduled_classes.*.time' => 'required',
            'rescheduled_classes.*.end_time' => 'nullable',
            'rescheduled_classes.*.class_link' => 'nullable|url|max:500',
            'rescheduled_classes.*.original_date' => 'nullable|date',
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
                    'end_time' => $class['end_time'] ?? null,
                    'class_link' => $class['class_link'] ?? null,
                    'notes' => $class['notes'] ?? null,
                ];
            }
        }

        // Sort classes by time
        usort($classes, function($a, $b) {
            return strcmp($a['time'], $b['time']);
        });

        // Process rescheduled classes
        $rescheduledClasses = [];
        if (!empty($validated['rescheduled_classes'])) {
            foreach ($validated['rescheduled_classes'] as $class) {
                $student = Student::find($class['student_id']);
                $tutor = Tutor::find($class['tutor_id']);
                $rescheduledClasses[] = [
                    'student_id' => $class['student_id'],
                    'tutor_id' => $class['tutor_id'],
                    'student_name' => $student ? $student->first_name . ' ' . $student->last_name : 'Unknown',
                    'tutor_name' => $tutor ? $tutor->first_name . ' ' . $tutor->last_name : 'Unknown',
                    'time' => $class['time'],
                    'end_time' => $class['end_time'] ?? null,
                    'class_link' => $class['class_link'] ?? null,
                    'original_date' => $class['original_date'] ?? null,
                ];
            }
        }

        // Sort rescheduled classes by time
        usort($rescheduledClasses, function($a, $b) {
            return strcmp($a['time'], $b['time']);
        });

        $schedule->update([
            'classes' => $classes,
            'rescheduled_classes' => $rescheduledClasses,
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
        $selectedDate = Carbon::parse($date);

        $schedule = DailyClassSchedule::whereDate('schedule_date', $date)->first();

        // If no schedule exists, check for inherited weekly schedule
        if (!$schedule) {
            $repeatSchedule = DailyClassSchedule::where('repeat_weekly', true)
                ->where('day_name', $selectedDate->format('l'))
                ->whereDate('schedule_date', '<', $selectedDate)
                ->orderBy('schedule_date', 'desc')
                ->first();

            if ($repeatSchedule) {
                // Create a new schedule from the inherited weekly schedule
                $schedule = DailyClassSchedule::create([
                    'schedule_date' => $selectedDate->toDateString(),
                    'day_name' => $selectedDate->format('l'),
                    'classes' => $repeatSchedule->classes,
                    'rescheduled_classes' => [],
                    'footer_note' => $repeatSchedule->footer_note,
                    'repeat_weekly' => false, // Don't repeat the newly created schedule
                    'status' => 'posted',
                    'posted_at' => now(),
                    'posted_by' => Auth::id(),
                ]);

                ActivityLog::create([
                    'user_id' => Auth::id(),
                    'action' => 'posted_schedule',
                    'description' => "Created and posted schedule for " . $selectedDate->format('l, M j, Y') . " from weekly template",
                    'model_type' => DailyClassSchedule::class,
                    'model_id' => $schedule->id,
                ]);

                return redirect()->back()->with('success', 'Schedule created from weekly template and posted successfully!');
            }

            return redirect()->back()->with('error', 'No schedule found for this date.');
        }

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

    public function getWhatsAppFormat(Request $request)
    {
        $date = $request->get('date', Carbon::today()->toDateString());
        $selectedDate = Carbon::parse($date);

        $schedule = DailyClassSchedule::whereDate('schedule_date', $selectedDate)->first();
        $classes = [];
        $rescheduledClasses = [];

        if ($schedule) {
            $classes = $schedule->classes ?? [];
            $rescheduledClasses = $schedule->rescheduled_classes ?? [];
        } else {
            // Check for inherited weekly schedule
            $repeatSchedule = DailyClassSchedule::where('repeat_weekly', true)
                ->where('day_name', $selectedDate->format('l'))
                ->whereDate('schedule_date', '<', $selectedDate)
                ->orderBy('schedule_date', 'desc')
                ->first();

            if ($repeatSchedule) {
                $classes = $repeatSchedule->classes ?? [];
            }
        }

        // Sort classes by time
        usort($classes, function($a, $b) {
            return strcmp($a['time'] ?? '00:00', $b['time'] ?? '00:00');
        });

        usort($rescheduledClasses, function($a, $b) {
            return strcmp($a['time'] ?? '00:00', $b['time'] ?? '00:00');
        });

        $format = "📚 *Classes Scheduled for Today*\n";
        $format .= "📅 " . $selectedDate->format('l, M j, Y') . "\n\n";

        if (count($classes) > 0) {
            $format .= "*Regular Classes:*\n";
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
        }

        if (count($rescheduledClasses) > 0) {
            $format .= "\n*Rescheduled Classes:*\n";
            $count = 1;
            foreach ($rescheduledClasses as $class) {
                $studentName = $class['student_name'] ?? 'Unknown';
                $tutorName = $class['tutor_name'] ?? 'Unknown';
                $time = $class['time'] ?? '00:00';

                // Format time nicely
                try {
                    $time = Carbon::parse($time)->format('g:ia');
                } catch (\Exception $e) {
                    // Keep original time if parsing fails
                }

                $originalDate = '';
                if (isset($class['original_date'])) {
                    try {
                        $originalDate = ' (was ' . Carbon::parse($class['original_date'])->format('M j') . ')';
                    } catch (\Exception $e) {
                        $originalDate = '';
                    }
                }

                $format .= "{$count}. {$studentName} - {$time} by {$tutorName}{$originalDate}\n";
                $count++;
            }
        }

        $totalClasses = count($classes) + count($rescheduledClasses);
        $format .= "\n✅ Total: {$totalClasses} class" . ($totalClasses !== 1 ? 'es' : '');

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
