<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\TutorReport;

class TutorReportApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $report;

    public function __construct(TutorReport $report)
    {
        $this->report = $report;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        // Check user notification preferences
        $channels = ['database'];

        if ($notifiable->notify_email ?? true) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $student = $this->report->student;
        $director = $this->report->director;

        return (new MailMessage)
            ->subject('Report Approved - Final Director Approval')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Great news! Your report has been given final approval by the director.')
            ->line('**Student:** ' . $student->first_name . ' ' . $student->last_name)
            ->line('**Month:** ' . $this->report->month)
            ->line('**Performance Rating:** ' . ucfirst($this->report->performance_rating))
            ->line('**Attendance Score:** ' . $this->report->attendance_score . '%')
            ->when($this->report->director_comment, function ($message) {
                return $message->line('**Director Comment:** ' . $this->report->director_comment);
            })
            ->action('View Report', route('tutor.reports.show', $this->report->id))
            ->line('The report is now available to the student and parents.')
            ->line('Thank you for your excellent work!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'report_approved',
            'report_id' => $this->report->id,
            'student_id' => $this->report->student_id,
            'student_name' => $this->report->student->first_name . ' ' . $this->report->student->last_name,
            'month' => $this->report->month,
            'performance_rating' => $this->report->performance_rating,
            'attendance_score' => $this->report->attendance_score,
            'director_comment' => $this->report->director_comment,
            'approved_at' => $this->report->approved_by_director_at?->toDateTimeString(),
            'link' => route('tutor.reports.show', $this->report->id),
        ];
    }
}
