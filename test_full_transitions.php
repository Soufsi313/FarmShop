<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Order;
use App\Models\Product;

echo "=== Test des transitions automatiques complètes ===\n\n";

// Récupérer la dernière commande ou en créer une pour test
$order = Order::latest()->first();

if (!$order) {
    echo "Création d'une commande de test...\n";
    
    $product = Product::where('quantity', '>', 0)->first();
    if (!$product) {
        echo "Aucun produit avec stock disponible\n";
        exit(1);
    }
    
    $order = Order::create([
        'user_id' => 1,
        'order_number' => 'TEST-' . time(),
        'total_amount' => $product->prix,
        'tva_amount' => $product->prix * 0.20,
        'status' => 'pending',
        'payment_status' => 'paid'
    ]);
    
    // Créer un item
    $order->items()->create([
        'product_id' => $product->id,
        'quantity' => 1,
        'unit_price' => $product->prix,
        'tva_rate' => 20,
        'product_name' => $product->name,
        'product_category' => $product->category->toArray() ?? []
    ]);
    
    echo "Commande test créée: {$order->order_number}\n\n";
}

echo "Commande utilisée: {$order->order_number} (ID: {$order->id})\n";
echo "Statut actuel: {$order->status}\n\n";

// Vérifier le stock avant
$productStock = [];
foreach ($order->items as $item) {
    $productStock[$item->product_id] = $item->product->quantity;
    echo "Stock initial du produit {$item->product->name}: {$item->product->quantity}\n";
}

echo "\n=== Test des transitions automatiques ===\n";

// Si la commande n'est pas confirmée, la confirmer
if ($order->status === 'pending') {
    echo "1. Confirmation de la commande...\n";
    $order->updateStatus('confirmed');
    echo "   ✅ Statut: confirmed - Transition vers preparing programmée\n\n";
} else {
    echo "Commande déjà confirmée\n\n";
}

echo "2. Attente des transitions automatiques...\n";
echo "   - preparing dans 15 secondes\n";
echo "   - shipped dans 30 secondes\n";
echo "   - delivered dans 45 secondes\n\n";

echo "3. Lancement du worker pour traiter les jobs...\n";
echo "   Vous pouvez suivre les logs avec: tail -f storage/logs/laravel.log\n\n";

echo "=== Test d'annulation et restauration de stock ===\n";

// Créer une autre commande pour tester l'annulation
$cancelOrder = Order::create([
    'user_id' => 1,
    'order_number' => 'CANCEL-' . time(),
    'total_amount' => 50,
    'tva_amount' => 10,
    'status' => 'confirmed',
    'payment_status' => 'paid'
]);

$product = Product::first();
$initialStock = $product->quantity;

$cancelOrder->items()->create([
    'product_id' => $product->id,
    'quantity' => 2,
    'unit_price' => 25,
    'tva_rate' => 20,
    'product_name' => $product->name,
    'product_category' => $product->category->toArray() ?? []
]);

echo "Commande d'annulation créée: {$cancelOrder->order_number}\n";
echo "Stock avant annulation: {$initialStock}\n";

try {
    $cancelOrder->cancel('Test d\'annulation');
    $product->refresh();
    echo "✅ Commande annulée avec succès\n";
    echo "Stock après annulation: {$product->quantity} (+2 unités restaurées)\n";
    
    if ($product->quantity == $initialStock + 2) {
        echo "✅ SUCCÈS: Le stock a été correctement restauré!\n";
    } else {
        echo "❌ ERREUR: Le stock n'a pas été restauré correctement\n";
    }
} catch (Exception $e) {
    echo "❌ Erreur lors de l'annulation: {$e->getMessage()}\n";
}

echo "\n=== Instructions pour voir les transitions en temps réel ===\n";
echo "Dans un autre terminal, lancez:\n";
echo "php artisan queue:work --timeout=60\n\n";
echo "Pour voir les logs:\n";
echo "tail -f storage/logs/laravel.log\n\n";
echo "La commande {$order->order_number} devrait progresser automatiquement!\n";
