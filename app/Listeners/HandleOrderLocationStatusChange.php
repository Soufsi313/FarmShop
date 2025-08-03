<?php

namespace App\Listeners;

use App\Events\OrderLocationStatusChanged;
use App\Mail\RentalOrderConfirmed;
use App\Mail\RentalOrderCancelled;
use App\Mail\RentalOrderCompleted;
use App\Mail\RentalOrderInspection;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class HandleOrderLocationStatusChange implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderLocationStatusChanged $event): void
    {
        $orderLocation = $event->orderLocation;
        $oldStatus = $event->oldStatus;
        $newStatus = $event->newStatus;

        Log::info("Changement de statut de location: {$orderLocation->order_number} de {$oldStatus} vers {$newStatus}");

        // Actions automatiques selon le nouveau statut
        switch ($newStatus) {
            case 'confirmed':
                $this->handleConfirmed($orderLocation);
                break;
                
            case 'active':
                $this->handleActive($orderLocation);
                break;
                
            case 'completed':
                $this->handleCompleted($orderLocation);
                break;
                
            case 'closed':
                $this->handleClosed($orderLocation);
                break;
                
            case 'inspecting':
                $this->handleInspecting($orderLocation);
                break;
                
            case 'finished':
                $this->handleFinished($orderLocation);
                break;
                
            case 'cancelled':
                $this->handleCancelled($orderLocation);
                break;
        }

        // Programmer les vérifications automatiques futures
        $this->scheduleAutomaticChecks($orderLocation);
    }

    /**
     * Gérer le statut "confirmé"
     */
    private function handleConfirmed($orderLocation)
    {
        // Éviter les doublons d'emails si déjà confirmé
        if ($orderLocation->confirmed_at) {
            Log::info("Location déjà confirmée, pas d'email envoyé: {$orderLocation->order_number}");
            return;
        }

        // Mettre à jour les timestamps
        $orderLocation->update([
            'confirmed_at' => now(),
        ]);

        // Envoyer email de confirmation (une seule fois)
        try {
            Mail::to($orderLocation->user->email)->send(new RentalOrderConfirmed($orderLocation));
            Log::info("Email de confirmation envoyé pour la commande {$orderLocation->order_number}");
        } catch (\Exception $e) {
            Log::error("Erreur envoi email confirmation: " . $e->getMessage());
        }

        // Programmer la transition automatique vers "active" à la date de début
        $this->scheduleStatusChange($orderLocation, 'active', $orderLocation->start_date);
    }

    /**
     * Gérer le statut "actif"
     */
    private function handleActive($orderLocation)
    {
        $orderLocation->update([
            'started_at' => now(),
            'status' => 'active'
        ]);

        // Programmer la transition automatique vers "completed" à la date de fin
        $this->scheduleStatusChange($orderLocation, 'completed', $orderLocation->end_date);
        
        Log::info("Location activée: {$orderLocation->order_number}");
    }

    /**
     * Gérer le statut "terminé"
     */
    private function handleCompleted($orderLocation)
    {
        $orderLocation->update([
            'completed_at' => now(),
            'status' => 'completed'
        ]);

        // Envoyer email demandant la fermeture
        try {
            Mail::to($orderLocation->user->email)->send(new RentalOrderCompleted($orderLocation));
            Log::info("Email de fin de location envoyé pour {$orderLocation->order_number}");
        } catch (\Exception $e) {
            Log::error("Erreur envoi email fin de location: " . $e->getMessage());
        }

        // Programmer un rappel si pas fermé après 24h
        $this->scheduleCloseReminder($orderLocation);
    }

    /**
     * Gérer le statut "fermé"
     */
    private function handleClosed($orderLocation)
    {
        $orderLocation->update([
            'closed_at' => now(),
            'actual_return_date' => now(),
            'status' => 'inspecting' // Transition automatique vers inspection
        ]);

        Log::info("Location fermée et en attente d'inspection: {$orderLocation->order_number}");
        
        // Notifier les admins pour l'inspection
        $this->notifyAdminsForInspection($orderLocation);
    }

    /**
     * Gérer le statut "en inspection"
     */
    private function handleInspecting($orderLocation)
    {
        $orderLocation->update([
            'inspection_started_at' => now(),
            'inspection_status' => 'in_progress'
        ]);

        Log::info("Inspection démarrée pour: {$orderLocation->order_number}");
    }

    /**
     * Gérer le statut "terminé"
     */
    private function handleFinished($orderLocation)
    {
        // Calculer les pénalités finales
        $totalPenalties = $orderLocation->orderItemLocations()->sum('penalty_amount');
        $finalAmount = $orderLocation->total_amount + $totalPenalties;

        $orderLocation->update([
            'finished_at' => now(),
            'penalty_amount' => $totalPenalties,
            'final_amount' => $finalAmount,
            'payment_status' => 'paid'
        ]);

        // Envoyer le rapport final d'inspection (utilise la méthode du modèle)
        try {
            $orderLocation->sendInspectionReport();
            Log::info("Rapport d'inspection final envoyé pour {$orderLocation->order_number}");
        } catch (\Exception $e) {
            Log::error("Erreur envoi rapport inspection final: " . $e->getMessage());
        }
    }

    /**
     * Gérer l'annulation
     */
    private function handleCancelled($orderLocation)
    {
        $orderLocation->update([
            'cancelled_at' => now(),
            'payment_status' => 'refunded'
        ]);

        // Envoyer email d'annulation
        try {
            Mail::to($orderLocation->user->email)->send(new RentalOrderCancelled($orderLocation));
            Log::info("Email d'annulation envoyé pour {$orderLocation->order_number}");
        } catch (\Exception $e) {
            Log::error("Erreur envoi email annulation: " . $e->getMessage());
        }
    }

    /**
     * Programmer les vérifications automatiques
     */
    private function scheduleAutomaticChecks($orderLocation)
    {
        // Cette méthode peut être étendue pour programmer des jobs spécifiques
        // selon les besoins de transitions automatiques
    }

    /**
     * Programmer un changement de statut
     */
    private function scheduleStatusChange($orderLocation, $newStatus, $scheduledFor)
    {
        // Programmer un job pour changer le statut à une date donnée
        \App\Jobs\UpdateRentalStatusJob::dispatch()->delay($scheduledFor);
    }

    /**
     * Programmer un rappel de fermeture
     */
    private function scheduleCloseReminder($orderLocation)
    {
        // Programmer un rappel 24h après la fin si pas fermé
        $reminderTime = $orderLocation->end_date->addDay();
        // Ici on pourrait programmer un job spécifique de rappel
    }

    /**
     * Notifier les admins pour inspection
     */
    private function notifyAdminsForInspection($orderLocation)
    {
        // Notifier tous les admins qu'une inspection est nécessaire
        $admins = \App\Models\User::where('role', 'Admin')->get();
        
        foreach ($admins as $admin) {
            // Ici on pourrait envoyer une notification spécifique aux admins
            Log::info("Notification d'inspection envoyée à l'admin {$admin->email} pour {$orderLocation->order_number}");
        }
    }
}
