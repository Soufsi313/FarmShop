<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Notifications\OrderStatusChanged;
use Illuminate\Console\Command;
use Carbon\Carbon;

class AutomateOrderStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:automate-statuses {--dry-run : Run without making actual changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatise les changements de statuts des commandes selon les règles métier';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $updated = 0;

        $this->info('Démarrage de l\'automatisation des statuts de commandes...');

        if ($dryRun) {
            $this->warn('Mode DRY-RUN activé - Aucune modification ne sera effectuée');
        }

        // 1. Auto-confirmer les commandes payées depuis plus de 1 heure
        $this->info('Vérification des commandes à confirmer...');
        $pendingOrders = Order::where('status', Order::STATUS_PENDING)
            ->where('payment_status', Order::PAYMENT_PAID)
            ->where('created_at', '<=', Carbon::now()->subHour())
            ->get();

        $this->line("Commandes trouvées à confirmer : {$pendingOrders->count()}");

        foreach ($pendingOrders as $order) {
            $oldStatus = $order->status;
            
            if (!$dryRun) {
                $order->update([
                    'status' => Order::STATUS_CONFIRMED,
                    'confirmed_at' => Carbon::now()
                ]);
                
                // Envoyer notification
                $order->user->notify(new OrderStatusChanged($order, $oldStatus));
            }

            $this->line("  - Commande #{$order->order_number} : {$oldStatus} → " . Order::STATUS_CONFIRMED);
            $updated++;
        }

        // 2. Auto-expédier les commandes confirmées depuis plus de 2 jours
        $this->info('Vérification des commandes à expédier...');
        $confirmedOrders = Order::where('status', Order::STATUS_CONFIRMED)
            ->where('confirmed_at', '<=', Carbon::now()->subDays(2))
            ->get();

        $this->line("Commandes trouvées à expédier : {$confirmedOrders->count()}");

        foreach ($confirmedOrders as $order) {
            $oldStatus = $order->status;
            
            if (!$dryRun) {
                $order->update([
                    'status' => Order::STATUS_SHIPPED,
                    'shipped_at' => Carbon::now()
                ]);
                
                // Envoyer notification
                $order->user->notify(new OrderStatusChanged($order, $oldStatus));
            }

            $this->line("  - Commande #{$order->order_number} : {$oldStatus} → " . Order::STATUS_SHIPPED);
            $updated++;
        }

        // 3. Auto-livrer les commandes expédiées depuis plus de 3 jours
        $this->info('Vérification des commandes à livrer...');
        $shippedOrders = Order::where('status', Order::STATUS_SHIPPED)
            ->where('shipped_at', '<=', Carbon::now()->subDays(3))
            ->get();

        $this->line("Commandes trouvées à livrer : {$shippedOrders->count()}");

        foreach ($shippedOrders as $order) {
            $oldStatus = $order->status;
            
            if (!$dryRun) {
                $order->update([
                    'status' => Order::STATUS_DELIVERED,
                    'delivered_at' => Carbon::now()
                ]);
                
                // Envoyer notification
                $order->user->notify(new OrderStatusChanged($order, $oldStatus));
            }

            $this->line("  - Commande #{$order->order_number} : {$oldStatus} → " . Order::STATUS_DELIVERED);
            $updated++;
        }

        $this->info("Automatisation terminée. {$updated} commandes mises à jour.");

        if ($dryRun) {
            $this->warn('Mode DRY-RUN : Aucune modification réelle n\'a été effectuée.');
        }

        return Command::SUCCESS;
    }
}
