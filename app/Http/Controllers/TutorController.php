<?php

namespace App\Http\Controllers;

use App\Models\Tutor;
use App\Models\User;
use App\Http\Requests\StoreTutorRequest;
use App\Http\Requests\UpdateTutorRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

    public function store(StoreTutorRequest $request)
    {
        $data = $request->validated();

        // Generate tutor ID
        $data['tutor_id'] = $this->generateTutorId();

        // Set default status if not provided
        $data['status'] = $data['status'] ?? 'active';

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $filename = 'tutor_' . Str::slug($data['email']) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('profile_photos', $filename, 'public');
            $data['profile_photo'] = $path;
        }

        $tutor = Tutor::create($data);

        // Optional: create user account when checkbox is checked
        $tempPassword = null;
        if ($request->boolean('create_user_account')) {
            $tempPassword = 'KidzTech2025';

            $user = User::create([
                'name' => $data['first_name'] . ' ' . $data['last_name'],
                'email' => $data['email'],
                'password' => Hash::make($tempPassword),
                'profile_photo' => $data['profile_photo'] ?? null,
            ]);

            // Assign tutor role (requires spatie/laravel-permission package)
            if (method_exists($user, 'assignRole')) {
                $user->assignRole('tutor');
            }

            // Flash temp password for one-time display
            session()->flash('temp_password', $tempPassword);
            session()->flash('temp_password_email', $data['email']);
        }

        return redirect()->route('tutors.show', $tutor)
            ->with('success', 'Tutor added successfully!' . ($tempPassword ? ' User account created with temporary password.' : ''));
    }

    public function show(Tutor $tutor)
    {
        return view('tutors.show', compact('tutor'));
    }

    public function edit(Tutor $tutor)
    {
        return view('tutors.edit', compact('tutor'));
    }

    public function update(UpdateTutorRequest $request, Tutor $tutor)
    {
        $data = $request->validated();

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($tutor->profile_photo && Storage::disk('public')->exists($tutor->profile_photo)) {
                Storage::disk('public')->delete($tutor->profile_photo);
            }

            $file = $request->file('profile_photo');
            $filename = 'tutor_' . Str::slug($data['email']) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('profile_photos', $filename, 'public');
            $data['profile_photo'] = $path;
        }

        $tutor->update($data);

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
