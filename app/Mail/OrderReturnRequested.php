<?php

namespace App\Mail;

use App\Models\OrderReturn;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderReturnRequested extends Mailable
{
    use SerializesModels;

    public OrderReturn $orderReturn;

    /**
     * Create a new message instance.
     */
    public function __construct(OrderReturn $orderReturn)
    {
        $this->orderReturn = $orderReturn->load(['order', 'orderItem.product', 'user']);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Retour confirmé - ' . $this->orderReturn->return_number,
            from: config('mail.from.address'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $returnTypeLabels = [
            'defective' => 'Produit défectueux',
            'unwanted' => 'Produit non souhaité',
            'damaged' => 'Produit endommagé',
            'wrong_item' => 'Mauvais article reçu',
        ];

        return new Content(
            markdown: 'emails.returns.requested',
            with: [
                'orderReturn' => $this->orderReturn,
                'user' => $this->orderReturn->user,
                'order' => $this->orderReturn->order,
                'orderItem' => $this->orderReturn->orderItem,
                'product' => $this->orderReturn->orderItem->product,
                'returnTypeLabel' => $returnTypeLabels[$this->orderReturn->return_type] ?? $this->orderReturn->return_type,
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
