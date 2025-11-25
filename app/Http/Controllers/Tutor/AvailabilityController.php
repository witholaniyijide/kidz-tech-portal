<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tutor\StoreAvailabilityRequest;
use App\Models\TutorAvailability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvailabilityController extends Controller
{
    /**
     * Display a listing of the tutor's availability.
     */
    public function index()
    {
        // Get the authenticated tutor
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            abort(403, 'You do not have a tutor profile.');
        }

        // Get all availabilities for this tutor
        $availabilities = $tutor->availabilities()
            ->orderByRaw("FIELD(day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')")
            ->orderBy('start_time')
            ->get();

        // Days of week for dropdown
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        return view('tutor.availability.index', compact('availabilities', 'daysOfWeek'));
    }

    /**
     * Store a newly created availability in storage.
     */
    public function store(StoreAvailabilityRequest $request)
    {
        // Get the authenticated tutor
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            abort(403, 'You do not have a tutor profile.');
        }

        // Create availability
        TutorAvailability::create([
            'tutor_id' => $tutor->id,
            'day' => $request->day,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'notes' => $request->notes,
            'is_active' => $request->is_active ?? true,
        ]);

        return redirect()
            ->route('tutor.availability.index')
            ->with('success', 'Availability added successfully!');
    }

    /**
     * Update the specified availability in storage.
     */
    public function update(Request $request, TutorAvailability $availability)
    {
        // Get the authenticated tutor
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            abort(403, 'You do not have a tutor profile.');
        }

        // Verify availability belongs to this tutor
        if ($availability->tutor_id !== $tutor->id) {
            abort(403, 'Unauthorized access to this availability.');
        }

        // Validate request
        $validated = $request->validate([
            'day' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'notes' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        // Update availability
        $availability->update($validated);

        return redirect()
            ->route('tutor.availability.index')
            ->with('success', 'Availability updated successfully!');
    }

    /**
     * Remove the specified availability from storage.
     */
    public function destroy(TutorAvailability $availability)
    {
        // Get the authenticated tutor
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            abort(403, 'You do not have a tutor profile.');
        }

        // Verify availability belongs to this tutor
        if ($availability->tutor_id !== $tutor->id) {
            abort(403, 'Unauthorized access to this availability.');
        }

        $availability->delete();

        return redirect()
            ->route('tutor.availability.index')
            ->with('success', 'Availability removed successfully!');
    }
}
