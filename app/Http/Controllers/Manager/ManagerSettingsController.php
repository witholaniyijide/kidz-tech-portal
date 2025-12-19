<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ManagerSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->hasRole('manager')) {
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
        $preferences = $user->preferences['notifications'] ?? [];
        return view('manager.settings.index', compact('user', 'preferences'));
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
            'description' => 'Manager updated profile information',
            'model_type' => get_class($user),
            'model_id' => $user->id,
        ]);

        return redirect()
            ->route('manager.settings.index')
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
            'description' => 'Manager changed account password',
            'model_type' => get_class($user),
            'model_id' => $user->id,
        ]);

        return redirect()
            ->route('manager.settings.index')
            ->with('success', 'Password changed successfully.');
    }

    /**
     * Update notification preferences.
     */
    public function updateNotifications(Request $request)
    {
        $validated = $request->validate([
            'email_notifications' => 'boolean',
            'attendance_alerts' => 'boolean',
            'report_alerts' => 'boolean',
            'assessment_alerts' => 'boolean',
        ]);

        $user = Auth::user();

        // Store in user preferences (JSON column)
        $preferences = $user->preferences ?? [];
        $preferences['notifications'] = $validated;
        $user->preferences = $preferences;
        $user->save();

        return redirect()
            ->route('manager.settings.index')
            ->with('success', 'Notification preferences updated.');
    }
}
