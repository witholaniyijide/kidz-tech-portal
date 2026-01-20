<?php

namespace App\Mail;

use App\Models\Student;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ParentAccountWelcomeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public User $parent,
        public Student $student,
        public string $password,
        public string $loginUrl,
        public string $relationship = ''
    ) {
    }

    /**
     * Get proper salutation based on relationship.
     */
    protected function getSalutation(): string
    {
        $name = $this->parent->name;

        switch (strtolower($this->relationship)) {
            case 'father':
                return "Dear Mr. {$name}";
            case 'mother':
                return "Dear Mrs. {$name}";
            case 'guardian':
                return "Dear {$name}";
            default:
                return "Dear {$name}";
        }
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to KidzTech Parent Portal - Your Account Has Been Created',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.parent.welcome',
            with: [
                'parent' => $this->parent,
                'student' => $this->student,
                'password' => $this->password,
                'loginUrl' => $this->loginUrl,
                'salutation' => $this->getSalutation(),
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
        return [];
    }
}
