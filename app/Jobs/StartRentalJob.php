<?php

namespace App\Jobs;

use App\Models\OrderLocation;
use App\Mail\RentalStartedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class StartRentalJob implements ShouldQueue
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
            // Vérifier que la location est toujours confirmée et n'a pas été annulée
            if ($this->orderLocation->status !== 'confirmed') {
                Log::info('Location non démarrée car statut incorrect', [
                    'order_location_id' => $this->orderLocation->id,
                    'current_status' => $this->orderLocation->status
                ]);
                return;
            }

            // Marquer la location comme démarrée
            $this->orderLocation->update([
                'status' => 'active',
                'started_at' => now()
            ]);

            // Envoyer l'email de début de location
            Mail::to($this->orderLocation->user->email)->send(
                new RentalStartedMail($this->orderLocation)
            );

            Log::info('Location démarrée avec succès', [
                'order_location_id' => $this->orderLocation->id,
                'order_number' => $this->orderLocation->order_number,
                'user_email' => $this->orderLocation->user->email
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors du démarrage de la location', [
                'order_location_id' => $this->orderLocation->id,
                'error' => $e->getMessage()
            ]);
            
            // Re-programmer le job dans 5 minutes en cas d'erreur temporaire
            $this->release(300);
        }
    }
}
