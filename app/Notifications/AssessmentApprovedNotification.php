<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\TutorAssessment;

class AssessmentApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $assessment;

    public function __construct(TutorAssessment $assessment)
    {
        $this->assessment = $assessment;
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
        $director = $this->assessment->director;

        return (new MailMessage)
            ->subject('Assessment Approved - Final Director Approval')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your assessment has been approved by the director.')
            ->line('**Assessment Month:** ' . $this->assessment->assessment_month)
            ->line('**Performance Score:** ' . $this->assessment->performance_score . '/100')
            ->line('**Professionalism:** ' . $this->assessment->professionalism_rating . '/5')
            ->line('**Communication:** ' . $this->assessment->communication_rating . '/5')
            ->line('**Punctuality:** ' . $this->assessment->punctuality_rating . '/5')
            ->when($this->assessment->director_comment, function ($message) {
                return $message->line('**Director Comment:** ' . $this->assessment->director_comment);
            })
            ->action('View Assessment', route('tutor.assessments.show', $this->assessment->id))
            ->line('Keep up the excellent work!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'assessment_approved',
            'assessment_id' => $this->assessment->id,
            'assessment_month' => $this->assessment->assessment_month,
            'performance_score' => $this->assessment->performance_score,
            'professionalism_rating' => $this->assessment->professionalism_rating,
            'communication_rating' => $this->assessment->communication_rating,
            'punctuality_rating' => $this->assessment->punctuality_rating,
            'director_comment' => $this->assessment->director_comment,
            'approved_at' => $this->assessment->approved_by_director_at?->toDateTimeString(),
            'link' => route('tutor.assessments.show', $this->assessment->id),
        ];
    }
}
