<?php

namespace App\Mail;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class VisitorMessageReply extends Mailable
{
    use Queueable, SerializesModels;

    public $originalMessage;
    public $replyContent;
    public $adminName;

    /**
     * Create a new message instance.
     */
    public function __construct(Message $originalMessage, string $replyContent, string $adminName = 'Administration FarmShop')
    {
        $this->originalMessage = $originalMessage;
        $this->replyContent = $replyContent;
        $this->adminName = $adminName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: $this->originalMessage->metadata['sender_email'] ?? 'no-reply@farmshop.com',
            subject: 'Re: ' . $this->originalMessage->subject,
            replyTo: [
                new Address(config('mail.from.address'), config('mail.from.name'))
            ]
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.visitor-message-reply',
            with: [
                'originalMessage' => $this->originalMessage,
                'replyContent' => $this->replyContent,
                'adminName' => $this->adminName,
                'visitorName' => $this->originalMessage->metadata['sender_name'] ?? 'Cher visiteur',
                'visitorEmail' => $this->originalMessage->metadata['sender_email'] ?? '',
                'originalSubject' => $this->originalMessage->subject,
                'originalContent' => $this->originalMessage->content,
                'messageReference' => 'MSG-' . str_pad($this->originalMessage->id, 6, '0', STR_PAD_LEFT),
                'contactReason' => $this->originalMessage->metadata['contact_reason'] ?? 'question'
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
