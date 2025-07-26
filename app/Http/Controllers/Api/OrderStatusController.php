<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderStatusController extends Controller
{
    /**
     * Récupérer le statut d'une commande
     */
    public function getStatus(Order $order)
    {
        // Vérifier que l'utilisateur a accès à cette commande
        if ($order->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'status' => 'success',
            'order' => [
                'status' => $order->status,
                'payment_status' => $order->payment_status,
                'can_be_cancelled' => $order->can_be_cancelled,
                'status_updated_at' => $order->status_updated_at,
                'delivered_at' => $order->delivered_at,
                'has_returnable_items' => $order->has_returnable_items,
                'invoice_number' => $order->invoice_number,
            ],
            'time_since_creation' => $order->created_at->diffInSeconds(now()),
            'next_status_in' => $this->getNextStatusTime($order),
        ]);
    }

    /**
     * Calculer le temps avant le prochain changement de statut
     */
    private function getNextStatusTime(Order $order)
    {
        if (!$order->can_be_cancelled) {
            return null;
        }

        $statusProgression = [
            'confirmed' => 15,
            'preparing' => 15, 
            'shipped' => 15
        ];

        $timeLimit = $statusProgression[$order->status] ?? null;
        if (!$timeLimit) {
            return null;
        }

        $secondsSinceUpdate = now()->diffInSeconds($order->status_updated_at);
        $remainingSeconds = max(0, $timeLimit - $secondsSinceUpdate);

        return $remainingSeconds;
    }

    /**
     * Déclencher manuellement le passage au statut suivant (pour test)
     */
    public function triggerNextStatus(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $statusProgression = [
            'confirmed' => 'preparing',
            'preparing' => 'shipped',
            'shipped' => 'delivered'
        ];

        $nextStatus = $statusProgression[$order->status] ?? null;
        if ($nextStatus) {
            $order->updateStatus($nextStatus);
            return response()->json(['message' => 'Status updated', 'new_status' => $nextStatus]);
        }

        return response()->json(['error' => 'Cannot progress status'], 400);
    }
}
