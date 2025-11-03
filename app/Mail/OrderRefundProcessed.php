<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderRefundProcessed extends Mailable
{
    use SerializesModels;

    public Order $order;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order->load(['items.product', 'user']);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Remboursement effectué - Commande ' . $this->order->order_number,
            from: config('mail.from.address'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.returns.refund-processed',
            with: [
                'order' => $this->order,
                'user' => $this->order->user,
                'returnableItems' => $this->order->items->filter(fn($item) => $item->is_returnable),
                'refundAmount' => $this->order->items->filter(fn($item) => $item->is_returnable)
                    ->sum(fn($item) => $item->unit_price * $item->quantity),
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
