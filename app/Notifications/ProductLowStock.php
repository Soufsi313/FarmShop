<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProductLowStock extends Notification implements ShouldQueue
{
    use Queueable;

    public $product;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('⚠️ Alerte Stock - Stock faible')
                    ->greeting('Bonjour ' . $notifiable->name . ',')
                    ->line('Le produit **' . $this->product->name . '** a atteint le seuil critique de stock.')
                    ->line('Quantité restante : ' . $this->product->quantity)
                    ->line('Seuil critique : ' . $this->product->critical_stock_threshold)
                    ->line('Il est recommandé de réapprovisionner ce produit prochainement.')
                    ->action('Voir le produit', route('admin.products.show', $this->product->id))
                    ->line('Merci de votre attention.');
    }

    /**
     * Get the database representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'type' => 'stock_low',
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'quantity' => $this->product->quantity,
            'critical_threshold' => $this->product->critical_stock_threshold,
            'message' => 'Le produit "' . $this->product->name . '" a atteint le seuil critique de stock.',
            'url' => route('admin.products.show', $this->product->id)
        ];
    }
}
