<?php

require __DIR__ . '/vendor/autoload.php';

use App\Models\Order;

// Initialiser Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== STATUT DES COMMANDES RÉCENTES ===\n\n";

$recentOrders = Order::latest()->take(5)->get();

foreach ($recentOrders as $order) {
    echo "Commande: {$order->order_number}\n";
    echo "Statut: {$order->status}\n";
    echo "Statut paiement: {$order->payment_status}\n";
    echo "Créée: {$order->created_at}\n";
    echo "Mise à jour: {$order->updated_at}\n";
    
    if ($order->status_history) {
        echo "Historique des statuts:\n";
        foreach ($order->status_history as $history) {
            echo "  - {$history['from']} → {$history['to']} à {$history['timestamp']}\n";
        }
    }
    
    echo "---\n";
}
