<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CheckTutorNotResigned
{
    /**
     * Handle an incoming request.
     * Restrict access for tutors who have resigned more than 3 days ago.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Only check for tutor role
        if ($user && $user->hasRole('tutor')) {
            $tutor = $user->tutor;

            if ($tutor && $tutor->status === 'resigned') {
                // Check if resigned_at is set and more than 3 days ago
                if ($tutor->resigned_at) {
                    $resignedDate = Carbon::parse($tutor->resigned_at);
                    $daysSinceResigned = $resignedDate->diffInDays(Carbon::now());

                    if ($daysSinceResigned >= 3) {
                        Auth::logout();
                        $request->session()->invalidate();
                        $request->session()->regenerateToken();

                        return redirect()->route('login')
                            ->with('error', 'Your account has been deactivated. Please contact the administrator if you believe this is an error.');
                    }
                }
            }
        }

        return $next($request);
    }
}
