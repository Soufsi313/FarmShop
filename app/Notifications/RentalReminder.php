<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Rental;

class RentalReminder extends Notification implements ShouldQueue
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
        $daysUntilReturn = now()->diffInDays($this->rental->end_date);
        
        return (new MailMessage)
            ->subject('Rappel - Fin de location dans ' . $daysUntilReturn . ' jour(s)')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Nous vous rappelons que votre location se termine bientôt.')
            ->line('**Numéro de location :** ' . $this->rental->rental_number)
            ->line('**Date de fin :** ' . $this->rental->end_date->format('d/m/Y'))
            ->line('**Nombre de jours restants :** ' . $daysUntilReturn)
            ->line('**Produits loués :**')
            ->lines($this->getProductsList())
            ->line('Merci de prévoir le retour de vos produits avant la date limite pour éviter des frais de retard.')
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
            'end_date' => $this->rental->end_date,
            'message' => 'Rappel de fin de location'
        ];
    }

    /**
     * Obtenir la liste des produits
     */
    private function getProductsList()
    {
        $lines = [];
        
        foreach ($this->rental->items as $item) {
            $lines[] = "- {$item->product->name} (Quantité: {$item->quantity})";
        }
        
        return $lines;
    }
}
