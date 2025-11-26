<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Student;
use Illuminate\Auth\Access\HandlesAuthorization;

class StudentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any students.
     */
    public function viewAny(User $user): bool
    {
        // Admin, Manager, Director can view any students
        return $user->hasAnyRole(['admin', 'manager', 'director']);
    }

    /**
     * Determine if the user can view the student profile.
     */
    public function view(User $user, Student $student): bool
    {
        // Parents can view students linked to them via guardian_student pivot
        if ($user->isParent()) {
            return $user->guardiansOf->contains($student);
        }

        // Students can view ONLY their own profile
        if ($user->isStudent()) {
            // Check if the user's email matches the student's email
            return $user->email === $student->email;
        }

        // Admin, Manager, Director can view any student
        if ($user->hasAnyRole(['admin', 'manager', 'director'])) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can create students.
     */
    public function create(User $user): bool
    {
        // Only Admin, Manager, Director can create students
        return $user->hasAnyRole(['admin', 'manager', 'director']);
    }

    /**
     * Determine if the user can update the student.
     */
    public function update(User $user, Student $student): bool
    {
        // Parents CANNOT update students
        if ($user->isParent()) {
            return false;
        }

        // Students CANNOT update their own profile
        if ($user->isStudent()) {
            return false;
        }

        // Admin, Manager, Director can update any student
        return $user->hasAnyRole(['admin', 'manager', 'director']);
    }

    /**
     * Determine if the user can delete the student.
     */
    public function delete(User $user, Student $student): bool
    {
        // Parents CANNOT delete students
        if ($user->isParent()) {
            return false;
        }

        // Students CANNOT delete themselves
        if ($user->isStudent()) {
            return false;
        }

        // Only Admin can delete students
        return $user->hasRole('admin');
    }
}
