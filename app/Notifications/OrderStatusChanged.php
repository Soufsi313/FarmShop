<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public $order;
    public $oldStatus;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order, string $oldStatus)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
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
        $statusMessages = [
            Order::STATUS_PENDING => 'En attente de confirmation',
            Order::STATUS_CONFIRMED => 'Confirmée et en préparation',
            Order::STATUS_SHIPPED => 'Expédiée',
            Order::STATUS_DELIVERED => 'Livrée',
            Order::STATUS_CANCELLED => 'Annulée',
        ];

        $currentStatusMessage = $statusMessages[$this->order->status] ?? 'Statut inconnu';
        
        $mailMessage = (new MailMessage)
            ->subject('Mise à jour de votre commande #' . $this->order->order_number)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Le statut de votre commande #' . $this->order->order_number . ' a été mis à jour.')
            ->line('Nouveau statut : ' . $currentStatusMessage);

        // Messages personnalisés selon le statut
        switch ($this->order->status) {
            case Order::STATUS_CONFIRMED:
                $mailMessage->line('Votre commande a été confirmée et est maintenant en préparation.');
                break;
            case Order::STATUS_SHIPPED:
                $mailMessage->line('Votre commande a été expédiée. Vous devriez la recevoir sous peu.');
                if ($this->order->tracking_number) {
                    $mailMessage->line('Numéro de suivi : ' . $this->order->tracking_number);
                }
                break;
            case Order::STATUS_DELIVERED:
                $mailMessage->line('Votre commande a été livrée avec succès !');
                $mailMessage->line('Nous espérons que vous êtes satisfait(e) de vos achats.');
                break;
            case Order::STATUS_CANCELLED:
                $mailMessage->line('Votre commande a été annulée.');
                $mailMessage->line('Le remboursement sera effectué sous 3-5 jours ouvrés.');
                break;
        }

        return $mailMessage
            ->action('Voir ma commande', url('/orders/' . $this->order->id))
            ->line('Merci de faire confiance à FarmShop !')
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
            'old_status' => $this->oldStatus,
            'new_status' => $this->order->status,
            'message' => 'Le statut de votre commande #' . $this->order->order_number . ' a été mis à jour.'
        ];
    }
}
