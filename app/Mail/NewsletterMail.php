<?php

namespace App\Mail;

use App\Models\Newsletter;
use App\Models\NewsletterSend;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsletterMail extends Mailable
{
    use Queueable, SerializesModels;

    public Newsletter $newsletter;
    public User $user;
    public NewsletterSend $send;

    /**
     * Create a new message instance.
     */
    public function __construct(Newsletter $newsletter, User $user, NewsletterSend $send)
    {
        $this->newsletter = $newsletter;
        $this->user = $user;
        $this->send = $send;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        // Pour éviter les problèmes de RFC 2822, utilisons seulement les emails sans noms
        return new Envelope(
            to: $this->user->email,
            subject: $this->newsletter->subject,
            replyTo: 's.mef2703@gmail.com'
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            html: 'emails.newsletter',
            text: 'emails.newsletter-text',
            with: [
                'newsletter' => $this->newsletter,
                'user' => $this->user,
                'send' => $this->send,
                'trackingUrl' => $this->send->tracking_url,
                'unsubscribeUrl' => $this->send->unsubscribe_url,
                'websiteUrl' => url('/'),
                'preferencesUrl' => url('/profile/newsletter-preferences')
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
