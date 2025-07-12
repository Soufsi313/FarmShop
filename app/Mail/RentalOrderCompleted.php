<?php

namespace App\Mail;

use App\Models\OrderLocation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RentalOrderCompleted extends Mailable implements ShouldQueue
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
            subject: 'Votre location est terminée - Fermeture requise #' . $this->orderLocation->order_number,
            from: config('mail.from.address'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.rental-order-completed',
            with: [
                'orderLocation' => $this->orderLocation,
                'user' => $this->orderLocation->user,
                'items' => $this->orderLocation->orderItemLocations,
                'endDate' => $this->orderLocation->end_date,
                'actualDays' => $this->orderLocation->getActualRentalDays(),
                'plannedDays' => $this->orderLocation->getRentalDaysCount(),
                'lateDays' => max(0, $this->orderLocation->getActualRentalDays() - $this->orderLocation->getRentalDaysCount()),
                'potentialLateFees' => $this->orderLocation->calculateLateFees(),
                'closeDeadline' => now()->addDays(2)->format('d/m/Y à 23:59'),
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
