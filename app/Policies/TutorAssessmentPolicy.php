<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TutorAssessment;
use Illuminate\Auth\Access\HandlesAuthorization;

class TutorAssessmentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any assessments.
     */
    public function viewAny(User $user): bool
    {
        // Managers, Directors, and Admins can view all assessments
        return $user->hasRole('manager') || $user->hasRole('director') || $user->hasRole('admin');
    }

    /**
     * Determine if the user can view the assessment.
     */
    public function view(User $user, TutorAssessment $assessment): bool
    {
        // Tutors can view their own assessments
        if ($user->hasRole('tutor') && $assessment->tutor_id === $user->id) {
            return true;
        }

        // Managers, Directors, and Admins can view all assessments
        if ($user->hasRole('manager') || $user->hasRole('director') || $user->hasRole('admin')) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can create assessments.
     */
    public function create(User $user): bool
    {
        // Only managers and admins can create assessments
        return $user->hasRole('manager') || $user->hasRole('admin');
    }

    /**
     * Determine if the user can update the assessment.
     */
    public function update(User $user, TutorAssessment $assessment): bool
    {
        // Only managers can update draft or submitted assessments
        if ($user->hasRole('manager') && in_array($assessment->status, ['draft', 'submitted'])) {
            return true;
        }

        // Admins can update any assessment
        return $user->hasRole('admin');
    }

    /**
     * Determine if the user can approve the assessment.
     */
    public function approve(User $user, TutorAssessment $assessment): bool
    {
        // Managers can approve submitted assessments
        if ($user->hasRole('manager') && $assessment->status === 'submitted') {
            return true;
        }

        // Directors can give final approval to manager-approved assessments
        if ($user->hasRole('director') && in_array($assessment->status, ['submitted', 'approved-by-manager'])) {
            return true;
        }

        // Admins can approve at any stage
        if ($user->hasRole('admin')) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can comment on the assessment.
     */
    public function comment(User $user, TutorAssessment $assessment): bool
    {
        // Managers and Directors can comment on any assessment
        return $user->hasRole('manager') || $user->hasRole('director') || $user->hasRole('admin');
    }

    /**
     * Determine if the user can add a comment to the assessment.
     */
    public function addComment(User $user, TutorAssessment $assessment): bool
    {
        // Same as comment() ability
        return $this->comment($user, $assessment);
    }

    /**
     * Determine if the user can delete the assessment.
     */
    public function delete(User $user, TutorAssessment $assessment): bool
    {
        // Only managers can delete draft assessments
        if ($user->hasRole('manager') && $assessment->status === 'draft') {
            return true;
        }

        // Admins can delete any assessment
        return $user->hasRole('admin');
    }
}
