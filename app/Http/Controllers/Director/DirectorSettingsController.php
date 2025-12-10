<?php

namespace App\Http\Controllers\Director;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DirectorSettingsController extends Controller
{
    /**
     * Show director settings page.
     */
    public function index()
    {
        $user = Auth::user();

        return view('director.settings.index', [
            'user' => $user,
        ]);
    }

    /**
     * Update notification preferences.
     */
    public function updateNotificationPreferences(Request $request)
    {
        $request->validate([
            'notify_email' => 'boolean',
            'notify_in_app' => 'boolean',
            'notify_daily_summary' => 'boolean',
        ]);

        $user = Auth::user();

        $user->update([
            'notify_email' => $request->boolean('notify_email'),
            'notify_in_app' => $request->boolean('notify_in_app'),
            'notify_daily_summary' => $request->boolean('notify_daily_summary'),
        ]);

        return back()->with('success', 'Notification preferences updated successfully.');
    }

    /**
     * Update profile settings.
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
        ]);

        $user = Auth::user();

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Update password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Verify current password
        if (!\Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update([
            'password' => \Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }
}
