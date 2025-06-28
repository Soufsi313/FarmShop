<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCancellation extends Notification implements ShouldQueue
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
            ->subject('Annulation de votre commande #' . $this->order->order_number)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Votre commande #' . $this->order->order_number . ' a été annulée avec succès.')
            ->line('Montant de la commande : ' . number_format($this->order->total_amount, 2) . ' €')
            ->line('Le remboursement sera effectué automatiquement sur votre moyen de paiement original dans un délai de 3 à 5 jours ouvrés.')
            ->line('Si vous avez des questions concernant cette annulation, n\'hésitez pas à nous contacter.')
            ->action('Voir mes commandes', url('/orders'))
            ->line('Nous espérons vous revoir bientôt sur FarmShop !')
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
            'message' => 'Votre commande #' . $this->order->order_number . ' a été annulée.'
        ];
    }
}
