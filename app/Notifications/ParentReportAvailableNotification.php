<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\TutorReport;

class ParentReportAvailableNotification extends Notification implements ShouldQueue
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
        // Always send email and database notification to parents
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $student = $this->report->student;
        $tutor = $this->report->tutor;

        return (new MailMessage)
            ->subject('New Progress Report Available for ' . $student->first_name)
            ->greeting('Dear Parent,')
            ->line('A new monthly progress report is now available for ' . $student->first_name . ' ' . $student->last_name . '.')
            ->line('**Month:** ' . $this->report->month)
            ->line('**Tutor:** ' . $tutor->first_name . ' ' . $tutor->last_name)
            ->line('**Performance Rating:** ' . ucfirst($this->report->performance_rating))
            ->line('**Attendance Score:** ' . $this->report->attendance_score . '%')
            ->line('')
            ->line('**Report Summary:**')
            ->line($this->report->progress_summary)
            ->line('')
            ->when($this->report->director_comment, function ($message) {
                return $message->line('**Director\'s Note:** ' . $this->report->director_comment);
            })
            ->action('View Full Report', route('parent.reports.show', [$student->id, $this->report->id]))
            ->line('You can also download and print the report from your parent dashboard.')
            ->line('Thank you for your continued support in your child\'s coding journey!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'parent_report_available',
            'report_id' => $this->report->id,
            'student_id' => $this->report->student_id,
            'student_name' => $this->report->student->first_name . ' ' . $this->report->student->last_name,
            'month' => $this->report->month,
            'performance_rating' => $this->report->performance_rating,
            'attendance_score' => $this->report->attendance_score,
            'tutor_name' => $this->report->tutor->first_name . ' ' . $this->report->tutor->last_name,
            'approved_at' => $this->report->approved_by_director_at?->toDateTimeString(),
            'link' => route('parent.reports.show', [$this->report->student_id, $this->report->id]),
        ];
    }
}
