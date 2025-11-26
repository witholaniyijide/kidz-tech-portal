<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ParentNotification;
use Illuminate\Auth\Access\HandlesAuthorization;

class ParentNotificationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any notifications.
     */
    public function viewAny(User $user): bool
    {
        // Only parents can view their own notifications
        return $user->isParent();
    }

    /**
     * Determine if the user can view the notification.
     */
    public function view(User $user, ParentNotification $notification): bool
    {
        // Parents can view ONLY their own notifications
        if ($user->isParent()) {
            return $notification->parent_id === $user->id;
        }

        return false;
    }

    /**
     * Determine if the user can mark notification as read.
     */
    public function markAsRead(User $user, ParentNotification $notification): bool
    {
        // Parents can mark their own notifications as read
        if ($user->isParent()) {
            return $notification->parent_id === $user->id;
        }

        return false;
    }

    /**
     * Determine if the user can delete the notification.
     */
    public function delete(User $user, ParentNotification $notification): bool
    {
        // Parents CANNOT delete notifications
        if ($user->isParent()) {
            return false;
        }

        // Only Admin can delete notifications
        return $user->hasRole('admin');
    }
}
