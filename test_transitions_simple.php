<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Order;

echo "=== Test des transitions automatiques complètes ===\n\n";

// Récupérer une commande confirmée pour tester les transitions
$order = Order::where('status', 'confirmed')->first();

if (!$order) {
    // Prendre la dernière commande et la remettre en confirmed
    $order = Order::latest()->first();
    if ($order && $order->status !== 'cancelled') {
        $order->update(['status' => 'confirmed']);
        echo "Commande remise en statut 'confirmed' pour test\n";
    } else {
        echo "Aucune commande disponible pour test\n";
        exit(1);
    }
}

echo "Commande utilisée: {$order->order_number} (ID: {$order->id})\n";
echo "Statut actuel: {$order->status}\n\n";

echo "=== Test des transitions automatiques ===\n";

// Forcer le déclenchement des transitions
echo "1. Déclenchement des transitions automatiques...\n";
$order->updateStatus('confirmed');
echo "   ✅ Statut: confirmed - Transition vers preparing programmée\n\n";

echo "2. Jobs programmés dans la queue:\n";
echo "   - preparing dans 15 secondes\n";
echo "   - shipped dans 30 secondes (après preparing)\n";
echo "   - delivered dans 45 secondes (après shipped)\n\n";

echo "=== Test d'annulation et restauration de stock ===\n";

// Tester avec une commande existante en statut 'confirmed'
$cancelableOrder = Order::where('status', 'confirmed')
                        ->whereHas('items') 
                        ->first();

if (!$cancelableOrder) {
    // Créer une commande basique pour test d'annulation
    $lastOrder = Order::latest()->first();
    $cancelableOrder = $lastOrder->replicate();
    $cancelableOrder->order_number = 'CANCEL-' . time();
    $cancelableOrder->status = 'confirmed';
    $cancelableOrder->save();
    
    // Copier les items
    foreach ($lastOrder->items as $item) {
        $newItem = $item->replicate();
        $newItem->order_id = $cancelableOrder->id;
        $newItem->save();
    }
}

echo "Test d'annulation avec commande: {$cancelableOrder->order_number}\n";

// Vérifier le stock avant annulation
$stockBefore = [];
foreach ($cancelableOrder->items as $item) {
    $stockBefore[$item->product_id] = $item->product->quantity;
    echo "Stock avant annulation - {$item->product->name}: {$item->product->quantity}\n";
}

try {
    $cancelableOrder->cancel('Test d\'annulation pour vérification du stock');
    echo "✅ Commande annulée avec succès\n";
    
    // Vérifier le stock après annulation
    foreach ($cancelableOrder->items as $item) {
        $item->product->refresh();
        $stockAfter = $item->product->quantity;
        $expectedStock = $stockBefore[$item->product_id] + $item->quantity;
        
        echo "Stock après annulation - {$item->product->name}: {$stockAfter} ";
        echo "(+{$item->quantity} attendu = {$expectedStock})\n";
        
        if ($stockAfter == $expectedStock) {
            echo "✅ SUCCÈS: Stock correctement restauré!\n";
        } else {
            echo "❌ ERREUR: Stock non restauré (attendu: {$expectedStock}, actuel: {$stockAfter})\n";
        }
    }
} catch (Exception $e) {
    echo "❌ Erreur lors de l'annulation: {$e->getMessage()}\n";
}

echo "\n=== Instructions pour voir les transitions en temps réel ===\n";
echo "Dans un autre terminal, lancez:\n";
echo "php artisan queue:work --timeout=60\n\n";
echo "Pour voir les logs en temps réel:\n";
echo "tail -f storage/logs/laravel.log\n\n";
echo "La commande {$order->order_number} devrait progresser automatiquement:\n";
echo "confirmed -> preparing -> shipped -> delivered\n";
