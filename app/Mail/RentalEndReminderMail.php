<?php

namespace App\Mail;

use App\Models\OrderLocation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RentalEndReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public OrderLocation $orderLocation;

    /**
     * Create a new message instance.
     */
    public function __construct(OrderLocation $orderLocation)
    {
        $this->orderLocation = $orderLocation->load(['orderItemLocations.product', 'user']);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '⏰ Rappel - Votre location se termine demain #' . $this->orderLocation->order_number,
            from: config('mail.from.address'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $endDate = $this->orderLocation->end_date;
        $startDate = $this->orderLocation->start_date;
        $totalDays = $this->orderLocation->rental_days ?: $startDate->diffInDays($endDate);
        
        return new Content(
            view: 'emails.rental-end-reminder',
            with: [
                'orderLocation' => $this->orderLocation,
                'user' => $this->orderLocation->user,
                'items' => $this->orderLocation->orderItemLocations,
                'endDate' => $endDate,
                'startDate' => $startDate,
                'totalDays' => $totalDays,
                'endTomorrow' => $endDate->format('d/m/Y à H:i'),
                'hoursRemaining' => now()->diffInHours($endDate),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
