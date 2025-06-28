<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Rental;

class RentalConfirmation extends Notification implements ShouldQueue
{
    use Queueable;

    protected $rental;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Rental $rental)
    {
        $this->rental = $rental;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
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
            ->subject('Confirmation de votre location - ' . $this->rental->rental_number)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Nous vous confirmons la création de votre location.')
            ->line('**Numéro de location :** ' . $this->rental->rental_number)
            ->line('**Période :** du ' . $this->rental->start_date->format('d/m/Y') . ' au ' . $this->rental->end_date->format('d/m/Y'))
            ->line('**Durée :** ' . $this->rental->duration_in_days . ' jour(s)')
            ->line('**Produits loués :**')
            ->lines($this->getProductsList())
            ->line('**Montant de la location :** ' . number_format($this->rental->total_rental_amount, 2) . '€')
            ->line('**Caution :** ' . number_format($this->rental->total_deposit_amount, 2) . '€')
            ->line('**Total :** ' . number_format($this->rental->total_amount, 2) . '€')
            ->line('Votre demande de location est en cours de traitement. Vous recevrez une confirmation une fois celle-ci validée.')
            ->action('Voir ma location', url('/rentals/' . $this->rental->id))
            ->line('Merci de votre confiance !');
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
            'rental_id' => $this->rental->id,
            'rental_number' => $this->rental->rental_number,
            'total_amount' => $this->rental->total_amount,
            'message' => 'Nouvelle location créée'
        ];
    }

    /**
     * Obtenir la liste des produits
     */
    private function getProductsList()
    {
        $lines = [];
        
        foreach ($this->rental->items as $item) {
            $lines[] = "- {$item->product->name} (Quantité: {$item->quantity}, Prix/jour: " . number_format($item->rental_price_per_day, 2) . "€)";
        }
        
        return $lines;
    }
}
