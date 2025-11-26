<?php

namespace App\Providers;

use App\Models\TutorReport;
use App\Models\TutorAvailability;
use App\Models\TutorAssessment;
use App\Models\DirectorActivityLog;
use App\Models\Student;
use App\Models\StudentProgress;
use App\Models\ParentNotification;
use App\Policies\TutorReportPolicy;
use App\Policies\TutorAvailabilityPolicy;
use App\Policies\TutorAssessmentPolicy;
use App\Policies\DirectorActivityLogPolicy;
use App\Policies\StudentPolicy;
use App\Policies\StudentProgressPolicy;
use App\Policies\ParentNotificationPolicy;
use App\Policies\StudentReportPolicy;
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
        TutorAssessment::class => TutorAssessmentPolicy::class,
        DirectorActivityLog::class => DirectorActivityLogPolicy::class,
        Student::class => StudentPolicy::class,
        StudentProgress::class => StudentProgressPolicy::class,
        ParentNotification::class => ParentNotificationPolicy::class,
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

        Gate::define('director-approve-report', function ($user) {
            return $user->hasRole('director') || $user->hasRole('admin');
        });

        Gate::define('director-approve-assessment', function ($user) {
            return $user->hasRole('director') || $user->hasRole('admin');
        });

        Gate::define('director-view-analytics', function ($user) {
            return $user->hasRole('director') || $user->hasRole('admin');
        });

        Gate::define('director-view-activity-logs', function ($user) {
            return $user->hasRole('director') || $user->hasRole('admin');
        });

        Gate::define('director-export-data', function ($user) {
            return $user->hasRole('director') || $user->hasRole('admin');
        });
    }
}
