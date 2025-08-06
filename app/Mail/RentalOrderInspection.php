<?php

namespace App\Mail;

use App\Models\OrderLocation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RentalOrderInspection extends Mailable
{
    use Queueable, SerializesModels;

    public OrderLocation $orderLocation;

    /**
     * Create a new message instance.
     */
    public function __construct(OrderLocation $orderLocation)
    {
        $this->orderLocation = $orderLocation->load(['orderItemLocations.product']);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '📋 Rapport d\'inspection - Location #' . $this->orderLocation->order_number . ' - FarmShop',
            from: 's.mef2703@gmail.com',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.rental-order-inspection',
            with: [
                'orderLocation' => $this->orderLocation,
                'user' => $this->orderLocation->user,
            ]
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
