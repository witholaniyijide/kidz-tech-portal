<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ParentSettingsController extends Controller
{
    /**
     * Display the parent settings page.
     */
    public function index()
    {
        $user = Auth::user();

        return view('parent.settings.index', compact('user'));
    }

    /**
     * Update parent profile information.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'phone_country_code' => ['nullable', 'string', 'max:10'],
            'timezone' => ['nullable', 'string', 'timezone'],
        ]);

        // Track if email is changing
        $emailChanged = $user->email !== $validated['email'];
        $oldEmail = $user->email;

        $user->update($validated);

        // If email changed, update all linked student records to preserve parent linkage
        if ($emailChanged) {
            $this->updateStudentParentEmails($user, $oldEmail, $validated['email']);
        }

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Update student parent email fields when a parent changes their email.
     * This prevents creation of duplicate parent accounts and preserves message history.
     */
    protected function updateStudentParentEmails($user, $oldEmail, $newEmail)
    {
        // Get all students linked to this parent via guardian_student table
        $students = $user->students()->get();

        foreach ($students as $student) {
            $updates = [];

            // Update father_email if it matches the old email
            if ($student->father_email === $oldEmail) {
                $updates['father_email'] = $newEmail;
            }

            // Update mother_email if it matches the old email
            if ($student->mother_email === $oldEmail) {
                $updates['mother_email'] = $newEmail;
            }

            // Update legacy parent_email if it matches the old email
            if ($student->parent_email === $oldEmail) {
                $updates['parent_email'] = $newEmail;
            }

            // Apply updates if any
            if (!empty($updates)) {
                $student->update($updates);
            }
        }
    }

    /**
     * Update parent password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($validated['new_password']),
        ]);

        return redirect()->back()->with('success', 'Password changed successfully.');
    }

    /**
     * Update notification preferences.
     */
    public function updateNotifications(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'notify_email' => ['boolean'],
            'notify_in_app' => ['boolean'],
            'notify_daily_summary' => ['boolean'],
        ]);

        $user->update([
            'notify_email' => $request->has('notify_email'),
            'notify_in_app' => $request->has('notify_in_app'),
            'notify_daily_summary' => $request->has('notify_daily_summary'),
        ]);

        return redirect()->back()->with('success', 'Notification preferences updated successfully.');
    }

    /**
     * Update profile photo/avatar.
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        // Delete old avatar if exists
        if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        // Store new avatar
        $path = $request->file('avatar')->store('avatars', 'public');
        $user->profile_photo = $path;
        $user->save();

        return redirect()->back()->with('success', 'Profile photo updated successfully.');
    }
}
