<?php

namespace App\Mail;

use App\Models\TrainingSignup;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TrainingProgramLink extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public TrainingSignup $signup) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your TGC Training Program — Personal Access Link',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.training.link',
            with: [
                'signup' => $this->signup,
            ]
        );
    }
}
