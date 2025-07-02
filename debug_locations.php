<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\OrderLocation;
use App\Models\OrderItemLocation;
use App\Models\Product;

echo "=== DEBUG LOCATIONS ===" . PHP_EOL;

// Test basique : récupérer toutes les commandes
try {
    $orders = OrderLocation::all();
    echo "✓ {$orders->count()} commandes trouvées" . PHP_EOL;
} catch (Exception $e) {
    echo "❌ Erreur lors de la récupération des commandes : " . $e->getMessage() . PHP_EOL;
    exit(1);
}

// Test avec relations
try {
    $orders = OrderLocation::with(['user', 'items'])->take(1)->get();
    echo "✓ Relations user et items chargées" . PHP_EOL;
} catch (Exception $e) {
    echo "❌ Erreur avec relations user et items : " . $e->getMessage() . PHP_EOL;
}

// Test avec relation items.product
try {
    $orders = OrderLocation::with(['user', 'items.product'])->take(1)->get();
    echo "✓ Relation items.product chargée" . PHP_EOL;
    
    if ($orders->count() > 0) {
        $order = $orders->first();
        echo "  - Commande : {$order->order_number}" . PHP_EOL;
        echo "  - Utilisateur : {$order->user->name}" . PHP_EOL;
        echo "  - Items : {$order->items->count()}" . PHP_EOL;
        
        foreach ($order->items as $item) {
            if ($item->product) {
                echo "    * Produit : {$item->product->name}" . PHP_EOL;
            } else {
                echo "    * ERREUR: Produit non trouvé (ID: {$item->product_id})" . PHP_EOL;
            }
        }
    }
} catch (Exception $e) {
    echo "❌ Erreur avec relation items.product : " . $e->getMessage() . PHP_EOL;
}

// Test statistiques
try {
    $stats = [
        'total' => OrderLocation::count(),
        'pending' => OrderLocation::where('status', 'pending')->count(),
        'confirmed' => OrderLocation::where('status', 'confirmed')->count(),
        'active' => OrderLocation::where('status', 'active')->count(),
        'completed' => OrderLocation::where('status', 'completed')->count(),
        'overdue' => OrderLocation::where('status', 'overdue')->count(),
        'cancelled' => OrderLocation::where('status', 'cancelled')->count(),
    ];
    
    echo "✓ Statistiques :" . PHP_EOL;
    foreach ($stats as $status => $count) {
        echo "  - {$status}: {$count}" . PHP_EOL;
    }
} catch (Exception $e) {
    echo "❌ Erreur avec les statistiques : " . $e->getMessage() . PHP_EOL;
}

echo PHP_EOL . "Test terminé." . PHP_EOL;
