<?php

require __DIR__ . '/vendor/autoload.php';

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

// Initialiser Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== RESTAURATION MANUELLE DU STOCK ===\n\n";

// Identifier les commandes annulées qui avaient été payées et dont le stock doit être restauré
$ordersToFix = Order::where('status', 'cancelled')
    ->where('created_at', '>=', now()->subDay())
    ->whereIn('payment_status', ['paid', 'refunded']) // Stock décrémenté mais commande annulée
    ->with('items.product')
    ->get();

echo "Commandes à corriger: " . $ordersToFix->count() . "\n\n";

foreach ($ordersToFix as $order) {
    echo "Traitement commande: {$order->order_number}\n";
    echo "Statut: {$order->status} - Paiement: {$order->payment_status}\n";
    
    $stockRestored = false;
    
    foreach ($order->items as $item) {
        if ($item->product) {
            $oldQuantity = $item->product->quantity;
            $item->product->increment('quantity', $item->quantity);
            $newQuantity = $item->product->fresh()->quantity;
            
            echo "  - {$item->product->name}: stock {$oldQuantity} → {$newQuantity} (+{$item->quantity})\n";
            
            if ($item->special_offer_id) {
                echo "    🔥 Avait une offre spéciale: {$item->discount_percentage}%\n";
            }
            
            $stockRestored = true;
            
            Log::info('Stock restauré manuellement après annulation', [
                'product_id' => $item->product->id,
                'product_name' => $item->product->name,
                'previous_quantity' => $oldQuantity,
                'new_quantity' => $newQuantity,
                'quantity_restored' => $item->quantity,
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'special_offer_applied' => $item->special_offer_id ? true : false
            ]);
        }
    }
    
    if ($stockRestored) {
        echo "  ✅ Stock restauré avec succès\n";
    } else {
        echo "  ❌ Aucun stock à restaurer\n";
    }
    echo "---\n";
}

if ($ordersToFix->isEmpty()) {
    echo "Aucune commande nécessitant une correction de stock.\n";
}
