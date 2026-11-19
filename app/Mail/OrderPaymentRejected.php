<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderPaymentRejected extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "We could not verify your TGC100 merch payment ({$this->order->reference})",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.shop.rejected',
            with: ['order' => $this->order->loadMissing('items')],
        );
    }
}
