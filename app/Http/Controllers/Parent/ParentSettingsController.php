<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
            'phone' => ['nullable', 'string', 'regex:/^(070|080|081|090|091)\d{8}$/'],
        ], [
            'phone.regex' => 'Phone must be a valid Nigerian phone number (e.g., 08012345678)',
        ]);

        $user->update($validated);

        return redirect()->back()->with('success', 'Profile updated successfully.');
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
}
