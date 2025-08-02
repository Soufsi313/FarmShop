<?php

namespace App\Jobs;

use App\Models\OrderLocation;
use App\Mail\RentalEndReminderMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class RentalEndReminderJob implements ShouldQueue
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
            // Vérifier que la location est toujours en cours
            if (!in_array($this->orderLocation->status, ['confirmed', 'in_progress'])) {
                Log::info('Rappel de fin non envoyé car statut incorrect', [
                    'order_location_id' => $this->orderLocation->id,
                    'current_status' => $this->orderLocation->status
                ]);
                return;
            }

            // Envoyer l'email de rappel
            Mail::to($this->orderLocation->user->email)->send(
                new RentalEndReminderMail($this->orderLocation)
            );

            Log::info('Rappel de fin de location envoyé', [
                'order_location_id' => $this->orderLocation->id,
                'order_number' => $this->orderLocation->order_number,
                'user_email' => $this->orderLocation->user->email,
                'end_date' => $this->orderLocation->end_date->format('Y-m-d')
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi du rappel de fin de location', [
                'order_location_id' => $this->orderLocation->id,
                'error' => $e->getMessage()
            ]);
            
            // Re-programmer le job dans 1 heure en cas d'erreur temporaire
            $this->release(3600);
        }
    }
}
