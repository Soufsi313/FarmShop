<?php
// Vérifier les dernières commandes
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DERNIÈRES COMMANDES ===\n\n";

$orders = \App\Models\Order::with(['user', 'items.product'])->latest()->limit(5)->get();

foreach ($orders as $order) {
    echo "Commande #{$order->order_number} (ID: {$order->id})\n";
    echo "  - Statut: {$order->status}\n";
    echo "  - Client: {$order->user->name}\n";
    echo "  - Créée le: " . $order->created_at->format('d/m/Y H:i:s') . "\n";
    echo "  - Mise à jour le: " . $order->updated_at->format('d/m/Y H:i:s') . "\n";
    
    if ($order->status === 'pending') {
        $timeSinceCreation = now()->diffInMinutes($order->created_at);
        echo "  - Temps écoulé: {$timeSinceCreation} minute(s)\n";
        echo "  - Devrait passer à 'confirmed' dans: " . max(0, 1 - $timeSinceCreation) . " minute(s)\n";
    } elseif ($order->status === 'confirmed') {
        $timeSinceConfirmed = now()->diffInMinutes($order->updated_at);
        echo "  - Temps en statut confirmé: {$timeSinceConfirmed} minute(s)\n";
        echo "  - Devrait passer à 'preparation' dans: " . max(0, 1 - $timeSinceConfirmed) . " minute(s)\n";
    }
    
    if ($order->items->count() > 0) {
        echo "  - Produits:\n";
        foreach ($order->items as $item) {
            $productName = $item->product ? $item->product->name : $item->product_name ?? 'Produit inconnu';
            echo "    * {$productName} x{$item->quantity}\n";
        }
    }
    
    echo "  ---\n";
}

echo "\nLe scheduler est-il actif? " . (file_exists(storage_path('logs/order-status-automation.log')) ? "OUI" : "NON") . "\n";

if (file_exists(storage_path('logs/order-status-automation.log'))) {
    echo "\nDernières lignes du log d'automatisation:\n";
    $logContent = file_get_contents(storage_path('logs/order-status-automation.log'));
    $lines = explode("\n", trim($logContent));
    $lastLines = array_slice($lines, -5);
    foreach ($lastLines as $line) {
        if (!empty($line)) {
            echo "  " . $line . "\n";
        }
    }
}
