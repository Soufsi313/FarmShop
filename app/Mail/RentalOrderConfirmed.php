<?php

namespace App\Mail;

use App\Models\OrderLocation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RentalOrderConfirmed extends Mailable
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
            subject: 'Confirmation de votre commande de location #' . $this->orderLocation->order_number,
            from: config('mail.from.address'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.rental-order-confirmed',
            with: [
                'orderLocation' => $this->orderLocation,
                'user' => $this->orderLocation->user,
                'items' => $this->orderLocation->orderItemLocations,
                'startDate' => $this->orderLocation->start_date,
                'endDate' => $this->orderLocation->end_date,
                'totalDays' => $this->orderLocation->getRentalDaysCount(),
                'pickupAddress' => $this->orderLocation->pickup_address,
                'returnAddress' => $this->orderLocation->return_address,
                'subtotal' => $this->orderLocation->subtotal,
                'depositAmount' => $this->orderLocation->deposit_amount,
                'taxAmount' => $this->orderLocation->tax_amount,
                'totalAmount' => $this->orderLocation->total_amount,
                'cancellationDeadline' => $this->orderLocation->getCancellationDeadline(),
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
