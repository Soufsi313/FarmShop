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
        // Calculs simples pour éviter les erreurs
        $startDate = $this->orderLocation->start_date;
        $endDate = $this->orderLocation->end_date;
        $plannedDays = $this->orderLocation->rental_days ?: $startDate->diffInDays($endDate);
        $actualDays = $startDate->diffInDays(now());
        $lateDays = max(0, $actualDays - $plannedDays);
        $potentialLateFees = $lateDays * 10; // 10€/jour
        
        return new Content(
            view: 'emails.rental-order-completed-custom',
            text: 'emails.rental-order-completed-text',
            with: [
                'orderLocation' => $this->orderLocation,
                'user' => $this->orderLocation->user,
                'items' => $this->orderLocation->orderItemLocations,
                'endDate' => $endDate,
                'actualDays' => $actualDays,
                'plannedDays' => $plannedDays,
                'lateDays' => $lateDays,
                'potentialLateFees' => $potentialLateFees,
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
