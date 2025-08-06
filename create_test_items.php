<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔧 CRÉATION DES ITEMS POUR LES LOCATIONS DE TEST\n";
echo "=================================================\n\n";

// Récupérer les 3 locations de test créées
$orders = DB::table('order_locations')
    ->where('order_number', 'like', 'LOC-TEST-%-20250805')
    ->orderBy('id')
    ->get();

if ($orders->count() != 3) {
    echo "❌ Pas exactement 3 locations trouvées\n";
    exit(1);
}

// Récupérer les produits
$products = [
    122 => 'Bêche agricole professionnelle',
    123 => 'Houe maraîchère 3 dents', 
    124 => 'Fourche à fumier longue'
];

foreach ($orders as $i => $order) {
    $productId = 122 + $i; // IDs 122, 123, 124
    
    echo ($i + 1) . ". Création item pour {$order->order_number}\n";
    echo "   Produit: {$products[$productId]} (ID: {$productId})\n";
    
    try {
        // Récupérer les informations du produit
        $product = DB::table('products')->where('id', $productId)->first();
        
        DB::table('order_item_locations')->insert([
            'order_location_id' => $order->id,
            'product_id' => $productId,
            'product_name' => $product->name,
            'product_sku' => $product->sku ?? 'SKU-' . $productId,
            'product_description' => $product->description ?? 'Description du produit',
            'quantity' => 1,
            'daily_rate' => $order->daily_rate,
            'rental_days' => $order->rental_days,
            'deposit_per_item' => $order->deposit_amount,
            'subtotal' => $order->subtotal,
            'total_deposit' => $order->deposit_amount,
            'tax_amount' => $order->tax_amount,
            'total_amount' => $order->total_amount,
            'condition_at_pickup' => 'excellent',
            'condition_at_return' => null,
            'item_damage_cost' => $order->damage_cost,
            'item_inspection_notes' => null,
            'damage_details' => null,
            'item_late_days' => $order->late_days,
            'item_late_fees' => $order->late_fees,
            'item_deposit_refund' => $order->deposit_refund,
            'created_at' => $order->created_at,
            'updated_at' => $order->updated_at,
        ]);
        
        echo "   ✅ Item créé avec succès\n\n";
        
    } catch (Exception $e) {
        echo "   ❌ Erreur: " . $e->getMessage() . "\n\n";
    }
}

echo "✅ Tous les items ont été créés !\n";
