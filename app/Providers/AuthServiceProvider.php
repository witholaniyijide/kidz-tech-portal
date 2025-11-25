<?php

namespace App\Providers;

use App\Models\TutorReport;
use App\Models\TutorAvailability;
use App\Policies\TutorReportPolicy;
use App\Policies\TutorAvailabilityPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        TutorReport::class => TutorReportPolicy::class,
        TutorAvailability::class => TutorAvailabilityPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Define gates for tutor-specific actions
        Gate::define('tutor-create-report', function ($user) {
            return $user->hasRole('tutor');
        });

        Gate::define('tutor-approve-report', function ($user) {
            return $user->hasRole('manager');
        });

        Gate::define('attendance-approve', function ($user) {
            return $user->hasRole('manager');
        });
    }
}
