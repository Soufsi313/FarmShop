<?php

namespace App\Jobs;

use App\Models\OrderLocation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class UpdateRentalStatusJob implements ShouldQueue
{
    use Queueable;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Log::info('Début de la vérification automatique des statuts de location');

            // Vérifier et mettre à jour les statuts des locations
            OrderLocation::checkStatusUpdates();

            Log::info('Fin de la vérification automatique des statuts de location');

        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour des statuts de location: ' . $e->getMessage());
            throw $e;
        }
    }
}
