<?php

namespace App\Mail;

use App\Models\RegistrationGroup;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * One aggregated recap for whoever booked the group.
 *
 * Each participant still gets their own RegistrationApproved email; this is the
 * organizer's copy of the whole transaction, with everyone's bib in one place.
 */
class GroupRegistrationSummary extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public RegistrationGroup $group) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Group Registration Summary — {$this->group->reference_code}",
        );
    }

    public function content(): Content
    {
        $members = $this->group->registrations()
            ->with('raceCategory')
            ->get()
            ->sortBy(fn ($r) => $r->last_name . $r->first_name)
            ->values();

        return new Content(
            markdown: 'emails.registration.group_summary',
            with: [
                'group'    => $this->group,
                'members'  => $members,
                'approved' => $members->where('status', 'approved'),
                'rejected' => $members->where('status', 'rejected'),
            ],
        );
    }
}
