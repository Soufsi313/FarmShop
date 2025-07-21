<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VisitorContactConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $visitorName;
    public $visitorEmail;
    public $messageSubject;
    public $messageContent;
    public $reason;
    public $priority;
    public $reference;
    public $messageId;

    /**
     * Create a new message instance.
     */
    public function __construct($data)
    {
        $this->visitorName = $data['visitor_name'];
        $this->visitorEmail = $data['visitor_email'];
        $this->messageSubject = $data['subject'];
        $this->messageContent = $data['message'];
        $this->reason = $data['reason'];
        $this->priority = $data['priority'];
        $this->reference = $data['reference'];
        $this->messageId = $data['message_id'];
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Confirmation de réception - ' . $this->messageSubject)
                    ->view('emails.visitor-contact-confirmation')
                    ->with([
                        'visitorName' => $this->visitorName,
                        'messageSubject' => $this->messageSubject,
                        'messageContent' => $this->messageContent,
                        'reason' => $this->reason,
                        'priority' => $this->priority,
                        'reference' => $this->reference,
                        'messageId' => $this->messageId,
                        'estimatedResponseTime' => '24-48 heures'
                    ]);
    }
}
