<?php

namespace App\Jobs;

use App\Models\OrderLocation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class AutoUpdateRentalStatusJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('🔄 Démarrage de la mise à jour automatique des statuts de location');

        $this->activateConfirmedOrders();
        $this->completeActiveOrders();
        $this->checkOverdueOrders();

        Log::info('✅ Mise à jour automatique des statuts terminée');
    }

    /**
     * Activer les commandes confirmées dont la date de début est arrivée
     */
    private function activateConfirmedOrders()
    {
        $orders = OrderLocation::where('status', 'confirmed')
            ->where('start_date', '<=', now())
            ->get();

        foreach ($orders as $order) {
            Log::info("🟢 Activation automatique de la commande {$order->order_number}");
            $order->update(['status' => 'active']);
        }

        if ($orders->count() > 0) {
            Log::info("✅ {$orders->count()} commande(s) activée(s) automatiquement");
        }
    }

    /**
     * Marquer comme terminées les commandes actives dont la date de fin est passée
     */
    private function completeActiveOrders()
    {
        $orders = OrderLocation::where('status', 'active')
            ->where('end_date', '<=', now())
            ->get();

        foreach ($orders as $order) {
            Log::info("🔴 Marquage automatique comme terminée de la commande {$order->order_number}");
            $order->update(['status' => 'completed']);
        }

        if ($orders->count() > 0) {
            Log::info("✅ {$orders->count()} commande(s) marquée(s) comme terminées automatiquement");
        }
    }

    /**
     * Vérifier les commandes en retard et appliquer les frais
     */
    private function checkOverdueOrders()
    {
        // Commandes terminées non fermées depuis plus de 24h
        $overdueOrders = OrderLocation::where('status', 'completed')
            ->where('completed_at', '<=', now()->subDay())
            ->get();

        foreach ($overdueOrders as $order) {
            $lateDays = $order->calculateLateDays();
            $lateFees = $lateDays * 10; // 10€ par jour de retard

            Log::warning("⚠️ Commande {$order->order_number} en retard de {$lateDays} jour(s). Frais: {$lateFees}€");

            $order->update([
                'late_days' => $lateDays,
                'late_fees' => $lateFees
            ]);
        }

        if ($overdueOrders->count() > 0) {
            Log::info("⚠️ {$overdueOrders->count()} commande(s) en retard traitée(s)");
        }
    }
}
