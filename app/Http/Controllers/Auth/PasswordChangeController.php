<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordChangeController extends Controller
{
    /**
     * Show the password change form.
     */
    public function show()
    {
        return view('auth.password-change');
    }

    /**
     * Update the user's password.
     */
    public function update(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = Auth::user();

        // Prevent using the same password as before
        if (Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'You cannot use the same password. Please choose a different password.',
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password),
            'password_change_required' => false,
        ]);

        // Redirect to intended URL or dashboard based on role
        $intended = session()->pull('url.intended');

        if ($intended) {
            return redirect($intended)
                ->with('success', 'Your password has been changed successfully.');
        }

        // Determine the appropriate dashboard based on user role
        $redirectRoute = $this->getDashboardRoute($user);

        return redirect()->route($redirectRoute)
            ->with('success', 'Your password has been changed successfully. Welcome!');
    }

    /**
     * Get the appropriate dashboard route for the user based on their role.
     */
    protected function getDashboardRoute($user): string
    {
        if ($user->hasRole('director')) {
            return 'director.dashboard';
        }

        if ($user->hasRole('admin')) {
            return 'admin.dashboard';
        }

        if ($user->hasRole('manager')) {
            return 'manager.dashboard';
        }

        if ($user->hasRole('tutor')) {
            return 'tutor.dashboard';
        }

        if ($user->hasRole('parent')) {
            return 'parent.dashboard';
        }

        return 'dashboard';
    }
}
