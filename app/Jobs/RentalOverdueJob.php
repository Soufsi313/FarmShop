<?php

namespace App\Jobs;

use App\Models\OrderLocation;
use App\Mail\RentalOverdueMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class RentalOverdueJob implements ShouldQueue
{
    use Queueable;

    public $orderLocation;

    /**
     * Create a new job instance.
     */
    public function __construct(OrderLocation $orderLocation)
    {
        $this->orderLocation = $orderLocation;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Vérifier que la location n'a pas été retournée
            if (in_array($this->orderLocation->status, ['returned', 'finished', 'cancelled'])) {
                Log::info('Notification de retard non envoyée car location déjà retournée', [
                    'order_location_id' => $this->orderLocation->id,
                    'current_status' => $this->orderLocation->status
                ]);
                return;
            }

            // Calculer les jours de retard
            $lateDays = now()->diffInDays($this->orderLocation->end_date);
            
            // Calculer les frais de retard
            $lateFees = $lateDays * ($this->orderLocation->late_fee_per_day ?? 10);

            // Mettre à jour les informations de retard
            $this->orderLocation->update([
                'late_days' => $lateDays,
                'late_fees' => $lateFees
            ]);

            // Envoyer l'email de retard
            Mail::to($this->orderLocation->user->email)->send(
                new RentalOverdueMail($this->orderLocation)
            );

            Log::info('Notification de retard envoyée', [
                'order_location_id' => $this->orderLocation->id,
                'order_number' => $this->orderLocation->order_number,
                'user_email' => $this->orderLocation->user->email,
                'late_days' => $lateDays,
                'late_fees' => $lateFees
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de la notification de retard', [
                'order_location_id' => $this->orderLocation->id,
                'error' => $e->getMessage()
            ]);
            
            // Re-programmer le job dans 2 heures en cas d'erreur temporaire
            $this->release(7200);
        }
    }
}
