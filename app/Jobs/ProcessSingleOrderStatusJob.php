<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessSingleOrderStatusJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    protected $orderId;
    protected $nextStatus;

    /**
     * Create a new job instance.
     */
    public function __construct($orderId, $nextStatus)
    {
        $this->orderId = $orderId;
        $this->nextStatus = $nextStatus;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $order = Order::find($this->orderId);
            
            if (!$order) {
                Log::warning("Commande {$this->orderId} non trouvée pour mise à jour de statut");
                return;
            }

            // Vérifier que la commande n'a pas été annulée entre temps
            if (in_array($order->status, ['cancelled', 'returned'])) {
                Log::info("Commande {$order->order_number} annulée/retournée, pas de mise à jour de statut");
                return;
            }

            // Vérifier que le statut actuel correspond à ce qui est attendu
            $statusProgression = [
                'confirmed' => 'preparing',
                'preparing' => 'shipped', 
                'shipped' => 'delivered'
            ];

            $currentStatus = $order->status;
            $expectedNextStatus = $statusProgression[$currentStatus] ?? null;

            if ($expectedNextStatus === $this->nextStatus) {
                $order->updateStatus($this->nextStatus);
                Log::info("Commande {$order->order_number} mise à jour automatiquement: {$currentStatus} → {$this->nextStatus}");
                
                // Programmer le prochain changement de statut si nécessaire
                if ($this->nextStatus === 'preparing') {
                    ProcessSingleOrderStatusJob::dispatch($this->orderId, 'shipped')
                        ->delay(now()->addSeconds(15)); // 15 secondes exactement
                } elseif ($this->nextStatus === 'shipped') {
                    ProcessSingleOrderStatusJob::dispatch($this->orderId, 'delivered')
                        ->delay(now()->addSeconds(15)); // 15 secondes exactement
                }
            } else {
                Log::info("Commande {$order->order_number} statut inattendu: {$currentStatus}, attendu pour: {$this->nextStatus}");
            }

        } catch (\Exception $e) {
            Log::error("Erreur lors de la mise à jour du statut de la commande {$this->orderId}: " . $e->getMessage());
            throw $e;
        }
    }
}
