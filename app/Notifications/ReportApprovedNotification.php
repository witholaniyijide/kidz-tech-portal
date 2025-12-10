<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Report;

class ReportApprovedNotification extends Notification
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
                    ->subject('Monthly Progress Report Approved - ' . $this->report->student->full_name)
                    ->greeting('Hello ' . $notifiable->name . '!')
                    ->line('Great news! The monthly progress report for **' . $this->report->student->full_name . '** has been approved.')
                    ->line('**Month:** ' . $this->report->month . ' ' . $this->report->year)
                    ->line('**Instructor:** ' . $this->report->instructor->name)
                    ->action('View Report', route('reports.show', $this->report))
                    ->line('Thank you for being part of Kidz Tech Coding Club!');
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
