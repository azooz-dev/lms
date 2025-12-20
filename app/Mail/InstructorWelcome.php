<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InstructorWelcome extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public readonly User $instructor
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to Our Platform - Start Teaching Today!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.instructor_welcome',
        );
    }
}
