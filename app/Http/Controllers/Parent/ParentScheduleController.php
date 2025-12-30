<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\DailyClassSchedule;
use App\Models\Message;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
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

        // Build weekly schedule from student's class_schedule (default schedule)
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
                                'duration' => $schedule['duration'] ?? '1 hour',
                                'course' => $schedule['course'] ?? $child->current_course ?? 'Coding Class',
                                'class_link' => $child->class_link,
                                'google_classroom_link' => $child->google_classroom_link,
                                'from_daily_schedule' => false,
                            ];
                        }
                    }
                }
            }
        }

        // Check if admin has posted today's schedule
        $today = now()->format('l');
        $todayPostedSchedule = DailyClassSchedule::where('schedule_date', Carbon::today())
            ->where('status', 'posted')
            ->first();

        $schedulePosted = false;
        $todayClasses = [];

        if ($todayPostedSchedule && $todayPostedSchedule->classes) {
            $schedulePosted = true;
            // Filter classes for this parent's children from posted schedule
            $postedClasses = collect($todayPostedSchedule->classes)->filter(function ($class) use ($studentIds) {
                return isset($class['student_id']) && in_array($class['student_id'], $studentIds);
            });

            // Use posted classes for today (these take priority)
            foreach ($postedClasses as $class) {
                $child = $children->firstWhere('id', $class['student_id']);
                if ($child) {
                    $todayClasses[] = [
                        'student' => $child,
                        'tutor' => $child->tutor,
                        'time' => isset($class['class_time']) ? Carbon::parse($class['class_time'])->format('g:i A') : 'TBD',
                        'duration' => $class['duration'] ?? '1 hour',
                        'course' => $class['course'] ?? $child->current_course ?? 'Coding Class',
                        'class_link' => $class['class_link'] ?? $child->class_link,
                        'google_classroom_link' => $child->google_classroom_link,
                        'from_daily_schedule' => true,
                    ];
                }
            }
        } else {
            // Fall back to default weekly schedule for today
            $todayClasses = $weeklySchedule[$today] ?? [];
        }

        // Get this week's posted schedules for the weekly view
        $weekStart = Carbon::now()->startOfWeek();
        $weekEnd = Carbon::now()->endOfWeek();

        $weekPostedSchedules = DailyClassSchedule::whereBetween('schedule_date', [$weekStart, $weekEnd])
            ->where('status', 'posted')
            ->get();

        // Override weekly schedule with posted schedules where available
        foreach ($weekPostedSchedules as $postedSchedule) {
            if ($postedSchedule->classes) {
                $dayName = $postedSchedule->schedule_date->format('l');
                $postedClasses = collect($postedSchedule->classes)->filter(function ($class) use ($studentIds) {
                    return isset($class['student_id']) && in_array($class['student_id'], $studentIds);
                });

                if ($postedClasses->isNotEmpty()) {
                    // Replace default schedule with posted schedule for this day
                    $weeklySchedule[$dayName] = [];
                    foreach ($postedClasses as $class) {
                        $child = $children->firstWhere('id', $class['student_id']);
                        if ($child) {
                            $weeklySchedule[$dayName][] = [
                                'student' => $child,
                                'tutor' => $child->tutor,
                                'time' => isset($class['class_time']) ? Carbon::parse($class['class_time'])->format('g:i A') : 'TBD',
                                'duration' => $class['duration'] ?? '1 hour',
                                'course' => $class['course'] ?? $child->current_course ?? 'Coding Class',
                                'class_link' => $class['class_link'] ?? $child->class_link,
                                'google_classroom_link' => $child->google_classroom_link,
                                'from_daily_schedule' => true,
                                'schedule_date' => $postedSchedule->schedule_date->format('M d'),
                            ];
                        }
                    }
                }
            }
        }

        return view('parent.schedule.index', compact(
            'children',
            'weeklySchedule',
            'todayClasses',
            'selectedChildId',
            'today',
            'schedulePosted'
        ));
    }

    /**
     * Request a schedule change for a child.
     */
    public function requestScheduleChange(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'message' => 'required|string|max:1000',
        ]);

        $user = Auth::user();
        $student = Student::findOrFail($request->student_id);

        // Verify parent owns this student
        abort_unless($user->isGuardianOf($student), 403, 'Unauthorized');

        // Find director to send message to
        $director = User::whereHas('roles', function ($query) {
            $query->where('name', 'director');
        })->first();

        if (!$director) {
            return response()->json(['error' => 'No director found to send message to'], 500);
        }

        // Create message to director
        Message::create([
            'sender_id' => $user->id,
            'recipient_id' => $director->id,
            'student_id' => $student->id,
            'subject' => 'Schedule Change Request for ' . $student->first_name . ' ' . $student->last_name,
            'body' => $request->message,
        ]);

        return response()->json(['success' => true, 'message' => 'Your schedule change request has been sent to the Director.']);
    }

    /**
     * Toggle class reminder notification for a child.
     */
    public function toggleClassReminder(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'enabled' => 'required|boolean',
            'minutes_before' => 'nullable|integer|min:5|max:60',
        ]);

        $user = Auth::user();
        $student = Student::findOrFail($request->student_id);

        // Verify parent owns this student
        abort_unless($user->isGuardianOf($student), 403, 'Unauthorized');

        $student->update([
            'class_reminder_enabled' => $request->enabled,
            'class_reminder_minutes' => $request->minutes_before ?? 30,
        ]);

        $message = $request->enabled
            ? 'Class reminder enabled. You will be notified ' . ($request->minutes_before ?? 30) . ' minutes before class.'
            : 'Class reminder disabled.';

        return response()->json(['success' => true, 'message' => $message]);
    }
}
