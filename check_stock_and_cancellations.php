<?php

require __DIR__ . '/vendor/autoload.php';

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;

// Initialiser Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DIAGNOSTIC STOCK ET ANNULATIONS ===\n\n";

// Vérifier le stock des pommes vertes
$product = Product::where('name', 'like', '%pommes%')->first();
if ($product) {
    echo "Produit: {$product->name}\n";
    echo "Stock actuel: {$product->quantity}\n";
    echo "Prix: {$product->price}€\n\n";
}

// Analyser les commandes annulées récentes
echo "=== COMMANDES ANNULÉES RÉCENTES ===\n";
$cancelledOrders = Order::where('status', 'cancelled')
    ->where('created_at', '>=', now()->subDay())
    ->with('items.product')
    ->get();

foreach ($cancelledOrders as $order) {
    echo "Commande: {$order->order_number}\n";
    echo "Statut: {$order->status} - Paiement: {$order->payment_status}\n";
    echo "Créée: {$order->created_at}\n";
    echo "Annulée: {$order->updated_at}\n";
    
    echo "Articles:\n";
    foreach ($order->items as $item) {
        echo "  - {$item->product_name}: {$item->quantity}x à {$item->unit_price}€\n";
        if ($item->special_offer_id) {
            echo "    🔥 Offre spéciale appliquée: {$item->discount_percentage}%\n";
            echo "    Prix original: {$item->original_unit_price}€\n";
        }
    }
    echo "Total: {$order->total_amount}€\n";
    echo "---\n";
}

// Vérifier les mouvements de stock récents dans les logs
echo "\n=== RECHERCHE DANS LES LOGS ===\n";
$logFile = storage_path('logs/laravel.log');
if (file_exists($logFile)) {
    $logs = file_get_contents($logFile);
    $stockLogs = [];
    
    // Rechercher les logs de stock
    preg_match_all('/.*Stock (décrémenté|restauré|incrementé).*/', $logs, $matches);
    
    if (!empty($matches[0])) {
        $recentLogs = array_slice($matches[0], -10); // 10 derniers logs
        foreach ($recentLogs as $log) {
            echo $log . "\n";
        }
    } else {
        echo "Aucun log de stock trouvé récemment\n";
    }
} else {
    echo "Fichier de log non trouvé\n";
}
