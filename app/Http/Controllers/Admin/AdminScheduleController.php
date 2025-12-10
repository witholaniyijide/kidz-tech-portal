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

        $todaySchedule = DailyClassSchedule::with(['student', 'tutor'])
            ->whereDate('schedule_date', $selectedDate)
            ->orderBy('class_time')
            ->get();

        $schedulePosted = $todaySchedule->whereNotNull('posted_at')->isNotEmpty();

        $weekStart = $selectedDate->copy()->startOfWeek();
        $weekEnd = $selectedDate->copy()->endOfWeek();
        
        $weeklySchedule = DailyClassSchedule::with(['student', 'tutor'])
            ->whereBetween('schedule_date', [$weekStart, $weekEnd])
            ->orderBy('schedule_date')
            ->orderBy('class_time')
            ->get()
            ->groupBy(function($schedule) {
                return Carbon::parse($schedule->schedule_date)->format('l');
            });

        $students = Student::where('status', 'active')->orderBy('first_name')->get();
        $tutors = Tutor::where('status', 'active')->orderBy('first_name')->get();

        return view('admin.schedules.index', compact(
            'todaySchedule', 'schedulePosted', 'weeklySchedule',
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
            $weeklySchedule[$day->format('l')] = DailyClassSchedule::with(['student', 'tutor'])
                ->whereDate('schedule_date', $day)
                ->orderBy('class_time')
                ->get();
        }

        return view('admin.schedules.weekly', compact('weeklySchedule', 'weekStart', 'weekEnd', 'weekOffset'));
    }

    public function create()
    {
        $students = Student::where('status', 'active')->with('tutor')->orderBy('first_name')->get();
        $tutors = Tutor::where('status', 'active')->orderBy('first_name')->get();
        return view('admin.schedules.create', compact('students', 'tutors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'tutor_id' => 'required|exists:tutors,id',
            'schedule_date' => 'required|date',
            'class_time' => 'required',
            'class_link' => 'nullable|url|max:500',
            'notes' => 'nullable|string|max:500',
        ]);

        $schedule = DailyClassSchedule::create($validated);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'created',
            'description' => "Created schedule for " . Carbon::parse($validated['schedule_date'])->format('M j, Y'),
            'model_type' => DailyClassSchedule::class,
            'model_id' => $schedule->id,
        ]);

        return redirect()->route('admin.schedules.index', ['date' => $validated['schedule_date']])
            ->with('success', 'Schedule entry created successfully.');
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
            'student_id' => 'required|exists:students,id',
            'tutor_id' => 'required|exists:tutors,id',
            'schedule_date' => 'required|date',
            'class_time' => 'required',
            'class_link' => 'nullable|url|max:500',
            'status' => 'nullable|in:scheduled,in_progress,completed,cancelled',
            'notes' => 'nullable|string|max:500',
        ]);

        $schedule->update($validated);

        return redirect()->route('admin.schedules.index', ['date' => $validated['schedule_date']])
            ->with('success', 'Schedule updated successfully.');
    }

    public function destroy(DailyClassSchedule $schedule)
    {
        $date = $schedule->schedule_date;
        $schedule->delete();
        return redirect()->route('admin.schedules.index', ['date' => $date])
            ->with('success', 'Schedule entry deleted.');
    }

    public function postSchedule(Request $request)
    {
        $date = $request->get('date', Carbon::today()->toDateString());
        
        DailyClassSchedule::whereDate('schedule_date', $date)->update([
            'posted_at' => now(),
            'posted_by' => Auth::id(),
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'posted_schedule',
            'description' => "Posted schedule for " . Carbon::parse($date)->format('l, M j, Y'),
            'model_type' => DailyClassSchedule::class,
        ]);

        return redirect()->back()->with('success', 'Schedule posted successfully!');
    }

    public function getWhatsAppFormat(Request $request)
    {
        $date = $request->get('date', Carbon::today()->toDateString());
        $selectedDate = Carbon::parse($date);
        
        $schedules = DailyClassSchedule::with(['student', 'tutor'])
            ->whereDate('schedule_date', $selectedDate)
            ->orderBy('class_time')
            ->get();

        $format = "📚 *Classes Scheduled for Today*\n";
        $format .= "📅 " . $selectedDate->format('l, M j, Y') . "\n\n";

        $count = 1;
        foreach ($schedules as $schedule) {
            $studentName = $schedule->student->first_name ?? 'Unknown';
            $tutorName = $schedule->tutor->first_name ?? 'Unknown';
            $time = Carbon::parse($schedule->class_time)->format('g:ia');
            $format .= "{$count}. {$studentName} - {$time} by {$tutorName}\n";
            $count++;
        }

        $format .= "\n✅ Total: " . ($count - 1) . " classes";
        return response()->json(['format' => $format]);
    }

    public function generate(Request $request)
    {
        $date = $request->get('date', Carbon::today()->toDateString());
        $selectedDate = Carbon::parse($date);
        $dayOfWeek = strtolower($selectedDate->format('l'));

        $students = Student::where('status', 'active')
            ->whereNotNull('class_schedule')
            ->with('tutor')
            ->get();

        $created = 0;

        foreach ($students as $student) {
            $classSchedule = $student->class_schedule;
            if (is_string($classSchedule)) {
                $classSchedule = json_decode($classSchedule, true);
            }
            if (!is_array($classSchedule)) continue;

            foreach ($classSchedule as $schedule) {
                if (isset($schedule['day']) && strtolower($schedule['day']) === $dayOfWeek) {
                    $exists = DailyClassSchedule::where('student_id', $student->id)
                        ->whereDate('schedule_date', $selectedDate)
                        ->exists();

                    if (!$exists) {
                        DailyClassSchedule::create([
                            'student_id' => $student->id,
                            'tutor_id' => $student->tutor_id,
                            'schedule_date' => $selectedDate,
                            'class_time' => $schedule['time'] ?? '09:00',
                            'class_link' => $student->class_link,
                        ]);
                        $created++;
                    }
                }
            }
        }

        return redirect()->route('admin.schedules.index', ['date' => $date])
            ->with('success', "Generated {$created} schedule entries.");
    }
}
