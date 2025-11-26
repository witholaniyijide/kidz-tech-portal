<?php

namespace App\Policies;

use App\Models\User;
use App\Models\DirectorActivityLog;
use Illuminate\Auth\Access\HandlesAuthorization;

class DirectorActivityLogPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any activity logs.
     */
    public function viewAny(User $user): bool
    {
        // Only Directors and Admins can view activity logs
        return $user->hasRole('director') || $user->hasRole('admin');
    }

    /**
     * Determine if the user can view the activity log.
     */
    public function view(User $user, DirectorActivityLog $log): bool
    {
        // Only Directors and Admins can view activity logs
        return $user->hasRole('director') || $user->hasRole('admin');
    }
}
