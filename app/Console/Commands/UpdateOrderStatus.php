<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Notifications\OrderStatusChanged;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateOrderStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:update-status {--dry-run : Run without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatiquement mettre à jour le statut des commandes avec notifications';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now = Carbon::now();
        $isDryRun = $this->option('dry-run');
        $updatedOrders = 0;

        if ($isDryRun) {
            $this->info('🔍 Mode DRY-RUN activé - Aucune modification ne sera effectuée');
        }

        $this->info('🚀 Démarrage de l\'automatisation des statuts de commandes...');
        
        // 1. Passer les commandes confirmées à "en préparation" après 1min (test)
        $this->processStatusTransition(
            Order::STATUS_CONFIRMED,
            Order::STATUS_PREPARATION,
            1, // 1 minute pour test
            'confirmed_at',
            $isDryRun,
            $updatedOrders
        );
        
        // 2. Passer les commandes en préparation à "expédiée" après 1min (test)
        $this->processStatusTransition(
            Order::STATUS_PREPARATION,
            Order::STATUS_SHIPPED,
            1, // 1 minute pour test
            'preparation_at',
            $isDryRun,
            $updatedOrders
        );
        
        // 3. Passer les commandes expédiées à "livrée" après 1min (test)
        $this->processStatusTransition(
            Order::STATUS_SHIPPED,
            Order::STATUS_DELIVERED,
            1, // 1 minute pour test
            'shipped_at',
            $isDryRun,
            $updatedOrders
        );

        if ($updatedOrders > 0) {
            $this->info("✅ {$updatedOrders} commande(s) mise(s) à jour avec succès");
            Log::info("OrderStatusAutomation: {$updatedOrders} commandes mises à jour", [
                'timestamp' => $now->toDateTimeString(),
                'dry_run' => $isDryRun
            ]);
        } else {
            $this->info('ℹ️  Aucune commande à mettre à jour');
        }
        
        return 0;
    }

    /**
     * Traiter la transition d'un statut vers un autre
     */
    private function processStatusTransition(
        string $fromStatus,
        string $toStatus,
        int $delayInSeconds,
        string $timestampField,
        bool $isDryRun,
        int &$updatedOrders
    ): void {
        $now = Carbon::now();
        
        // Utiliser le bon champ de timestamp pour calculer le délai
        $query = Order::where('status', $fromStatus);
        
        if ($timestampField === 'confirmed_at') {
            // Utiliser confirmed_at si disponible, sinon updated_at comme fallback
            $query->where(function($q) use ($now, $delayInSeconds) {
                $q->where(function($subQ) use ($now, $delayInSeconds) {
                    $subQ->whereNotNull('confirmed_at')
                         ->where('confirmed_at', '<=', $now->copy()->subSeconds($delayInSeconds));
                })->orWhere(function($subQ) use ($now, $delayInSeconds) {
                    $subQ->whereNull('confirmed_at')
                         ->where('updated_at', '<=', $now->copy()->subSeconds($delayInSeconds));
                });
            });
        } elseif ($timestampField === 'preparation_at') {
            $query->where(function($q) use ($now, $delayInSeconds) {
                $q->where(function($subQ) use ($now, $delayInSeconds) {
                    $subQ->whereNotNull('preparation_at')
                         ->where('preparation_at', '<=', $now->copy()->subSeconds($delayInSeconds));
                })->orWhere(function($subQ) use ($now, $delayInSeconds) {
                    $subQ->whereNull('preparation_at')
                         ->where('updated_at', '<=', $now->copy()->subSeconds($delayInSeconds));
                });
            });
        } elseif ($timestampField === 'shipped_at') {
            $query->where(function($q) use ($now, $delayInSeconds) {
                $q->where(function($subQ) use ($now, $delayInSeconds) {
                    $subQ->whereNotNull('shipped_at')
                         ->where('shipped_at', '<=', $now->copy()->subSeconds($delayInSeconds));
                })->orWhere(function($subQ) use ($now, $delayInSeconds) {
                    $subQ->whereNull('shipped_at')
                         ->where('updated_at', '<=', $now->copy()->subSeconds($delayInSeconds));
                });
            });
        }
        
        $orders = $query->with('user')->get();
        
        foreach ($orders as $order) {
            if ($isDryRun) {
                $this->line("🔍 [DRY-RUN] Commande #{$order->order_number} passerait de '{$fromStatus}' à '{$toStatus}'");
                continue;
            }

            $oldStatus = $order->status;
            
            // Mettre à jour le statut
            $order->update(['status' => $toStatus]);
            
            // Envoyer la notification email
            if ($order->user) {
                try {
                    $order->user->notify(new OrderStatusChanged($order, $oldStatus));
                    $this->info("📧 Notification envoyée pour commande #{$order->order_number}");
                } catch (\Exception $e) {
                    $this->error("❌ Erreur notification pour commande #{$order->order_number}: " . $e->getMessage());
                    Log::error("Erreur notification commande #{$order->order_number}", [
                        'error' => $e->getMessage(),
                        'order_id' => $order->id
                    ]);
                }
            }
            
            $this->info("✅ Commande #{$order->order_number} : {$fromStatus} → {$toStatus}");
            $updatedOrders++;
            
            // Log pour traçabilité
            Log::info("Order status updated", [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'from_status' => $oldStatus,
                'to_status' => $toStatus,
                'user_id' => $order->user_id,
                'timestamp' => $now->toDateTimeString()
            ]);
        }
    }
}
