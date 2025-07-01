<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;

class OrderCancelled extends Notification
{
    use Queueable;

    protected $order;
    protected $reason;

    public function __construct(Order $order, string $reason)
    {
        $this->order = $order;
        $this->reason = $reason;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Annulation de votre commande #{$this->order->order_number} - FarmShop")
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Nous vous informons que votre commande #{$this->order->order_number} a été annulée.")
            ->line("**Raison de l'annulation :** {$this->reason}")
            ->line("**Montant total :** {$this->order->total_amount}€")
            ->line("Le remboursement sera traité dans les prochains jours ouvrables.")
            ->line("Si vous avez des questions concernant cette annulation, n'hésitez pas à nous contacter.")
            ->action('Voir mes commandes', url('/orders'))
            ->line("Nous nous excusons pour la gêne occasionnée.")
            ->salutation('L\'équipe FarmShop');
    }

    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'reason' => $this->reason,
            'total_amount' => $this->order->total_amount,
        ];
    }
}
