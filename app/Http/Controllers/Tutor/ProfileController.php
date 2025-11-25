<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tutor\UpdateProfileRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the tutor's profile.
     */
    public function edit()
    {
        // Get the authenticated tutor
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            abort(403, 'You do not have a tutor profile.');
        }

        return view('tutor.profile.edit', compact('tutor'));
    }

    /**
     * Update the tutor's profile in storage.
     */
    public function update(UpdateProfileRequest $request)
    {
        // Get the authenticated tutor
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            abort(403, 'You do not have a tutor profile.');
        }

        // Prepare data for update
        $data = $request->validated();

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($tutor->profile_photo && Storage::disk('public')->exists($tutor->profile_photo)) {
                Storage::disk('public')->delete($tutor->profile_photo);
            }

            // Store new photo
            $file = $request->file('profile_photo');
            $filename = 'tutor_' . $tutor->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('profile_photos/tutors', $filename, 'public');

            $data['profile_photo'] = $path;
        }

        // Update tutor profile
        $tutor->update($data);

        return redirect()
            ->route('tutor.profile.edit')
            ->with('success', 'Profile updated successfully!');
    }
}
