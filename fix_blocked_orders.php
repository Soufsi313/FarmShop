<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Order;
use App\Jobs\ProcessSingleOrderStatusJob;

echo "=== RÉINITIALISATION DES TRANSITIONS BLOQUÉES ===\n\n";

// Récupérer les commandes bloquées au statut "confirmed"
$blockedOrders = Order::where('status', 'confirmed')->get();

foreach ($blockedOrders as $order) {
    echo "Commande ID: {$order->id} - {$order->order_number}\n";
    echo "Statut actuel: {$order->status}\n";
    echo "Dernière mise à jour: {$order->updated_at}\n";
    
    // Déclencher la transition suivante immédiatement
    try {
        $job = new ProcessSingleOrderStatusJob($order->id, 'preparing');
        $job->handle();
        echo "✅ Transition vers 'preparing' déclenchée\n";
    } catch (Exception $e) {
        echo "❌ Erreur: {$e->getMessage()}\n";
    }
    
    echo "---\n";
}

echo "\n=== VÉRIFICATION FINALE ===\n";
$orders = Order::latest()->take(3)->get();
foreach ($orders as $order) {
    echo "Commande {$order->id}: {$order->status}\n";
}
