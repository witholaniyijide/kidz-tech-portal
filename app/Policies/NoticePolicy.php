<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Notice;
use Illuminate\Auth\Access\HandlesAuthorization;

class NoticePolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any notices.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view notices
        return true;
    }

    /**
     * Determine if the user can view the notice.
     */
    public function view(User $user, Notice $notice): bool
    {
        // All authenticated users can view notices
        return true;
    }

    /**
     * Determine if the user can create notices.
     */
    public function create(User $user): bool
    {
        // Admins, Directors, and Managers can create notices
        return $user->hasRole('admin') || $user->hasRole('director') || $user->hasRole('manager');
    }

    /**
     * Determine if the user can update the notice.
     */
    public function update(User $user, Notice $notice): bool
    {
        // Admins can update any notice
        if ($user->hasRole('admin')) {
            return true;
        }

        // Directors have ultimate power - can update any notice
        if ($user->hasRole('director')) {
            return true;
        }

        // Managers can only update their own notices
        if ($user->hasRole('manager')) {
            return $notice->posted_by === $user->id;
        }

        return false;
    }

    /**
     * Determine if the user can delete the notice.
     */
    public function delete(User $user, Notice $notice): bool
    {
        // Admins can delete any notice
        if ($user->hasRole('admin')) {
            return true;
        }

        // Directors have ultimate power - can delete any notice
        if ($user->hasRole('director')) {
            return true;
        }

        // Managers can only delete their own notices
        if ($user->hasRole('manager')) {
            return $notice->posted_by === $user->id;
        }

        return false;
    }

    /**
     * Determine if the user can restore the notice.
     */
    public function restore(User $user, Notice $notice): bool
    {
        // Only admins can restore notices
        return $user->hasRole('admin');
    }

    /**
     * Determine if the user can permanently delete the notice.
     */
    public function forceDelete(User $user, Notice $notice): bool
    {
        // Only admins can force delete notices
        return $user->hasRole('admin');
    }
}
