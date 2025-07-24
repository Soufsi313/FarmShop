<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class ProcessOrderStatusProgression implements ShouldQueue
{
    use Queueable;

    protected $orderId;
    protected $targetStatus;

    /**
     * Create a new job instance.
     */
    public function __construct(int $orderId, string $targetStatus)
    {
        $this->orderId = $orderId;
        $this->targetStatus = $targetStatus;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $order = Order::find($this->orderId);
        
        if (!$order) {
            Log::warning("Commande {$this->orderId} introuvable pour progression de statut");
            return;
        }

        // Vérifier si la commande a été annulée entre temps
        if ($order->status === 'cancelled') {
            Log::info("Commande {$order->order_number} annulée, arrêt de la progression");
            return;
        }

        // Vérifier si on peut encore progresser vers ce statut
        if (!$this->canProgressTo($order->status, $this->targetStatus)) {
            Log::warning("Impossible de progresser de '{$order->status}' vers '{$this->targetStatus}' pour la commande {$order->order_number}");
            return;
        }

        // Mettre à jour le statut
        $order->updateStatus($this->targetStatus);
        
        Log::info("Commande {$order->order_number} progressée vers '{$this->targetStatus}'");

        // Programmer la prochaine étape si nécessaire
        $this->scheduleNextProgression($order);
    }

    /**
     * Programmer la prochaine progression
     */
    private function scheduleNextProgression(Order $order): void
    {
        $nextStatus = $this->getNextStatus($order->status);
        
        if ($nextStatus) {
            // Programmer la prochaine étape dans 20 secondes
            ProcessOrderStatusProgression::dispatch($order->id, $nextStatus)
                ->delay(now()->addSeconds(20));
                
            Log::info("Prochaine progression programmée pour la commande {$order->order_number}: '{$order->status}' -> '{$nextStatus}' dans 20 secondes");
        }
    }

    /**
     * Obtenir le prochain statut dans la progression
     */
    private function getNextStatus(string $currentStatus): ?string
    {
        $progression = [
            'confirmed' => 'preparing',
            'preparing' => 'shipped', 
            'shipped' => 'delivered'
        ];

        return $progression[$currentStatus] ?? null;
    }

    /**
     * Vérifier si on peut progresser d'un statut à un autre
     */
    private function canProgressTo(string $currentStatus, string $targetStatus): bool
    {
        $validProgressions = [
            'confirmed' => ['preparing'],
            'preparing' => ['shipped'],
            'shipped' => ['delivered']
        ];

        return in_array($targetStatus, $validProgressions[$currentStatus] ?? []);
    }
}
