<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== MONITORING STOCK BÂCHE DE PROTECTION 6x4m ===\n";
echo "Produit ID: 102\n";
echo "Surveillez ce stock pendant que vous testez une nouvelle commande...\n\n";

while (true) {
    $product = DB::table('products')->where('id', 102)->first();
    $latestOrder = DB::table('order_locations')
        ->orderBy('created_at', 'desc')
        ->first();
    
    $timestamp = date('H:i:s');
    echo "[{$timestamp}] Stock actuel: {$product->rental_stock}";
    
    if ($latestOrder) {
        echo " | Dernière commande: {$latestOrder->order_number} ({$latestOrder->status})";
        if ($latestOrder->frontend_confirmed) {
            echo " [Frontend Confirmé]";
        } else {
            echo " [Frontend NON confirmé]";
        }
    }
    
    echo "\n";
    
    sleep(2); // Vérifier toutes les 2 secondes
}
