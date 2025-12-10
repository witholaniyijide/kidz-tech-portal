<?php

namespace App\Services;

use App\Models\TutorReport;
use App\Models\TutorAssessment;
use App\Models\User;
use App\Models\DirectorActivityLog;
use App\Models\TutorNotification;
use App\Models\ManagerNotification;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TutorReportApprovedNotification;
use App\Notifications\AssessmentApprovedNotification;
use App\Notifications\ParentReportAvailableNotification;
use App\Mail\DirectorFinalApprovalMail;
use App\Mail\ParentReportReadyMail;

class DirectorApprovalService
{
    /**
     * Approve a tutor report.
     *
     * @param TutorReport $report
     * @param User $director
     * @param string|null $comment
     * @param string|null $signature
     * @return bool
     * @throws \Exception
     */
    public function approveTutorReport(
        TutorReport $report,
        User $director,
        ?string $comment = null,
        ?string $signature = null
    ): bool {
        try {
            return DB::transaction(function () use ($report, $director, $comment, $signature) {
                $previousStatus = $report->status;

                // Update report status and metadata
                $report->update([
                    'status' => 'approved-by-director',
                    'director_id' => $director->id,
                    'director_comment' => $comment,
                    'director_signature' => $signature,
                    'approved_by_director_at' => now(),
                ]);

                // Log the action in audit logs
                AuditLog::create([
                    'user_id' => $director->id,
                    'action' => 'report.approve.director',
                    'auditable_type' => TutorReport::class,
                    'auditable_id' => $report->id,
                    'meta' => [
                        'previous_status' => $previousStatus,
                        'new_status' => 'approved-by-director',
                        'director_comment' => $comment,
                        'approved_at' => now()->toDateTimeString(),
                    ],
                ]);

                // Log director activity
                $this->logDirectorAction(
                    $director,
                    'approved_report',
                    TutorReport::class,
                    $report->id
                );

                // Notify the tutor
                TutorNotification::create([
                    'tutor_id' => $report->tutor_id,
                    'title' => 'Report Approved - Final Approval',
                    'body' => "Your report for {$report->student->first_name} {$report->student->last_name} ({$report->month}) has been given final approval by the director.",
                    'type' => 'system',
                    'is_read' => false,
                    'meta' => [
                        'report_id' => $report->id,
                        'action' => 'approved',
                        'link' => route('tutor.reports.show', $report->id),
                    ],
                ]);

                // Notify managers
                $managers = User::whereHas('roles', function ($query) {
                    $query->where('name', 'manager');
                })->get();

                foreach ($managers as $manager) {
                    ManagerNotification::create([
                        'user_id' => $manager->id,
                        'title' => 'Report Approved by Director',
                        'body' => "The report for {$report->student->first_name} {$report->student->last_name} ({$report->month}) has been approved by the director.",
                        'type' => 'report',
                        'is_read' => false,
                        'meta' => [
                            'report_id' => $report->id,
                            'action' => 'approved',
                            'link' => route('manager.tutor-reports.show', $report->id),
                        ],
                    ]);
                }

                // Notify admins via email
                $admins = User::whereHas('roles', function ($query) {
                    $query->where('name', 'admin');
                })->get();

                foreach ($admins as $admin) {
                    try {
                        Mail::send('emails.notification', [
                            'subject' => 'Report Approved by Director',
                            'body' => "The report for {$report->student->first_name} {$report->student->last_name} ({$report->month}) has been approved by the director.",
                            'type' => 'report_approved',
                            'meta' => ['report_id' => $report->id],
                        ], function ($message) use ($admin) {
                            $message->to($admin->email)
                                ->subject('Report Approved by Director');
                        });
                    } catch (\Exception $e) {
                        Log::error('Failed to send admin notification email', [
                            'admin_id' => $admin->id,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }

                // Send Laravel Notification to Tutor (queued)
                $report->tutor->notify(new TutorReportApprovedNotification($report));

                // Send email to tutor with PDF attachment
                Mail::to($report->tutor->email)->send(new DirectorFinalApprovalMail($report));

                // Notify and email all managers
                foreach ($managers as $manager) {
                    $manager->notify(new TutorReportApprovedNotification($report));
                }

                // Send notification and email to parents
                $this->notifyParentsOfApprovedReport($report);

                Log::info('Director approved tutor report', [
                    'report_id' => $report->id,
                    'director_id' => $director->id,
                ]);

                return true;
            });
        } catch (\Exception $e) {
            Log::error('Failed to approve tutor report', [
                'report_id' => $report->id,
                'director_id' => $director->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Approve a tutor assessment.
     *
     * @param TutorAssessment $assessment
     * @param User $director
     * @param string|null $comment
     * @param string|null $signature
     * @return bool
     * @throws \Exception
     */
    public function approveTutorAssessment(
        TutorAssessment $assessment,
        User $director,
        ?string $comment = null,
        ?string $signature = null
    ): bool {
        try {
            return DB::transaction(function () use ($assessment, $director, $comment, $signature) {
                $previousStatus = $assessment->status;

                // Update assessment status and metadata
                $assessment->update([
                    'status' => 'approved-by-director',
                    'director_id' => $director->id,
                    'director_comment' => $comment,
                    'approved_by_director_at' => now(),
                ]);

                // Log the action in audit logs (if AuditLog supports assessments)
                AuditLog::create([
                    'user_id' => $director->id,
                    'action' => 'assessment.approve.director',
                    'auditable_type' => TutorAssessment::class,
                    'auditable_id' => $assessment->id,
                    'meta' => [
                        'previous_status' => $previousStatus,
                        'new_status' => 'approved-by-director',
                        'director_comment' => $comment,
                        'approved_at' => now()->toDateTimeString(),
                    ],
                ]);

                // Log director activity
                $this->logDirectorAction(
                    $director,
                    'approved_assessment',
                    TutorAssessment::class,
                    $assessment->id
                );

                // Notify the tutor
                TutorNotification::create([
                    'tutor_id' => $assessment->tutor_id,
                    'title' => 'Assessment Approved by Director',
                    'body' => "Your assessment for {$assessment->assessment_month} has been approved by the director.",
                    'type' => 'system',
                    'is_read' => false,
                    'meta' => [
                        'assessment_id' => $assessment->id,
                        'action' => 'approved',
                    ],
                ]);

                // Notify managers
                $managers = User::whereHas('roles', function ($query) {
                    $query->where('name', 'manager');
                })->get();

                foreach ($managers as $manager) {
                    ManagerNotification::create([
                        'user_id' => $manager->id,
                        'title' => 'Assessment Approved by Director',
                        'body' => "The assessment for tutor {$assessment->tutor->first_name} {$assessment->tutor->last_name} ({$assessment->assessment_month}) has been approved by the director.",
                        'type' => 'assessment',
                        'is_read' => false,
                        'meta' => [
                            'assessment_id' => $assessment->id,
                            'action' => 'approved',
                        ],
                    ]);
                }

                // Send Laravel Notification to Tutor (queued)
                $assessment->tutor->notify(new AssessmentApprovedNotification($assessment));

                // Notify all managers
                foreach ($managers as $manager) {
                    $manager->notify(new AssessmentApprovedNotification($assessment));
                }

                Log::info('Director approved tutor assessment', [
                    'assessment_id' => $assessment->id,
                    'director_id' => $director->id,
                ]);

                return true;
            });
        } catch (\Exception $e) {
            Log::error('Failed to approve tutor assessment', [
                'assessment_id' => $assessment->id,
                'director_id' => $director->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Log director action to DirectorActivityLog.
     *
     * @param User $director
     * @param string $action
     * @param string|null $modelType
     * @param int|null $modelId
     * @return DirectorActivityLog
     */
    public function logDirectorAction(
        User $director,
        string $action,
        ?string $modelType = null,
        ?int $modelId = null
    ): DirectorActivityLog {
        return DirectorActivityLog::logAction(
            $director->id,
            $action,
            $modelType,
            $modelId
        );
    }

    /**
     * Notify parents when a report is approved.
     *
     * @param TutorReport $report
     * @return void
     */
    protected function notifyParentsOfApprovedReport(TutorReport $report): void
    {
        try {
            $student = $report->student;

            // Get parent user (if exists and has email)
            if ($student->parent && $student->parent->email) {
                $student->parent->notify(new ParentReportAvailableNotification($report));
                Mail::to($student->parent->email)->send(new ParentReportReadyMail($report));

                Log::info('Notified parent via user account', [
                    'report_id' => $report->id,
                    'parent_id' => $student->parent->id,
                    'parent_email' => $student->parent->email,
                ]);
            }

            // Also send to direct email addresses if available (father/mother)
            $parentEmails = [];

            if (!empty($student->father_email) && filter_var($student->father_email, FILTER_VALIDATE_EMAIL)) {
                $parentEmails[] = $student->father_email;
            }

            if (!empty($student->mother_email) && filter_var($student->mother_email, FILTER_VALIDATE_EMAIL)) {
                $parentEmails[] = $student->mother_email;
            }

            // Remove duplicates and parent's registered email (already sent above)
            $parentEmails = array_unique($parentEmails);
            if ($student->parent && $student->parent->email) {
                $parentEmails = array_filter($parentEmails, fn($email) => $email !== $student->parent->email);
            }

            // Send to additional parent emails
            foreach ($parentEmails as $email) {
                Mail::to($email)->send(new ParentReportReadyMail($report));

                Log::info('Sent parent report email', [
                    'report_id' => $report->id,
                    'parent_email' => $email,
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Failed to notify parents of approved report', [
                'report_id' => $report->id,
                'error' => $e->getMessage(),
            ]);
            // Don't throw - parent notification failure shouldn't fail the approval
        }
    }
}
