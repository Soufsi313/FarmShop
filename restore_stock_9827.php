<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== RESTAURATION STOCK COMMANDE LOC-202508139827 ===\n\n";

// 1. Trouver la commande
$order = DB::table('order_locations')
    ->where('order_number', 'LOC-202508139827')
    ->first();

if ($order) {
    echo "📦 Commande trouvée: {$order->order_number}\n";
    echo "   Status: {$order->status}\n";
    echo "   Payment status: {$order->payment_status}\n";
    echo "   Frontend confirmed: " . ($order->frontend_confirmed ? 'Oui' : 'Non') . "\n\n";
    
    // 2. Trouver les produits de cette commande
    $items = DB::table('order_item_locations')
        ->where('order_location_id', $order->id)
        ->get();
    
    foreach ($items as $item) {
        $product = DB::table('products')
            ->where('id', $item->product_id)
            ->first();
        
        if ($product) {
            echo "📊 Produit: {$product->name}\n";
            echo "   Stock actuel: {$product->rental_stock}\n";
            echo "   Quantité à restaurer: {$item->quantity}\n";
            
            // Restaurer le stock
            $newStock = $product->rental_stock + $item->quantity;
            DB::table('products')
                ->where('id', $product->id)
                ->update(['rental_stock' => $newStock]);
            
            echo "   ✅ Stock restauré: {$product->rental_stock} → {$newStock}\n\n";
        }
    }
    
    // 3. Marquer que cette commande n'est PAS confirmée côté frontend
    DB::table('order_locations')
        ->where('id', $order->id)
        ->update([
            'frontend_confirmed' => false,
            'frontend_confirmed_at' => null
        ]);
    
    echo "✅ Stock restauré et commande marquée comme non-confirmée frontend\n";
    echo "✅ Maintenant teste une nouvelle commande pour voir si le problème est résolu !\n";
    
} else {
    echo "❌ Commande non trouvée\n";
}
