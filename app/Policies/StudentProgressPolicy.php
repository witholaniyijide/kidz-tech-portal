<?php

namespace App\Policies;

use App\Models\User;
use App\Models\StudentProgress;
use Illuminate\Auth\Access\HandlesAuthorization;

class StudentProgressPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any progress entries.
     */
    public function viewAny(User $user): bool
    {
        // Admin, Manager, Director can view all progress entries
        return $user->hasAnyRole(['admin', 'manager', 'director', 'parent', 'student']);
    }

    /**
     * Determine if the user can view the progress entry.
     */
    public function view(User $user, StudentProgress $progress): bool
    {
        // Parents can view all progress entries for their linked students
        if ($user->isParent()) {
            $student = $progress->student;
            return $user->guardiansOf->contains($student);
        }

        // Students can view their own progress milestones
        if ($user->isStudent()) {
            // Check if the user's email matches the student's email
            return $user->email === $progress->student->email;
        }

        // Admin, Manager, Director can view any progress
        if ($user->hasAnyRole(['admin', 'manager', 'director'])) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can create progress entries.
     */
    public function create(User $user): bool
    {
        // Admin, Manager can create milestones
        return $user->hasAnyRole(['admin', 'manager']);
    }

    /**
     * Determine if the user can update the progress entry.
     */
    public function update(User $user, StudentProgress $progress): bool
    {
        // Parents CANNOT modify progress records
        if ($user->isParent()) {
            return false;
        }

        // Students CANNOT modify progress records
        if ($user->isStudent()) {
            return false;
        }

        // Admin, Manager can update milestones
        return $user->hasAnyRole(['admin', 'manager']);
    }

    /**
     * Determine if the user can delete the progress entry.
     */
    public function delete(User $user, StudentProgress $progress): bool
    {
        // Parents CANNOT delete progress records
        if ($user->isParent()) {
            return false;
        }

        // Students CANNOT delete progress records
        if ($user->isStudent()) {
            return false;
        }

        // Only Admin can delete progress entries
        return $user->hasRole('admin');
    }
}
