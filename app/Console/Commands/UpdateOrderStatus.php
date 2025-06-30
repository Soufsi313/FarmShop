<?php

namespace App\Console\Commands;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateOrderStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatiquement mettre à jour le statut des commandes';

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
        
        // Passer les commandes confirmées à "en préparation" après 1min30
        $ordersToProcess = Order::where('status', Order::STATUS_CONFIRMED)
            ->where('created_at', '<=', $now->copy()->subSeconds(90))
            ->get();
            
        foreach ($ordersToProcess as $order) {
            $order->update(['status' => Order::STATUS_PREPARATION]);
            $this->info("Commande #{$order->id} passée en préparation");
        }
        
        // Passer les commandes en préparation à "expédiée" après encore 1min30
        $ordersToShip = Order::where('status', Order::STATUS_PREPARATION)
            ->where('updated_at', '<=', $now->copy()->subSeconds(90))
            ->get();
            
        foreach ($ordersToShip as $order) {
            $order->update(['status' => Order::STATUS_SHIPPED]);
            $this->info("Commande #{$order->id} expédiée");
        }
        
        $this->info('Mise à jour des statuts terminée');
        return 0;
    }
}
