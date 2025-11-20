<?php

namespace App\Http\Controllers;

use App\Models\Tutor;
use Illuminate\Http\Request;

class TutorController extends Controller
{
    public function index(Request $request)
    {
        $query = Tutor::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('tutor_id', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('location') && $request->location != '') {
            $query->where('location', $request->location);
        }

        $tutors = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('tutors.index', compact('tutors'));
    }

    public function create()
    {
        return view('tutors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:tutors,email',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female',
            'address' => 'nullable|string',
            'state' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'specializations' => 'required|array|min:1',
            'hire_date' => 'required|date',
            'hourly_rate' => 'nullable|numeric|min:0',
            'qualifications' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $validated['tutor_id'] = $this->generateTutorId();
        $validated['status'] = 'active';

        Tutor::create($validated);

        return redirect()->route('tutors.index')
            ->with('success', 'Tutor added successfully!');
    }

    public function show(Tutor $tutor)
    {
        return view('tutors.show', compact('tutor'));
    }

    public function edit(Tutor $tutor)
    {
        return view('tutors.edit', compact('tutor'));
    }

    public function update(Request $request, Tutor $tutor)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:tutors,email,' . $tutor->id,
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female',
            'address' => 'nullable|string',
            'state' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'specializations' => 'required|array|min:1',
            'hire_date' => 'required|date',
            'status' => 'required|in:active,inactive,on_leave',
            'hourly_rate' => 'nullable|numeric|min:0',
            'qualifications' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $tutor->update($validated);

        return redirect()->route('tutors.show', $tutor)
            ->with('success', 'Tutor updated successfully!');
    }

    public function destroy(Tutor $tutor)
    {
        $tutor->delete();
        return redirect()->route('tutors.index')
            ->with('success', 'Tutor deleted successfully!');
    }

    private function generateTutorId()
    {
        $lastTutor = Tutor::withTrashed()->orderBy('id', 'desc')->first();
        $number = $lastTutor ? intval(substr($lastTutor->tutor_id, 2)) + 1 : 1;
        
        return 'TT' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
