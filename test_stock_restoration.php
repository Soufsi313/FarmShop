<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Order;
use App\Models\Product;

echo "=== Test spécifique de la restauration du stock lors d'annulation ===\n\n";

// Prendre une commande existante et la dupliquer pour test
$originalOrder = Order::whereHas('items')->latest()->first();

if (!$originalOrder) {
    echo "Aucune commande avec des items trouvée\n";
    exit(1);
}

// Dupliquer la commande pour test
$testOrder = $originalOrder->replicate();
$testOrder->order_number = 'STOCK-TEST-' . time();
$testOrder->status = 'confirmed';
$testOrder->save();

echo "Commande de test créée: {$testOrder->order_number}\n\n";

// Dupliquer les items
$stockBeforeCancel = [];
foreach ($originalOrder->items as $item) {
    $newItem = $item->replicate();
    $newItem->order_id = $testOrder->id;
    $newItem->save();
    
    // Enregistrer le stock avant annulation
    $product = $item->product;
    $stockBeforeCancel[$product->id] = $product->quantity;
    
    echo "Produit: {$product->name}\n";
    echo "  - Quantité commandée: {$item->quantity}\n";
    echo "  - Stock avant annulation: {$product->quantity}\n";
}

echo "\n--- ANNULATION DE LA COMMANDE ---\n";

try {
    $testOrder->cancel('Test de restauration du stock');
    echo "✅ Commande annulée avec succès\n\n";
    
    echo "Vérification de la restauration du stock:\n";
    
    foreach ($testOrder->items as $item) {
        $product = $item->product->fresh();
        $stockBefore = $stockBeforeCancel[$product->id];
        $expectedStock = $stockBefore + $item->quantity;
        $actualStock = $product->quantity;
        
        echo "Produit: {$product->name}\n";
        echo "  - Stock avant: {$stockBefore}\n";
        echo "  - Quantité restaurée: +{$item->quantity}\n";
        echo "  - Stock attendu: {$expectedStock}\n";
        echo "  - Stock actuel: {$actualStock}\n";
        
        if ($actualStock == $expectedStock) {
            echo "  ✅ SUCCÈS: Stock correctement restauré!\n";
        } else {
            echo "  ❌ ERREUR: Stock non restauré correctement\n";
        }
        echo "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur lors de l'annulation: {$e->getMessage()}\n";
}

echo "=== Vérification de l'état de la commande ===\n";
$testOrder->refresh();
echo "Statut: {$testOrder->status}\n";
echo "Date d'annulation: {$testOrder->cancelled_at}\n";
echo "Raison: {$testOrder->cancellation_reason}\n";
echo "Peut être annulée: " . ($testOrder->can_be_cancelled ? 'Oui' : 'Non') . "\n";
