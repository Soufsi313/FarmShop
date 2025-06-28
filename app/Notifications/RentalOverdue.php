<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Rental;

class RentalOverdue extends Notification implements ShouldQueue
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
        $daysOverdue = $this->rental->days_overdue;
        $penaltyAmount = $this->rental->penalties->where('type', 'late_return')->sum('amount');
        
        return (new MailMessage)
            ->subject('URGENT - Location en retard')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Votre location est maintenant **en retard**.')
            ->line('**Numéro de location :** ' . $this->rental->rental_number)
            ->line('**Date limite dépassée :** ' . $this->rental->end_date->format('d/m/Y'))
            ->line('**Nombre de jours de retard :** ' . $daysOverdue)
            ->line('**Produits à retourner :**')
            ->lines($this->getProductsList())
            ->when($penaltyAmount > 0, function ($message) use ($penaltyAmount) {
                return $message->line('**Frais de retard appliqués :** ' . number_format($penaltyAmount, 2) . '€');
            })
            ->line('Merci de retourner les produits dans les plus brefs délais.')
            ->line('Des frais supplémentaires peuvent s\'appliquer en cas de retard prolongé.')
            ->action('Voir ma location', url('/rentals/' . $this->rental->id))
            ->line('Contactez-nous si vous rencontrez des difficultés.');
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
            'days_overdue' => $this->rental->days_overdue,
            'message' => 'Location en retard'
        ];
    }

    /**
     * Obtenir la liste des produits non retournés
     */
    private function getProductsList()
    {
        $lines = [];
        
        foreach ($this->rental->items->where('return_status', '!=', 'fully_returned') as $item) {
            $lines[] = "- {$item->product->name} (Quantité restante: {$item->remaining_quantity})";
        }
        
        return $lines;
    }
}
