<?php

namespace App\Mail;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactResponseMail extends Mailable
{
    use Queueable, SerializesModels;

    public Contact $contact;

    /**
     * Create a new message instance.
     */
    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: [$this->contact->email => $this->contact->name],
            subject: 'Réponse à votre message - ' . $this->contact->subject,
            replyTo: ['s.mef2703@gmail.com' => 'FarmShop Support']
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            html: 'emails.contact-response',
            text: 'emails.contact-response-text',
            with: [
                'contact' => $this->contact,
                'adminName' => $this->contact->admin->name ?? 'Équipe FarmShop',
                'websiteUrl' => url('/'),
                'contactReference' => 'CONTACT-' . str_pad($this->contact->id, 6, '0', STR_PAD_LEFT)
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
