<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\TutorAvailability;
use App\Models\DailyClassSchedule;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AvailabilityController extends Controller
{
    /**
     * Days of the week in order.
     */
    protected $daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

    /**
     * Display the tutor's weekly availability calendar.
     */
    public function index(Request $request)
    {
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            abort(403, 'You do not have a tutor profile.');
        }

        $tab = $request->get('tab', 'weekly');

        // Get weekly recurring availability
        $weeklyAvailability = $tutor->availabilities()
            ->whereNull('specific_date')
            ->orderByRaw("FIELD(day, 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday')")
            ->orderBy('start_time')
            ->get()
            ->groupBy('day');

        // Get date-specific overrides (upcoming 30 days)
        $dateSpecificAvailability = $tutor->availabilities()
            ->whereNotNull('specific_date')
            ->where('specific_date', '>=', now()->startOfDay())
            ->orderBy('specific_date')
            ->orderBy('start_time')
            ->get()
            ->groupBy(function ($item) {
                return $item->specific_date->format('Y-m-d');
            });

        // Get scheduled classes for this tutor's students (current week for display)
        $studentIds = $tutor->students()->pluck('id')->toArray();
        $students = $tutor->students()->active()->get();
        
        $weekStart = now()->startOfWeek(Carbon::SUNDAY);
        $weekEnd = now()->endOfWeek(Carbon::SATURDAY);
        
        $scheduledClasses = collect();
        $weekSchedules = DailyClassSchedule::whereBetween('schedule_date', [$weekStart, $weekEnd])
            ->where('status', 'posted')
            ->get();

        foreach ($weekSchedules as $schedule) {
            if ($schedule->classes) {
                $filteredClasses = collect($schedule->classes)->filter(function ($class) use ($studentIds, $tutor) {
                    return (isset($class['student_id']) && in_array($class['student_id'], $studentIds))
                        || (isset($class['tutor_id']) && $class['tutor_id'] == $tutor->id);
                });

                foreach ($filteredClasses as $class) {
                    $class['schedule_date'] = $schedule->schedule_date;
                    $class['day_name'] = Carbon::parse($schedule->schedule_date)->format('l');
                    
                    // Get student name
                    if (isset($class['student_id'])) {
                        $student = $students->firstWhere('id', $class['student_id']);
                        $class['student_name'] = $student ? $student->first_name . ' ' . $student->last_name : 'Student';
                    }
                    
                    $scheduledClasses->push($class);
                }
            }
        }

        // Group scheduled classes by day
        $classesByDay = $scheduledClasses->groupBy('day_name');

        // Tutor's timezone (default to West Africa Time)
        $timezone = $tutor->timezone ?? 'Africa/Lagos';

        return view('tutor.availability.index', compact(
            'weeklyAvailability',
            'dateSpecificAvailability',
            'classesByDay',
            'students',
            'timezone',
            'tab'
        ));
    }

    /**
     * Store a newly created availability slot.
     */
    public function store(Request $request)
    {
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            abort(403, 'You do not have a tutor profile.');
        }

        $validated = $request->validate([
            'day' => 'required_without:specific_date|in:Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'specific_date' => 'nullable|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'type' => 'required|in:available,unavailable',
            'notes' => 'nullable|string|max:255',
        ]);

        // Determine the day from specific_date if provided
        if (!empty($validated['specific_date'])) {
            $validated['day'] = Carbon::parse($validated['specific_date'])->format('l');
        }

        // Check for conflicts with existing slots
        $existingSlots = $tutor->availabilities()
            ->where('day', $validated['day'])
            ->when(!empty($validated['specific_date']), function ($q) use ($validated) {
                return $q->where('specific_date', $validated['specific_date']);
            }, function ($q) {
                return $q->whereNull('specific_date');
            })
            ->get();

        foreach ($existingSlots as $slot) {
            if ($slot->conflictsWith($validated['start_time'], $validated['end_time'])) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'This time slot conflicts with an existing availability.');
            }
        }

        TutorAvailability::create([
            'tutor_id' => $tutor->id,
            'day' => $validated['day'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'type' => $validated['type'],
            'specific_date' => $validated['specific_date'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'is_active' => true,
            'timezone' => $tutor->timezone ?? 'Africa/Lagos',
        ]);

        $message = !empty($validated['specific_date']) 
            ? 'Date-specific availability added successfully!' 
            : 'Weekly availability added successfully!';

        return redirect()
            ->route('tutor.availability.index', ['tab' => !empty($validated['specific_date']) ? 'date-specific' : 'weekly'])
            ->with('success', $message);
    }

    /**
     * Update the specified availability slot.
     */
    public function update(Request $request, TutorAvailability $availability)
    {
        $tutor = Auth::user()->tutor;

        if (!$tutor || $availability->tutor_id !== $tutor->id) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'type' => 'required|in:available,unavailable',
            'notes' => 'nullable|string|max:255',
        ]);

        // Check for conflicts (excluding self)
        $existingSlots = $tutor->availabilities()
            ->where('id', '!=', $availability->id)
            ->where('day', $availability->day)
            ->when($availability->specific_date, function ($q) use ($availability) {
                return $q->where('specific_date', $availability->specific_date);
            }, function ($q) {
                return $q->whereNull('specific_date');
            })
            ->get();

        foreach ($existingSlots as $slot) {
            if ($slot->conflictsWith($validated['start_time'], $validated['end_time'])) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'This time slot conflicts with an existing availability.');
            }
        }

        $availability->update($validated);

        return redirect()
            ->route('tutor.availability.index')
            ->with('success', 'Availability updated successfully!');
    }

    /**
     * Remove the specified availability slot.
     */
    public function destroy(TutorAvailability $availability)
    {
        $tutor = Auth::user()->tutor;

        if (!$tutor || $availability->tutor_id !== $tutor->id) {
            abort(403, 'Unauthorized access.');
        }

        $availability->delete();

        return redirect()
            ->route('tutor.availability.index')
            ->with('success', 'Availability removed successfully!');
    }

    /**
     * Duplicate a slot to another day.
     */
    public function duplicate(Request $request, TutorAvailability $availability)
    {
        $tutor = Auth::user()->tutor;

        if (!$tutor || $availability->tutor_id !== $tutor->id) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'target_day' => 'required|in:Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
        ]);

        // Check for conflicts
        $existingSlots = $tutor->availabilities()
            ->where('day', $validated['target_day'])
            ->whereNull('specific_date')
            ->get();

        foreach ($existingSlots as $slot) {
            if ($slot->conflictsWith($availability->start_time, $availability->end_time)) {
                return redirect()
                    ->back()
                    ->with('error', 'Cannot duplicate: time slot conflicts with existing availability on ' . $validated['target_day'] . '.');
            }
        }

        TutorAvailability::create([
            'tutor_id' => $tutor->id,
            'day' => $validated['target_day'],
            'start_time' => $availability->start_time,
            'end_time' => $availability->end_time,
            'type' => $availability->type,
            'notes' => $availability->notes,
            'is_active' => true,
            'timezone' => $availability->timezone,
        ]);

        return redirect()
            ->route('tutor.availability.index')
            ->with('success', 'Availability duplicated to ' . $validated['target_day'] . '!');
    }

    /**
     * Mark an entire day as unavailable.
     */
    public function markDayUnavailable(Request $request)
    {
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            abort(403, 'You do not have a tutor profile.');
        }

        $validated = $request->validate([
            'day' => 'required_without:specific_date|in:Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'specific_date' => 'nullable|date|after_or_equal:today',
        ]);

        $day = $validated['day'] ?? Carbon::parse($validated['specific_date'])->format('l');

        // Delete existing slots for this day
        $query = $tutor->availabilities()->where('day', $day);
        
        if (!empty($validated['specific_date'])) {
            $query->where('specific_date', $validated['specific_date']);
        } else {
            $query->whereNull('specific_date');
        }
        
        $query->delete();

        // Create a single "unavailable" entry for the whole day
        TutorAvailability::create([
            'tutor_id' => $tutor->id,
            'day' => $day,
            'start_time' => '00:00',
            'end_time' => '23:59',
            'type' => 'unavailable',
            'specific_date' => $validated['specific_date'] ?? null,
            'is_active' => true,
            'timezone' => $tutor->timezone ?? 'Africa/Lagos',
        ]);

        return redirect()
            ->route('tutor.availability.index')
            ->with('success', $day . ' marked as unavailable.');
    }

    /**
     * Update timezone preference.
     */
    public function updateTimezone(Request $request)
    {
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            abort(403, 'You do not have a tutor profile.');
        }

        $validated = $request->validate([
            'timezone' => 'required|timezone',
        ]);

        $tutor->update(['timezone' => $validated['timezone']]);

        // Update all availability records
        $tutor->availabilities()->update(['timezone' => $validated['timezone']]);

        return redirect()
            ->route('tutor.availability.index')
            ->with('success', 'Timezone updated to ' . $validated['timezone']);
    }
}
