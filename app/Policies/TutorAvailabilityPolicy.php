<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TutorAvailability;
use Illuminate\Auth\Access\HandlesAuthorization;

class TutorAvailabilityPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any availabilities.
     */
    public function viewAny(User $user): bool
    {
        // Managers and Admins can view all availabilities
        return $user->hasRole('manager') || $user->hasRole('admin') || $user->hasRole('director');
    }

    /**
     * Determine if the user can view the availability.
     */
    public function view(User $user, TutorAvailability $availability): bool
    {
        // Tutors can view their own availability
        if ($user->hasRole('tutor')) {
            $tutor = $user->tutor;
            if ($tutor && $availability->tutor_id === $tutor->id) {
                return true;
            }
        }

        // Managers and Admins can view all availabilities
        return $user->hasRole('manager') || $user->hasRole('admin') || $user->hasRole('director');
    }

    /**
     * Determine if the user can create availabilities.
     */
    public function create(User $user): bool
    {
        // Tutors can create their own availability
        return $user->hasRole('tutor') || $user->hasRole('admin');
    }

    /**
     * Determine if the user can update the availability.
     */
    public function update(User $user, TutorAvailability $availability): bool
    {
        // Tutors can update their own availability
        if ($user->hasRole('tutor')) {
            $tutor = $user->tutor;
            if ($tutor && $availability->tutor_id === $tutor->id) {
                return true;
            }
        }

        // Admins can update any availability
        return $user->hasRole('admin');
    }

    /**
     * Determine if the user can delete the availability.
     */
    public function delete(User $user, TutorAvailability $availability): bool
    {
        // Tutors can delete their own availability
        if ($user->hasRole('tutor')) {
            $tutor = $user->tutor;
            if ($tutor && $availability->tutor_id === $tutor->id) {
                return true;
            }
        }

        // Admins can delete any availability
        return $user->hasRole('admin');
    }
}
