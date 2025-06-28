<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProductOutOfStock extends Notification implements ShouldQueue
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
                    ->subject('🚨 Alerte Stock - Rupture de stock')
                    ->greeting('Bonjour ' . $notifiable->name . ',')
                    ->line('Le produit **' . $this->product->name . '** est maintenant en rupture de stock.')
                    ->line('Quantité restante : ' . $this->product->quantity)
                    ->line('Il est urgent de réapprovisionner ce produit.')
                    ->action('Voir le produit', route('admin.products.show', $this->product->id))
                    ->line('Merci de prendre les mesures nécessaires rapidement.');
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
            'type' => 'stock_out',
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'quantity' => $this->product->quantity,
            'message' => 'Le produit "' . $this->product->name . '" est en rupture de stock.',
            'url' => route('admin.products.show', $this->product->id)
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
