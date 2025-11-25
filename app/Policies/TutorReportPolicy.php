<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TutorReport;
use Illuminate\Auth\Access\HandlesAuthorization;

class TutorReportPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view the report.
     */
    public function view(User $user, TutorReport $report): bool
    {
        // Tutors can view their own reports
        if ($user->hasRole('tutor') && $report->created_by === $user->id) {
            return true;
        }

        // Managers can view reports for tutors in their scope (or all)
        if ($user->hasRole('manager')) {
            return true;
        }

        // Directors and Admins can view all reports
        if ($user->hasRole('director') || $user->hasRole('admin')) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can create reports.
     */
    public function create(User $user): bool
    {
        // Only tutors (and admins) can create reports
        return $user->hasRole('tutor') || $user->hasRole('admin');
    }

    /**
     * Determine if the user can update the report.
     */
    public function update(User $user, TutorReport $report): bool
    {
        // Only the author can update when status is draft
        return $report->status === 'draft' && $report->created_by === $user->id;
    }

    /**
     * Determine if the user can submit the report.
     */
    public function submit(User $user, TutorReport $report): bool
    {
        // Only the author can submit when status is draft
        return $report->status === 'draft' && $report->created_by === $user->id;
    }

    /**
     * Determine if the user can comment on the report.
     */
    public function comment(User $user, TutorReport $report): bool
    {
        // Tutors can comment on their own reports
        if ($user->hasRole('tutor') && $report->created_by === $user->id) {
            return true;
        }

        // Managers and Directors can comment on any report
        return $user->hasRole('manager') || $user->hasRole('director') || $user->hasRole('admin');
    }

    /**
     * Determine if the user can approve/review the report.
     */
    public function approve(User $user, TutorReport $report): bool
    {
        // Managers can approve submitted reports
        if ($user->hasRole('manager') && $report->status === 'submitted') {
            return true;
        }

        // Directors can give final approval to manager-approved reports
        if ($user->hasRole('director') && $report->status === 'approved-by-manager') {
            return true;
        }

        // Admins can approve at any stage
        if ($user->hasRole('admin')) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can send the report back for correction.
     */
    public function sendBackForCorrection(User $user, TutorReport $report): bool
    {
        // Managers can send back submitted reports
        if ($user->hasRole('manager') && $report->status === 'submitted') {
            return true;
        }

        // Directors can send back manager-approved reports
        if ($user->hasRole('director') && $report->status === 'approved-by-manager') {
            return true;
        }

        // Admins can send back at any stage
        if ($user->hasRole('admin')) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can delete the report.
     */
    public function delete(User $user, TutorReport $report): bool
    {
        // Only author can delete draft reports
        if ($report->status === 'draft' && $report->created_by === $user->id) {
            return true;
        }

        // Admins can delete any report
        return $user->hasRole('admin');
    }
}
