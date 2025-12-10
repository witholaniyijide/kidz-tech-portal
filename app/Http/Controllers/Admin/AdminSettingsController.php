<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->hasRole('admin')) {
                abort(403, 'Unauthorized access.');
            }
            return $next($request);
        });
    }

    /**
     * Display settings page.
     */
    public function index()
    {
        $user = Auth::user();
        return view('admin.settings.index', compact('user'));
    }

    /**
     * Update profile information.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($validated);

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'updated_profile',
            'description' => 'Updated profile information',
            'model_type' => get_class($user),
            'model_id' => $user->id,
        ]);

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Update password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'changed_password',
            'description' => 'Changed account password',
            'model_type' => get_class($user),
            'model_id' => $user->id,
        ]);

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Password changed successfully.');
    }

    /**
     * Update notification preferences.
     */
    public function updateNotifications(Request $request)
    {
        $validated = $request->validate([
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
            'attendance_alerts' => 'boolean',
            'report_alerts' => 'boolean',
            'schedule_alerts' => 'boolean',
        ]);

        $user = Auth::user();
        
        // Store in user preferences (JSON column or separate table)
        $preferences = $user->preferences ?? [];
        $preferences['notifications'] = $validated;
        $user->preferences = $preferences;
        $user->save();

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Notification preferences updated.');
    }

    // Note: Admin CANNOT:
    // - Create director/manager accounts
    // - Change color theme system-wide
    // - Manage SMS/email provider settings
    // - Manage roles & permissions
    // These are Director-only per specification
}
