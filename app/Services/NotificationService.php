<?php

namespace App\Services;

use App\Models\User;
use App\Models\Tutor;
use App\Models\Student;
use App\Models\TutorReport;
use App\Models\AttendanceRecord;
use App\Models\TutorNotification;
use App\Models\ManagerNotification;
use App\Models\ParentNotification;
use App\Models\DirectorNotification;
use App\Models\Notice;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send notification when Tutor submits a report for review
     * Recipients: Manager (in-app + email)
     */
    public function notifyReportSubmitted(TutorReport $report): void
    {
        $report->load(['tutor', 'student']);

        // Safety check: ensure tutor and student exist
        $tutorName = $report->tutor
            ? "{$report->tutor->first_name} {$report->tutor->last_name}"
            : 'Unknown Tutor';
        $studentName = $report->student
            ? "{$report->student->first_name} {$report->student->last_name}"
            : 'Unknown Student';

        $managers = User::role('manager')->get();
        foreach ($managers as $manager) {
            $this->notifyManager(
                $manager,
                'New Report Submitted',
                "Tutor {$tutorName} has submitted a report for {$studentName} ({$report->month} {$report->year}). Please review.",
                'report', // Using valid enum type - semantic type stored in meta
                ['report_id' => $report->id, 'tutor_id' => $report->tutor_id, 'student_id' => $report->student_id, 'notification_type' => 'report_submitted']
            );
        }
    }

    /**
     * Send notification when Manager sends back a report for modification
     * Recipients: Tutor
     */
    public function notifyReportReturned(TutorReport $report, string $managerComment = ''): void
    {
        $report->load(['tutor', 'student']);

        // Safety check: ensure tutor exists
        if (!$report->tutor) {
            Log::warning('Cannot notify tutor for returned report - tutor not found', [
                'report_id' => $report->id,
                'tutor_id' => $report->tutor_id,
            ]);
            return;
        }

        $studentName = $report->student
            ? "{$report->student->first_name} {$report->student->last_name}"
            : 'Unknown Student';

        $message = "Your report for {$studentName} ({$report->month} {$report->year}) has been sent back for modification.";
        if ($managerComment) {
            $message .= " Manager's comment: {$managerComment}";
        }

        $this->notifyTutor(
            $report->tutor,
            'Report Returned for Modification',
            $message,
            'alert', // Using valid enum type - semantic type stored in meta
            ['report_id' => $report->id, 'student_id' => $report->student_id, 'manager_comment' => $managerComment, 'notification_type' => 'report_returned']
        );
    }

    /**
     * Send notification when Director approves a report
     * Recipients: Tutor, Manager, Admin, Parent
     */
    public function notifyReportApproved(TutorReport $report): void
    {
        $report->load(['tutor', 'student.guardians']);

        // Safety: get student name with fallback
        $studentName = $report->student
            ? "{$report->student->first_name} {$report->student->last_name}"
            : 'Unknown Student';

        // 1. Notify Tutor (in-app + email) - only if tutor exists
        if ($report->tutor) {
            $this->notifyTutor(
                $report->tutor,
                'Report Approved by Director',
                "Your report for {$studentName} ({$report->month}) has been approved by the Director.",
                'system', // Using valid enum type - semantic type stored in meta
                ['report_id' => $report->id, 'student_id' => $report->student_id, 'notification_type' => 'report_approved']
            );
        }

        // 2. Notify Managers (in-app + email)
        $managers = User::role('manager')->get();
        foreach ($managers as $manager) {
            $this->notifyManager(
                $manager,
                'Report Approved',
                "Report for {$studentName} has been approved by the Director.",
                'report', // Using valid enum type - semantic type stored in meta
                ['report_id' => $report->id, 'notification_type' => 'report_approved']
            );
        }

        // 3. Notify Admins (email only, respect notify_email preference)
        $admins = User::role('admin')->get();
        foreach ($admins as $admin) {
            if (($admin->notify_email ?? true) && $admin->email) {
                $this->sendEmailNotification(
                    $admin->email,
                    'Report Approved by Director',
                    "The report for {$studentName} ({$report->month}) has been approved.",
                    'report_approved',
                    ['report_id' => $report->id, 'student_name' => $studentName]
                );
            }
        }

        // 4. Notify Parents (in-app + email) - only if student and guardians exist
        if ($report->student && $report->student->guardians) {
            foreach ($report->student->guardians as $parent) {
                $this->notifyParent(
                    $parent,
                    $report->student,
                    'Progress Report Available',
                    "A new progress report for {$report->student->first_name} is now available for viewing.",
                    'report_available',
                    ['report_id' => $report->id]
                );
            }
        }
    }

    /**
     * Send notification when Admin approves attendance
     * Recipients: Tutor
     */
    public function notifyAttendanceApproved(AttendanceRecord $attendance): void
    {
        $attendance->load(['tutor', 'student']);

        if ($attendance->tutor) {
            $studentName = $attendance->student
                ? "{$attendance->student->first_name} {$attendance->student->last_name}"
                : 'Unknown Student';

            $this->notifyTutor(
                $attendance->tutor,
                'Attendance Approved',
                "Your attendance record for {$studentName} on {$attendance->class_date->format('M d, Y')} has been approved.",
                'schedule', // Using valid enum type - semantic type stored in meta
                ['attendance_id' => $attendance->id, 'student_id' => $attendance->student_id, 'notification_type' => 'attendance_approved']
            );
        }
    }

    /**
     * Send notification when Tutor submits attendance
     * Recipients: Admin
     */
    public function notifyAttendanceSubmitted(AttendanceRecord $attendance): void
    {
        $attendance->load(['tutor', 'student']);

        // Safety: get names with fallbacks
        $tutorName = $attendance->tutor
            ? "{$attendance->tutor->first_name} {$attendance->tutor->last_name}"
            : 'Unknown Tutor';
        $studentName = $attendance->student
            ? "{$attendance->student->first_name} {$attendance->student->last_name}"
            : 'Unknown Student';

        $admins = User::role('admin')->get();
        foreach ($admins as $admin) {
            if (($admin->notify_email ?? true) && $admin->email) {
                $this->sendEmailNotification(
                    $admin->email,
                    'New Attendance Submission',
                    "Tutor {$tutorName} has submitted attendance for {$studentName}.",
                    'attendance_submitted',
                    ['attendance_id' => $attendance->id, 'tutor_name' => $tutorName]
                );
            }
        }
    }

    /**
     * Send notification when Director approves assessment
     * Recipients: Manager (in-app + email)
     */
    public function notifyAssessmentApproved($assessment): void
    {
        $assessment->load(['tutor', 'student']);

        // Safety: get names with fallbacks
        $tutorName = $assessment->tutor
            ? "{$assessment->tutor->first_name} {$assessment->tutor->last_name}"
            : 'a tutor';
        $studentName = $assessment->student
            ? "{$assessment->student->first_name} {$assessment->student->last_name}"
            : '';
        $periodLabel = $assessment->assessment_period ?? '';

        $body = "Assessment for tutor {$tutorName}";
        if ($studentName) {
            $body .= " (student: {$studentName})";
        }
        if ($periodLabel) {
            $body .= " — {$periodLabel}";
        }
        $body .= " has been approved by the Director.";

        $managers = User::role('manager')->get();
        foreach ($managers as $manager) {
            $this->notifyManager(
                $manager,
                'Assessment Approved',
                $body,
                'assessment', // Using valid enum type - semantic type stored in meta
                ['assessment_id' => $assessment->id, 'notification_type' => 'assessment_approved']
            );
        }
    }

    /**
     * Send email notification when Director deletes an assessment
     * Recipients: Manager
     */
    public function sendAssessmentDeletedEmail(User $manager, string $tutorName, string $period, ?string $reason = null): void
    {
        $body = "The assessment for {$tutorName} — {$period} has been deleted by the Director.";
        if ($reason) {
            $body .= "\n\nReason: {$reason}";
        }

        $this->sendEmailNotification(
            $manager->email,
            'Assessment Deleted by Director',
            $body,
            'assessment_deleted',
            ['tutor_name' => $tutorName, 'period' => $period]
        );
    }

    /**
     * Send notification when student receives certification
     * Recipients: Parent
     */
    public function notifyCertificationSent(Student $student, string $certificationName): void
    {
        $student->load('guardians');

        if ($student->guardians) {
            foreach ($student->guardians as $parent) {
                $this->notifyParent(
                    $parent,
                    $student,
                    'Certification Awarded! 🎉',
                    "{$student->first_name} has earned the '{$certificationName}' certification. Congratulations!",
                    'certification_awarded',
                    ['certification' => $certificationName]
                );
            }
        }
    }

    /**
     * Send notification when student moves to a new course
     * Recipients: Parent
     */
    public function notifyCourseChange(Student $student, string $oldCourse, string $newCourse): void
    {
        $student->load('guardians');

        if ($student->guardians) {
            foreach ($student->guardians as $parent) {
                $this->notifyParent(
                    $parent,
                    $student,
                    'Course Level Updated',
                    "{$student->first_name} has progressed from '{$oldCourse}' to '{$newCourse}'. Keep up the great work!",
                    'course_change',
                    ['old_course' => $oldCourse, 'new_course' => $newCourse]
                );
            }
        }
    }

    /**
     * Send notice to multiple user roles
     * Recipients: Based on visibility settings
     */
    public function sendNotice(Notice $notice): void
    {
        $visibleTo = is_array($notice->visible_to) ? $notice->visible_to : json_decode($notice->visible_to, true) ?? [];

        foreach ($visibleTo as $role) {
            switch ($role) {
                case 'tutor':
                case 'tutors':
                    $tutors = Tutor::where('status', 'active')->get();
                    foreach ($tutors as $tutor) {
                        $this->notifyTutor(
                            $tutor,
                            $notice->title,
                            strip_tags($notice->content),
                            'notice',
                            ['notice_id' => $notice->id]
                        );
                    }
                    break;

                case 'parent':
                case 'parents':
                    $parents = User::role('parent')->get();
                    foreach ($parents as $parent) {
                        // Get first associated student for parent
                        $student = $parent->students->first();
                        if ($student) {
                            $this->notifyParent(
                                $parent,
                                $student,
                                $notice->title,
                                strip_tags($notice->content),
                                'notice',
                                ['notice_id' => $notice->id]
                            );
                        }
                    }
                    break;

                case 'manager':
                case 'managers':
                    $managers = User::role('manager')->get();
                    foreach ($managers as $manager) {
                        $this->notifyManager(
                            $manager,
                            $notice->title,
                            strip_tags($notice->content),
                            'notice',
                            ['notice_id' => $notice->id]
                        );
                    }
                    break;

                case 'admin':
                case 'admins':
                    $admins = User::role('admin')->get();
                    foreach ($admins as $admin) {
                        if (($admin->notify_email ?? true) && $admin->email) {
                            $this->sendEmailNotification(
                                $admin->email,
                                $notice->title,
                                strip_tags($notice->content),
                                'notice',
                                ['notice_id' => $notice->id]
                            );
                        }
                    }
                    break;
            }
        }
    }

    /**
     * Send message/comment notification
     */
    public function sendMessage(User $sender, User $recipient, string $subject, string $message, array $meta = []): void
    {
        // Determine recipient type and send appropriate notification
        if ($recipient->hasRole('parent')) {
            $student = $recipient->students->first();
            if ($student) {
                $this->notifyParent(
                    $recipient,
                    $student,
                    "Message from {$sender->name}",
                    $message,
                    'message',
                    array_merge($meta, ['sender_id' => $sender->id, 'subject' => $subject])
                );
            }
        } elseif ($recipient->hasRole('director')) {
            // In-app + email notification for messages to director (e.g. from parents)
            $this->notifyDirector(
                $recipient,
                "Message from {$sender->name}",
                $message,
                'message',
                array_merge($meta, ['sender_id' => $sender->id, 'subject' => $subject]),
                true // Send email for parent messages
            );
        } elseif ($recipient->hasRole('manager')) {
            $this->notifyManager(
                $recipient,
                "Message from {$sender->name}",
                $message,
                'message',
                array_merge($meta, ['sender_id' => $sender->id, 'subject' => $subject])
            );
        }
    }

    /**
     * Helper: Send notification to Tutor (in-app + email)
     */
    protected function notifyTutor(Tutor $tutor, string $title, string $body, string $type, array $meta = []): void
    {
        // In-app notification
        TutorNotification::create([
            'tutor_id' => $tutor->id,
            'title' => $title,
            'body' => $body,
            'type' => $type,
            'is_read' => false,
            'meta' => $meta,
        ]);

        // Email notification (respect notify_email preference via linked User account)
        if ($tutor->email) {
            $notifyEmail = true;
            if ($tutor->user_id) {
                $notifyEmail = $tutor->user->notify_email ?? true;
            }
            if ($notifyEmail) {
                $this->sendEmailNotification($tutor->email, $title, $body, $type, $meta);
            }
        }

        Log::info("Notification sent to Tutor", ['tutor_id' => $tutor->id, 'type' => $type]);
    }

    /**
     * Helper: Send notification to Manager (in-app + email)
     */
    protected function notifyManager(User $manager, string $title, string $body, string $type, array $meta = []): void
    {
        // In-app notification
        ManagerNotification::create([
            'user_id' => $manager->id,
            'title' => $title,
            'body' => $body,
            'type' => $type,
            'is_read' => false,
            'meta' => $meta,
        ]);

        // Email notification (respect notify_email preference)
        if (($manager->notify_email ?? true) && $manager->email) {
            $this->sendEmailNotification($manager->email, $title, $body, $type, $meta);
        }

        Log::info("Notification sent to Manager", ['manager_id' => $manager->id, 'type' => $type]);
    }

    /**
     * Helper: Send notification to Parent (in-app + email)
     */
    protected function notifyParent(User $parent, Student $student, string $title, string $body, string $type, array $meta = []): void
    {
        // In-app notification - set both explicit columns and data JSON
        ParentNotification::create([
            'parent_id' => $parent->id,
            'student_id' => $student->id,
            'type' => $type,
            'title' => $title,
            'message' => $body,
            'data' => array_merge($meta, [
                'title' => $title,
                'body' => $body,
                'student_id' => $student->id,
            ]),
            'read_at' => null,
        ]);

        // Email notification (respect notify_email preference)
        if (($parent->notify_email ?? true) && $parent->email) {
            $this->sendEmailNotification($parent->email, $title, $body, $type, $meta);
        }

        Log::info("Notification sent to Parent", ['parent_id' => $parent->id, 'student_id' => $student->id, 'type' => $type]);
    }

    /**
     * Helper: Send notification to Director (in-app + optional email)
     */
    protected function notifyDirector(User $director, string $title, string $body, string $type, array $meta = [], bool $sendEmail = false): void
    {
        // In-app notification
        DirectorNotification::create([
            'user_id' => $director->id,
            'title' => $title,
            'body' => $body,
            'type' => $type,
            'is_read' => false,
            'meta' => $meta,
        ]);

        // Email notification (only for specific notification types)
        if ($sendEmail && ($director->notify_email ?? true) && $director->email) {
            $this->sendEmailNotification($director->email, $title, $body, $type, $meta);
        }

        Log::info("Notification sent to Director", ['director_id' => $director->id, 'type' => $type, 'email_sent' => $sendEmail]);
    }

    /**
     * Notify directors when manager approves a report (in-app only)
     */
    public function notifyDirectorReportApproved(TutorReport $report): void
    {
        $report->load(['student', 'tutor']);

        $studentName = ($report->student->first_name ?? '') . ' ' . ($report->student->last_name ?? '');
        $title = 'Report Approved by Manager';
        $body = "The report for {$studentName} ({$report->month} {$report->year}) has been approved by the manager and is awaiting your review.";

        $directors = User::role('director')->get();
        foreach ($directors as $director) {
            $this->notifyDirector(
                $director,
                $title,
                $body,
                'report',
                ['report_id' => $report->id, 'student_id' => $report->student_id, 'action' => 'approved_by_manager']
            );
        }
    }

    /**
     * Notify directors when tutor submits attendance (in-app only)
     */
    public function notifyDirectorAttendanceSubmitted(AttendanceRecord $attendance): void
    {
        $attendance->load(['tutor', 'student']);

        $tutorName = ($attendance->tutor->first_name ?? '') . ' ' . ($attendance->tutor->last_name ?? '');
        $studentName = ($attendance->student->first_name ?? '') . ' ' . ($attendance->student->last_name ?? '');
        $title = 'Attendance Submitted';
        $body = "Tutor {$tutorName} submitted attendance for {$studentName} on {$attendance->class_date->format('M j, Y')}.";

        $directors = User::role('director')->get();
        foreach ($directors as $director) {
            $this->notifyDirector(
                $director,
                $title,
                $body,
                'attendance',
                ['attendance_id' => $attendance->id, 'action' => 'submitted']
            );
        }
    }

    /**
     * Notify directors when manager approves attendance (in-app only)
     */
    public function notifyDirectorAttendanceApproved(AttendanceRecord $attendance): void
    {
        $attendance->load(['tutor', 'student']);

        $studentName = ($attendance->student->first_name ?? '') . ' ' . ($attendance->student->last_name ?? '');
        $title = 'Attendance Approved';
        $body = "Attendance for {$studentName} on {$attendance->class_date->format('M j, Y')} has been approved.";

        $directors = User::role('director')->get();
        foreach ($directors as $director) {
            $this->notifyDirector(
                $director,
                $title,
                $body,
                'attendance',
                ['attendance_id' => $attendance->id, 'action' => 'approved']
            );
        }
    }

    /**
     * Notify directors when assessment is forwarded by manager (in-app + email)
     */
    public function notifyDirectorAssessmentForwarded($assessment): void
    {
        $assessment->load(['tutor']);

        $tutorName = $assessment->tutor ? ($assessment->tutor->first_name . ' ' . $assessment->tutor->last_name) : 'Unknown Tutor';
        $periodLabel = $assessment->assessment_period ?? '';
        $title = 'Assessment Ready for Review';
        $body = "Assessment for {$tutorName} — {$periodLabel} is pending your review.";

        $directors = User::role('director')->get();
        foreach ($directors as $director) {
            $this->notifyDirector(
                $director,
                $title,
                $body,
                'assessment',
                [
                    'assessment_id' => $assessment->id,
                    'link' => route('director.assessments.show', $assessment->id),
                ],
                true // Send email for assessment forwarded
            );
        }
    }

    /**
     * Helper: Send email notification
     */
    public function sendEmailNotification(string $email, string $subject, string $body, string $type, array $meta = []): void
    {
        try {
            // Validate email before attempting to send
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                Log::warning("Skipping email notification: invalid email address", ['email' => $email]);
                return;
            }

            // Check if mail is properly configured
            $fromAddress = config('mail.from.address');
            if (empty($fromAddress) || $fromAddress === 'hello@example.com') {
                Log::warning("Email notification skipped: MAIL_FROM_ADDRESS is not configured", [
                    'email' => $email,
                    'from' => $fromAddress,
                    'subject' => $subject,
                ]);
                return;
            }

            $fromName = config('mail.from.name', config('app.name', 'Kidz Tech Coding Club'));

            Mail::send('emails.notification', [
                'subject' => $subject,
                'body' => $body,
                'type' => $type,
                'meta' => $meta,
            ], function ($message) use ($email, $subject, $fromAddress, $fromName) {
                $message->from($fromAddress, $fromName)
                    ->to($email)
                    ->subject($subject);
            });

            Log::info("Email notification sent", ['email' => $email, 'subject' => $subject]);
        } catch (\Exception $e) {
            Log::error("Failed to send email notification", [
                'email' => $email,
                'subject' => $subject,
                'mailer' => config('mail.default'),
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Send push notification via FCM
     */
    public function sendPushNotification(User $user, string $title, string $body, array $data = []): void
    {
        try {
            $pushService = app(PushNotificationService::class);
            $pushService->sendToUser($user, $title, $body, $data);
        } catch (\Exception $e) {
            Log::error("Failed to send push notification", [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send push notification to multiple users
     */
    public function sendPushToUsers(array $users, string $title, string $body, array $data = []): array
    {
        try {
            $pushService = app(PushNotificationService::class);
            return $pushService->sendToUsers($users, $title, $body, $data);
        } catch (\Exception $e) {
            Log::error("Failed to send bulk push notifications", [
                'error' => $e->getMessage()
            ]);
            return ['success' => 0, 'failed' => count($users), 'no_token' => 0];
        }
    }

    /**
     * Send push notification to a topic
     */
    public function sendPushToTopic(string $topic, string $title, string $body, array $data = []): void
    {
        try {
            $pushService = app(PushNotificationService::class);
            $pushService->sendToTopic($topic, $title, $body, $data);
        } catch (\Exception $e) {
            Log::error("Failed to send topic push notification", [
                'topic' => $topic,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Broadcast notification to all users with a specific role
     */
    public function broadcastToRole(string $role, string $title, string $body, string $type, array $meta = []): void
    {
        $users = User::role($role)->get();

        foreach ($users as $user) {
            switch ($role) {
                case 'tutor':
                    $tutor = Tutor::where('email', $user->email)->first();
                    if ($tutor) {
                        $this->notifyTutor($tutor, $title, $body, $type, $meta);
                    }
                    break;
                case 'manager':
                    $this->notifyManager($user, $title, $body, $type, $meta);
                    break;
                case 'parent':
                    $student = $user->students->first();
                    if ($student) {
                        $this->notifyParent($user, $student, $title, $body, $type, $meta);
                    }
                    break;
                default:
                    $this->sendEmailNotification($user->email, $title, $body, $type, $meta);
            }
        }

        Log::info("Broadcast notification sent", ['role' => $role, 'type' => $type, 'count' => $users->count()]);
    }
}
