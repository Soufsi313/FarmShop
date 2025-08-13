<?php

namespace App\Services;

use App\Models\OrderLocation;
use App\Mail\RentalStartedMail;
use App\Mail\RentalEndReminderMail;
use App\Mail\RentalEndedMail;
use App\Mail\RentalOverdueMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class RentalStatusService
{
    /**
     * Vérifier et mettre à jour tous les statuts des locations
     */
    public function checkAndUpdateAllRentalStatuses(): array
    {
        $updated = [
            'started' => [],
            'reminded' => [],
            'ended' => [],
            'overdue' => []
        ];

        // Vérifier les locations qui doivent commencer
        $this->checkAndStartRentals($updated);
        
        // Vérifier les locations qui doivent envoyer un rappel
        $this->checkAndSendReminders($updated);
        
        // Vérifier les locations qui doivent se terminer
        $this->checkAndEndRentals($updated);
        
        // Vérifier les locations en retard
        $this->checkAndMarkOverdue($updated);

        return $updated;
    }

    /**
     * Vérifier et démarrer les locations qui doivent commencer
     */
    private function checkAndStartRentals(array &$updated): void
    {
        $locationsToStart = OrderLocation::where('status', 'confirmed')
            ->where('start_date', '<=', now())
            ->whereNull('started_at')
            ->get();

        foreach ($locationsToStart as $location) {
            try {
                $location->update([
                    'status' => 'active',
                    'started_at' => now()
                ]);

                // Envoyer l'email de début de location
                Mail::to($location->user->email)->send(
                    new RentalStartedMail($location)
                );

                $updated['started'][] = [
                    'order_id' => $location->id,
                    'order_number' => $location->order_number,
                    'user_email' => $location->user->email
                ];

                Log::info('Location démarrée automatiquement', [
                    'order_location_id' => $location->id,
                    'order_number' => $location->order_number
                ]);

            } catch (\Exception $e) {
                Log::error('Erreur lors du démarrage automatique de location', [
                    'order_location_id' => $location->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Vérifier et envoyer les rappels de fin de location
     */
    private function checkAndSendReminders(array &$updated): void
    {
        $tomorrow = now()->addDay()->startOfDay();
        
        $locationsToRemind = OrderLocation::where('status', 'active')
            ->whereDate('end_date', $tomorrow->toDateString())
            ->where(function($query) {
                $query->whereNull('reminder_sent_at')
                    ->orWhere('reminder_sent_at', '<', now()->subDay());
            })
            ->get();

        foreach ($locationsToRemind as $location) {
            try {
                $location->update(['reminder_sent_at' => now()]);

                // Envoyer l'email de rappel 24h avant
                Mail::to($location->user->email)->send(
                    new RentalEndReminderMail($location)
                );

                $updated['reminded'][] = [
                    'order_id' => $location->id,
                    'order_number' => $location->order_number,
                    'user_email' => $location->user->email,
                    'end_date' => $location->end_date->format('d/m/Y')
                ];

                Log::info('Rappel 24h avant fin de location envoyé', [
                    'order_location_id' => $location->id,
                    'order_number' => $location->order_number,
                    'end_date' => $location->end_date->format('Y-m-d')
                ]);

            } catch (\Exception $e) {
                Log::error('Erreur lors de l\'envoi du rappel de location', [
                    'order_location_id' => $location->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Vérifier et terminer les locations qui doivent se terminer
     */
    private function checkAndEndRentals(array &$updated): void
    {
        $locationsToEnd = OrderLocation::where('status', 'active')
            ->where('end_date', '<', now())
            ->whereNull('ended_at')
            ->get();

        foreach ($locationsToEnd as $location) {
            try {
                $location->update([
                    'status' => 'ended', // Statut "ended" = En attente d'inspection
                    'ended_at' => now()
                ]);

                // Envoyer l'email de fin de location
                Mail::to($location->user->email)->send(
                    new RentalEndedMail($location)
                );

                $updated['ended'][] = [
                    'order_id' => $location->id,
                    'order_number' => $location->order_number,
                    'user_email' => $location->user->email,
                    'end_date' => $location->end_date->format('d/m/Y'),
                    'status' => 'Terminée - En attente d\'inspection'
                ];

                Log::info('Location terminée - En attente d\'inspection', [
                    'order_location_id' => $location->id,
                    'order_number' => $location->order_number,
                    'end_date' => $location->end_date->format('Y-m-d'),
                    'next_step' => 'Inspection et clôture admin'
                ]);

            } catch (\Exception $e) {
                Log::error('Erreur lors de la fin automatique de location', [
                    'order_location_id' => $location->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Vérifier et marquer les locations en retard
     */
    private function checkAndMarkOverdue(array &$updated): void
    {
        $yesterdayEnd = now()->subDay()->endOfDay();
        
        $locationsOverdue = OrderLocation::where('status', 'ended')
            ->where('end_date', '<', $yesterdayEnd)
            ->whereNull('overdue_notification_sent_at')
            ->get();

        foreach ($locationsOverdue as $location) {
            try {
                $location->update([
                    'status' => 'overdue',
                    'overdue_notification_sent_at' => now()
                ]);

                // Envoyer l'email de retard
                Mail::to($location->user->email)->send(
                    new RentalOverdueMail($location)
                );

                $updated['overdue'][] = [
                    'order_id' => $location->id,
                    'order_number' => $location->order_number,
                    'user_email' => $location->user->email
                ];

                Log::info('Location marquée en retard', [
                    'order_location_id' => $location->id,
                    'order_number' => $location->order_number
                ]);

            } catch (\Exception $e) {
                Log::error('Erreur lors du marquage de retard de location', [
                    'order_location_id' => $location->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Vérifier les statuts pour un utilisateur spécifique
     */
    public function checkUserRentalStatuses(int $userId): array
    {
        // Même logique mais filtrée par utilisateur
        $userLocations = OrderLocation::where('user_id', $userId)
            ->whereIn('status', ['confirmed', 'active', 'ended'])
            ->get();

        $updated = [
            'started' => [],
            'reminded' => [],
            'ended' => [],
            'overdue' => []
        ];

        foreach ($userLocations as $location) {
            // Vérifier si doit commencer
            if ($location->status === 'confirmed' && $location->start_date <= now() && !$location->started_at) {
                $location->update(['status' => 'active', 'started_at' => now()]);
                $updated['started'][] = $location;
                
                Mail::to($location->user->email)->send(new RentalStartedMail($location));
            }

            // Vérifier si doit se terminer
            if ($location->status === 'active' && $location->end_date < now() && !$location->ended_at) {
                $location->update(['status' => 'ended', 'ended_at' => now()]);
                $updated['ended'][] = $location;
                
                Mail::to($location->user->email)->send(new RentalEndedMail($location));
            }
        }

        return $updated;
    }
}
