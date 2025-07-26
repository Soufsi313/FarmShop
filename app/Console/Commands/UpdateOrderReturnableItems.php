<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class UpdateOrderReturnableItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:update-returnable-items';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update has_returnable_items and has_non_returnable_items for existing orders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Mise à jour des propriétés de retour des commandes...');
        
        $orders = Order::with(['items.product.category'])->get();
        $updated = 0;
        
        foreach ($orders as $order) {
            try {
                $order->checkReturnableItems();
                $updated++;
                $this->info("Commande #{$order->order_number} mise à jour");
            } catch (\Exception $e) {
                $this->error("Erreur pour la commande #{$order->order_number}: " . $e->getMessage());
            }
        }
        
        $this->info("Terminé ! {$updated} commandes mises à jour.");
        return 0;
    }
}
