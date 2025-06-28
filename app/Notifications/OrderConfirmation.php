<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderConfirmation extends Notification implements ShouldQueue
{
    use Queueable;

    public $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Confirmation de votre commande #' . $this->order->order_number)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Nous avons bien reçu votre commande #' . $this->order->order_number)
            ->line('Montant total : ' . number_format($this->order->total_amount, 2) . ' €')
            ->line('Statut de paiement : ' . $this->order->payment_status_label)
            ->line('Adresse de livraison : ' . $this->order->shipping_address)
            ->action('Voir ma commande', url('/orders/' . $this->order->id))
            ->line('Merci pour votre achat sur FarmShop !')
            ->line('Vous recevrez une notification dès que le statut de votre commande évoluera.')
            ->salutation('L\'équipe FarmShop');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'total_amount' => $this->order->total_amount,
            'message' => 'Votre commande #' . $this->order->order_number . ' a été confirmée.'
        ];
    }
}
