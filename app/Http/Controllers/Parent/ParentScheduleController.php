<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Student;
use App\Models\User;
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

        // Build weekly schedule from student's class_schedule
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
