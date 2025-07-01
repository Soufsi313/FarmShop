<?php

require_once 'vendor/autoload.php';

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Order;

echo "🔍 Diagnostic de la commande #FS202507015879...\n\n";

// Récupérer la commande
$order = Order::where('order_number', 'FS202507015879')->with(['items.product'])->first();

if (!$order) {
    echo "❌ Commande non trouvée\n";
    exit;
}

echo "📦 Commande: {$order->order_number}\n";
echo "💰 Montant total: {$order->total_amount}€\n";
echo "📊 Status: {$order->status}\n";
echo "📅 Livrée le: " . ($order->delivered_at ?? 'Non défini') . "\n\n";

echo "📋 Articles de la commande:\n";
foreach ($order->items as $index => $item) {
    echo "  " . ($index + 1) . ". {$item->product_name}\n";
    echo "     - ID produit: {$item->product_id}\n";
    echo "     - Quantité: {$item->quantity}\n";
    echo "     - Prix unitaire: {$item->unit_price}€\n";
    echo "     - Prix total: {$item->total_price}€\n";
    echo "     - Périssable (item): " . ($item->is_perishable ? 'Oui' : 'Non') . "\n";
    
    if ($item->product) {
        echo "     - Périssable (produit): " . ($item->product->isPerishable() ? 'Oui' : 'Non') . "\n";
        echo "     - Prix actuel produit: {$item->product->price}€\n";
    } else {
        echo "     - ⚠️  Produit introuvable en base\n";
    }
    echo "\n";
}

// Test de l'éligibilité au retour
echo "🧪 Test d'éligibilité au retour:\n";
$returnableItems = [];
$nonReturnableItems = [];
$totalReturnableAmount = 0;

foreach ($order->items as $item) {
    $isPerishable = $item->product ? $item->product->isPerishable() : $item->is_perishable;
    
    if (!$isPerishable) {
        $returnableItems[] = [
            'id' => $item->id,
            'product_name' => $item->product_name,
            'quantity' => $item->quantity,
            'unit_price' => $item->unit_price,
            'total_price' => $item->total_price
        ];
        $totalReturnableAmount += $item->total_price;
    } else {
        $nonReturnableItems[] = [
            'product_name' => $item->product_name,
            'reason' => 'Produit périssable'
        ];
    }
}

echo "✅ Produits retournables: " . count($returnableItems) . "\n";
echo "❌ Produits non retournables: " . count($nonReturnableItems) . "\n";
echo "💰 Montant total retournable: {$totalReturnableAmount}€\n\n";

if (count($returnableItems) > 0) {
    echo "📋 Détail des produits retournables:\n";
    foreach ($returnableItems as $item) {
        echo "  - {$item['product_name']}: {$item['quantity']} × {$item['unit_price']}€ = {$item['total_price']}€\n";
    }
}

if (count($nonReturnableItems) > 0) {
    echo "\n❌ Produits non retournables:\n";
    foreach ($nonReturnableItems as $item) {
        echo "  - {$item['product_name']} ({$item['reason']})\n";
    }
}

echo "\n🎯 Diagnostic terminé!\n";
