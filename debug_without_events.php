<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

// Démarrer l'application
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Désactiver les observers pour éviter les boucles
\App\Models\OrderLocation::unsetEventDispatcher();

// Maintenant on peut faire des requêtes sans déclencher les events
echo "=== Debug Order Locations ===\n";

try {
    // Regarder spécifiquement la commande qui pose problème
    $problematicOrder = \App\Models\OrderLocation::where('order_number', 'LOC-202508137698')->first();
    
    if ($problematicOrder) {
        echo "=== Commande Problématique ===\n";
        echo "Order Number: {$problematicOrder->order_number}\n";
        echo "Status: {$problematicOrder->status}\n";
        echo "Created At: {$problematicOrder->created_at}\n";
        echo "Cancelled At: {$problematicOrder->cancelled_at}\n";
        echo "User ID: {$problematicOrder->user_id}\n";
        echo "Total Amount: {$problematicOrder->total_amount}\n";
        echo "Payment Status: {$problematicOrder->payment_status}\n";
        echo "Cancellation Reason: " . ($problematicOrder->cancellation_reason ?? 'Aucune') . "\n";
        
        // Vérifier les items
        $items = $problematicOrder->items;
        echo "Items Count: " . $items->count() . "\n";
        foreach ($items as $item) {
            echo "- Item: {$item->product_name} (Qty: {$item->quantity})\n";
        }
    } else {
        echo "Commande LOC-202508137698 non trouvée\n";
    }
    
    $orders = \App\Models\OrderLocation::latest()->take(5)->get();
    
    echo "Dernières 5 commandes:\n";
    foreach ($orders as $order) {
        echo "- {$order->order_number} | Status: {$order->status} | Créé: {$order->created_at}\n";
    }
    
    $pendingOrders = \App\Models\OrderLocation::where('status', 'pending')->count();
    echo "\nCommandes en attente: {$pendingOrders}\n";
    
    $cancelledOrders = \App\Models\OrderLocation::where('status', 'cancelled')->count();
    echo "Commandes annulées: {$cancelledOrders}\n";
    
    // Vérifier le panier de location actuel
    $cartLocation = \App\Models\CartLocation::where('user_id', 1)->with('items')->first();
    if ($cartLocation) {
        echo "\nPanier de location actuel:\n";
        echo "- ID: {$cartLocation->id}\n";
        echo "- Items: " . $cartLocation->items->count() . "\n";
    } else {
        echo "\nAucun panier de location actuel\n";
    }
    
} catch (\Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== Fin Debug ===\n";
