<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class DirectorActionSummaryNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $data;

    /**
     * Create a new notification instance.
     *
     * @param array $data Array containing:
     *   - reports_approved_today: int
     *   - assessments_approved_today: int
     *   - reports_pending: int
     *   - assessments_pending: int
     *   - recent_reports: Collection
     *   - recent_assessments: Collection
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        // Check user notification preferences for daily summaries
        $channels = ['database'];

        if (($notifiable->notify_email ?? true) && ($notifiable->notify_daily_summary ?? false)) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('Director Daily Summary - ' . now()->format('F j, Y'))
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Here\'s your daily summary of activities:')
            ->line('')
            ->line('**Today\'s Activity:**')
            ->line('✓ Reports Approved: ' . $this->data['reports_approved_today'])
            ->line('✓ Assessments Approved: ' . $this->data['assessments_approved_today'])
            ->line('')
            ->line('**Pending Items:**')
            ->line('⏳ Reports Awaiting Approval: ' . $this->data['reports_pending'])
            ->line('⏳ Assessments Awaiting Approval: ' . $this->data['assessments_pending']);

        if ($this->data['reports_pending'] > 0 || $this->data['assessments_pending'] > 0) {
            $message->action('Review Pending Items', route('director.dashboard'));
        }

        if (!empty($this->data['recent_reports'])) {
            $message->line('')->line('**Recently Approved Reports:**');
            foreach ($this->data['recent_reports'] as $report) {
                $message->line('• ' . $report->student->first_name . ' ' . $report->student->last_name . ' (' . $report->month . ')');
            }
        }

        if (!empty($this->data['recent_assessments'])) {
            $message->line('')->line('**Recently Approved Assessments:**');
            foreach ($this->data['recent_assessments'] as $assessment) {
                $message->line('• ' . $assessment->tutor->first_name . ' ' . $assessment->tutor->last_name . ' (' . $assessment->assessment_month . ')');
            }
        }

        return $message->line('')->line('Thank you for your leadership and dedication!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'director_daily_summary',
            'date' => now()->toDateString(),
            'reports_approved_today' => $this->data['reports_approved_today'],
            'assessments_approved_today' => $this->data['assessments_approved_today'],
            'reports_pending' => $this->data['reports_pending'],
            'assessments_pending' => $this->data['assessments_pending'],
            'link' => route('director.dashboard'),
        ];
    }
}
