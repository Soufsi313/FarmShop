<?php

namespace App\Jobs;

use App\Models\OrderLocation;
use App\Mail\RentalEndedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EndRentalJob implements ShouldQueue
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
            // Vérifier que la location est toujours en cours ou confirmée
            if (!in_array($this->orderLocation->status, ['confirmed', 'in_progress'])) {
                Log::info('Fin de location non traitée car statut incorrect', [
                    'order_location_id' => $this->orderLocation->id,
                    'current_status' => $this->orderLocation->status
                ]);
                return;
            }

            // Marquer la location comme terminée (en attente de retour)
            $this->orderLocation->update([
                'status' => 'completed',
                'completed_at' => now()
            ]);

            // Envoyer l'email de fin de location avec rappel de retour
            Mail::to($this->orderLocation->user->email)->send(
                new RentalEndedMail($this->orderLocation)
            );

            Log::info('Fin de location traitée avec succès', [
                'order_location_id' => $this->orderLocation->id,
                'order_number' => $this->orderLocation->order_number,
                'user_email' => $this->orderLocation->user->email,
                'end_date' => $this->orderLocation->end_date->format('Y-m-d')
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors du traitement de fin de location', [
                'order_location_id' => $this->orderLocation->id,
                'error' => $e->getMessage()
            ]);
            
            // Re-programmer le job dans 30 minutes en cas d'erreur temporaire
            $this->release(1800);
        }
    }
}
