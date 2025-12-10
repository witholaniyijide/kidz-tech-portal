<?php

namespace App\Mail;

use App\Models\TutorReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class DirectorFinalApprovalMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public TutorReport $report
    ) {
        $this->report->load(['student', 'tutor', 'director']);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Report Approved by Director - ' . $this->report->student->fullName(),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.director.final-approval',
            with: [
                'report' => $this->report,
                'student' => $this->report->student,
                'tutor' => $this->report->tutor,
                'director' => $this->report->director,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        // Generate PDF
        $pdf = Pdf::loadView('tutor.reports.pdf', ['report' => $this->report]);

        $filename = 'report_' . $this->report->student->first_name . '_' . $this->report->student->last_name . '_' . $this->report->month . '.pdf';

        return [
            Attachment::fromData(fn () => $pdf->output(), $filename)
                ->withMime('application/pdf'),
        ];
    }
}
