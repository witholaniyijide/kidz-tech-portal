<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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
        return view('manager.settings.index', compact('user'));
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

        return redirect()
            ->route('manager.settings.index')
            ->with('success', 'Notification preferences updated.');
    }

    /**
     * Update profile photo/avatar.
     */
    public function updateAvatar(Request $request)
    {
        $validated = $request->validate([
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

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'updated_avatar',
            'description' => 'Manager updated profile photo',
            'model_type' => get_class($user),
            'model_id' => $user->id,
        ]);

        return redirect()
            ->route('manager.settings.index')
            ->with('success', 'Profile photo updated successfully.');
    }
}
