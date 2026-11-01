<?php

namespace App\Mail;

use App\Models\GrantApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GrantApplicationReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public GrantApplication $application) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'We received your TGC100 Grant application',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.grant.received',
            with: [
                'application' => $this->application,
            ]
        );
    }
}
