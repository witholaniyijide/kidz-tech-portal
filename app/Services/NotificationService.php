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
use App\Models\Notice;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send notification when Director approves a report
     * Recipients: Tutor, Manager, Admin, Parent
     */
    public function notifyReportApproved(TutorReport $report): void
    {
        $report->load(['tutor', 'student.guardians']);

        // 1. Notify Tutor (in-app + email)
        $this->notifyTutor(
            $report->tutor,
            'Report Approved by Director',
            "Your report for {$report->student->first_name} {$report->student->last_name} ({$report->month}) has been approved by the Director.",
            'report_approved',
            ['report_id' => $report->id, 'student_id' => $report->student_id]
        );

        // 2. Notify Managers (in-app + email)
        $managers = User::role('manager')->get();
        foreach ($managers as $manager) {
            $this->notifyManager(
                $manager,
                'Report Approved',
                "Report for {$report->student->first_name} {$report->student->last_name} has been approved by the Director.",
                'report_approved',
                ['report_id' => $report->id]
            );
        }

        // 3. Notify Admins (in-app + email)
        $admins = User::role('admin')->get();
        foreach ($admins as $admin) {
            $this->sendEmailNotification(
                $admin->email,
                'Report Approved by Director',
                "The report for {$report->student->first_name} {$report->student->last_name} ({$report->month}) has been approved.",
                'report_approved',
                ['report_id' => $report->id, 'student_name' => $report->student->first_name . ' ' . $report->student->last_name]
            );
        }

        // 4. Notify Parents (in-app + email)
        if ($report->student->guardians) {
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
            $this->notifyTutor(
                $attendance->tutor,
                'Attendance Approved',
                "Your attendance record for {$attendance->student->first_name} {$attendance->student->last_name} on {$attendance->class_date->format('M d, Y')} has been approved.",
                'attendance_approved',
                ['attendance_id' => $attendance->id, 'student_id' => $attendance->student_id]
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

        $admins = User::role('admin')->get();
        foreach ($admins as $admin) {
            $this->sendEmailNotification(
                $admin->email,
                'New Attendance Submission',
                "Tutor {$attendance->tutor->first_name} {$attendance->tutor->last_name} has submitted attendance for {$attendance->student->first_name} {$attendance->student->last_name}.",
                'attendance_submitted',
                ['attendance_id' => $attendance->id, 'tutor_name' => $attendance->tutor->first_name . ' ' . $attendance->tutor->last_name]
            );
        }
    }

    /**
     * Send notification when Director approves assessment
     * Recipients: Manager
     */
    public function notifyAssessmentApproved($assessment): void
    {
        $assessment->load(['tutor', 'student']);

        $managers = User::role('manager')->get();
        foreach ($managers as $manager) {
            $this->notifyManager(
                $manager,
                'Assessment Approved',
                "Assessment for {$assessment->student->first_name} {$assessment->student->last_name} has been approved by the Director.",
                'assessment_approved',
                ['assessment_id' => $assessment->id]
            );
        }
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
                        $this->sendEmailNotification(
                            $admin->email,
                            $notice->title,
                            strip_tags($notice->content),
                            'notice',
                            ['notice_id' => $notice->id]
                        );
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
            $this->sendEmailNotification(
                $recipient->email,
                "Message from {$sender->name}: {$subject}",
                $message,
                'message',
                array_merge($meta, ['sender_id' => $sender->id])
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

        // Email notification (if tutor has email)
        if ($tutor->email) {
            $this->sendEmailNotification($tutor->email, $title, $body, $type, $meta);
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

        // Email notification
        $this->sendEmailNotification($manager->email, $title, $body, $type, $meta);

        Log::info("Notification sent to Manager", ['manager_id' => $manager->id, 'type' => $type]);
    }

    /**
     * Helper: Send notification to Parent (in-app + email)
     */
    protected function notifyParent(User $parent, Student $student, string $title, string $body, string $type, array $meta = []): void
    {
        // In-app notification - matching existing ParentNotification model structure
        ParentNotification::create([
            'parent_id' => $parent->id,
            'type' => $type,
            'data' => array_merge($meta, [
                'title' => $title,
                'body' => $body,
                'student_id' => $student->id,
            ]),
            'read_at' => null,
        ]);

        // Email notification
        $this->sendEmailNotification($parent->email, $title, $body, $type, $meta);

        Log::info("Notification sent to Parent", ['parent_id' => $parent->id, 'student_id' => $student->id, 'type' => $type]);
    }

    /**
     * Helper: Send email notification
     */
    protected function sendEmailNotification(string $email, string $subject, string $body, string $type, array $meta = []): void
    {
        try {
            Mail::send('emails.notification', [
                'subject' => $subject,
                'body' => $body,
                'type' => $type,
                'meta' => $meta,
            ], function ($message) use ($email, $subject) {
                $message->to($email)
                    ->subject($subject);
            });

            Log::info("Email notification sent", ['email' => $email, 'subject' => $subject]);
        } catch (\Exception $e) {
            Log::error("Failed to send email notification", [
                'email' => $email,
                'error' => $e->getMessage()
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
