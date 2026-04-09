<?php

namespace App\Mail;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegistrationRejected extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Registration $registration) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Update on Your Registration',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.registration.rejected',
            with: [
                'registration' => $this->registration,
                'category'     => $this->registration->raceCategory,
            ]
        );
    }
}
