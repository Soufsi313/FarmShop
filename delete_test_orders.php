<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Numéros des commandes à supprimer
$orderNumbers = [
    'ORD-2025080008',
    'ORD-2025080005', 
    'ORD-2025080004'
];

echo "Suppression des commandes de test...\n";

foreach ($orderNumbers as $orderNumber) {
    try {
        // Trouver la commande
        $order = Order::where('order_number', $orderNumber)->first();
        
        if ($order) {
            echo "Commande trouvée: {$orderNumber} (ID: {$order->id})\n";
            
            // Supprimer les items de la commande d'abord
            $itemsDeleted = OrderItem::where('order_id', $order->id)->delete();
            echo "  - {$itemsDeleted} items supprimés\n";
            
            // Supprimer la commande (force delete pour bypasser les restrictions)
            $order->forceDelete();
            echo "  - Commande supprimée définitivement\n";
            
        } else {
            echo "Commande non trouvée: {$orderNumber}\n";
        }
        
    } catch (Exception $e) {
        echo "Erreur lors de la suppression de {$orderNumber}: " . $e->getMessage() . "\n";
    }
}

echo "\nSuppression terminée!\n";
