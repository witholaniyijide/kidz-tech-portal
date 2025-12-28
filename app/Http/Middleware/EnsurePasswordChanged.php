<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordChanged
{
    /**
     * Routes that should be accessible even when password change is required.
     */
    protected array $except = [
        'password.change',
        'password.change.update',
        'logout',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip for guests
        if (!$request->user()) {
            return $next($request);
        }

        // Skip for exempt routes
        $currentRoute = $request->route()?->getName();
        if ($currentRoute && in_array($currentRoute, $this->except)) {
            return $next($request);
        }

        // Check if user needs to change password
        if ($request->user()->password_change_required) {
            // Store the intended URL so we can redirect back after password change
            if (!$request->is('password/change*')) {
                session()->put('url.intended', $request->url());
            }

            return redirect()->route('password.change')
                ->with('warning', 'You must change your password before continuing.');
        }

        return $next($request);
    }
}
