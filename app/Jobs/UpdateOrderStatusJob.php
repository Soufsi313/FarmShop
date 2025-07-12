<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class UpdateOrderStatusJob implements ShouldQueue
{
    use Queueable;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Log::info('Début de la mise à jour automatique des statuts de commandes');

            // Mettre à jour les commandes en attente de confirmation (après 5 minutes)
            $pendingOrders = Order::where('status', 'pending')
                ->where('created_at', '<=', now()->subMinutes(5))
                ->get();

            foreach ($pendingOrders as $order) {
                if ($order->payment_status === 'paid') {
                    $order->updateStatus('confirmed');
                    Log::info("Commande {$order->order_number} confirmée automatiquement");
                }
            }

            // Mettre à jour les commandes confirmées vers préparation (après 2 heures)
            $confirmedOrders = Order::where('status', 'confirmed')
                ->where('status_updated_at', '<=', now()->subHours(2))
                ->get();

            foreach ($confirmedOrders as $order) {
                $order->updateStatus('preparing');
                Log::info("Commande {$order->order_number} passée en préparation automatiquement");
            }

            // Mettre à jour les commandes en préparation vers expédition (après 24 heures)
            $preparingOrders = Order::where('status', 'preparing')
                ->where('status_updated_at', '<=', now()->subHours(24))
                ->get();

            foreach ($preparingOrders as $order) {
                $order->updateStatus('shipped');
                Log::info("Commande {$order->order_number} expédiée automatiquement");
            }

            // Mettre à jour les commandes expédiées vers livraison (après 3 jours)
            $shippedOrders = Order::where('status', 'shipped')
                ->where('status_updated_at', '<=', now()->subDays(3))
                ->get();

            foreach ($shippedOrders as $order) {
                $order->updateStatus('delivered');
                Log::info("Commande {$order->order_number} livrée automatiquement");
            }

            Log::info('Fin de la mise à jour automatique des statuts de commandes');

        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour automatique des statuts: ' . $e->getMessage());
            throw $e;
        }
    }
}
