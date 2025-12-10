<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TutorReport;
use Illuminate\Auth\Access\HandlesAuthorization;

class StudentReportPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any student reports.
     */
    public function viewAny(User $user): bool
    {
        // Parents, Students, Admin, Manager, Director can view reports
        return $user->hasAnyRole(['parent', 'student', 'admin', 'manager', 'director']);
    }

    /**
     * Determine if the user can view the student report.
     */
    public function view(User $user, TutorReport $report): bool
    {
        // Parents can view ONLY director-approved reports for their linked students
        if ($user->isParent()) {
            // Must be director-approved
            if ($report->status !== 'approved-by-director') {
                return false;
            }

            // Check if parent is guardian of the student
            $student = $report->student;
            return $user->guardiansOf->contains($student);
        }

        // Students can view ONLY director-approved reports for themselves
        if ($user->isStudent()) {
            // Must be director-approved
            if ($report->status !== 'approved-by-director') {
                return false;
            }

            // Check if the report is about this student
            return $user->email === $report->student->email;
        }

        // Tutors CANNOT access student/parent portal reports
        // They only have access via their own report module (handled by TutorReportPolicy)
        if ($user->hasRole('tutor')) {
            return false;
        }

        // Director, Manager, Admin have access via existing report policies
        if ($user->hasAnyRole(['admin', 'manager', 'director'])) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can export the report as PDF.
     */
    public function export(User $user, TutorReport $report): bool
    {
        // Parents can export director-approved reports for their students
        if ($user->isParent()) {
            if ($report->status !== 'approved-by-director') {
                return false;
            }
            $student = $report->student;
            return $user->guardiansOf->contains($student);
        }

        // Students can export their own director-approved reports
        if ($user->isStudent()) {
            if ($report->status !== 'approved-by-director') {
                return false;
            }
            return $user->email === $report->student->email;
        }

        // Director, Manager, Admin can export any report
        return $user->hasAnyRole(['admin', 'manager', 'director']);
    }

    /**
     * Determine if the user can print the report.
     */
    public function print(User $user, TutorReport $report): bool
    {
        // Same logic as export
        return $this->export($user, $report);
    }
}
