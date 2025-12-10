<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Report;

class ReportSubmittedNotification extends Notification
{
    use Queueable;

    protected $report;

    public function __construct(Report $report)
    {
        $this->report = $report;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('New Report Awaiting Approval - ' . $this->report->student->full_name)
                    ->greeting('Hello ' . $notifiable->name . '!')
                    ->line('A new monthly progress report has been submitted and is awaiting your approval.')
                    ->line('**Student:** ' . $this->report->student->full_name)
                    ->line('**Month:** ' . $this->report->month . ' ' . $this->report->year)
                    ->line('**Instructor:** ' . $this->report->instructor->name)
                    ->action('Review Report', route('reports.show', $this->report))
                    ->line('Please review and approve the report at your earliest convenience.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'report_id' => $this->report->id,
            'student_name' => $this->report->student->full_name,
            'month' => $this->report->month,
            'year' => $this->report->year,
        ];
    }
}
