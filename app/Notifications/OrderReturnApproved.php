<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;

class OrderReturnApproved extends Notification
{
    use Queueable;

    protected $order;
    protected $returnNumber;
    protected $refundAmount;

    public function __construct(Order $order, string $returnNumber, float $refundAmount)
    {
        $this->order = $order;
        $this->returnNumber = $returnNumber;
        $this->refundAmount = $refundAmount;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Retour approuvé pour votre commande #{$this->order->order_number} - FarmShop")
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Votre demande de retour pour la commande #{$this->order->order_number} a été approuvée.")
            ->line("**Numéro de retour :** {$this->returnNumber}")
            ->line("**Montant du remboursement :** {$this->refundAmount}€")
            ->line("Le remboursement sera traité dans les 5 à 7 jours ouvrables.")
            ->line("Vous recevrez une confirmation une fois le remboursement effectué.")
            ->action('Voir mes commandes', url('/orders'))
            ->line("Merci de votre confiance.")
            ->salutation('L\'équipe FarmShop');
    }

    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'return_number' => $this->returnNumber,
            'refund_amount' => $this->refundAmount,
        ];
    }
}
