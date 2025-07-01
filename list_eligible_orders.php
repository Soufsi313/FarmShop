<?php
// Lister les commandes éligibles au retour
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== COMMANDES ÉLIGIBLES AU RETOUR ===\n\n";

$orders = \App\Models\Order::where('status', 'delivered')
    ->where('id', '!=', 8) // Exclure la commande déjà retournée
    ->with(['user', 'items.product'])
    ->limit(10)
    ->get();

if ($orders->isEmpty()) {
    echo "Aucune commande livrée trouvée (autre que la commande 8).\n";
    echo "Création d'une commande de test...\n\n";
    
    // Créer une commande de test simple
    $user = \App\Models\User::first();
    $product = \App\Models\Product::where('is_perishable', false)->first();
    
    if (!$user || !$product) {
        echo "Erreur: Pas d'utilisateur ou de produit non périssable trouvé.\n";
        exit;
    }
    
    $order = \App\Models\Order::create([
        'user_id' => $user->id,
        'order_number' => 'FS' . now()->format('Ymd') . str_pad(\App\Models\Order::count() + 1, 6, '0', STR_PAD_LEFT),
        'status' => 'delivered',
        'payment_status' => 'paid',
        'subtotal' => 25.00,
        'tax_amount' => 5.00,
        'shipping_cost' => 3.99,
        'total_amount' => 33.99,
        'payment_method' => 'card',
        'shipping_address' => json_encode([
            'name' => $user->name,
            'address' => '123 Rue Test',
            'city' => 'Test City',
            'postal_code' => '12345',
            'country' => 'Belgium'
        ]),
        'delivered_at' => now()->subDays(2), // Livrée il y a 2 jours
        'created_at' => now()->subDays(3),
        'updated_at' => now()->subDays(2),
    ]);
    
    // Ajouter un item
    \App\Models\OrderItem::create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'quantity' => 1,
        'unit_price' => 25.00,
        'total_price' => 25.00,
        'is_perishable' => false,
        'is_returnable' => true,
    ]);
    
    echo "Commande de test créée: {$order->order_number} (ID: {$order->id})\n";
    $orders = collect([$order]);
}

foreach ($orders as $order) {
    $returnDeadline = \Carbon\Carbon::parse($order->delivered_at)->addDays(14);
    $isEligible = now()->lte($returnDeadline);
    
    echo "Commande #{$order->order_number} (ID: {$order->id})\n";
    echo "  - Client: {$order->user->name}\n";
    echo "  - Montant: {$order->total_amount}€\n";
    echo "  - Livrée le: " . $order->delivered_at->format('d/m/Y H:i') . "\n";
    echo "  - Deadline retour: " . $returnDeadline->format('d/m/Y H:i') . "\n";
    echo "  - Éligible au retour: " . ($isEligible ? 'OUI' : 'NON') . "\n";
    
    if ($isEligible && $order->items) {
        $returnableItems = $order->items->filter(function($item) {
            return $item->product ? !$item->product->isPerishable() : !$item->is_perishable;
        });
        echo "  - Produits retournables: {$returnableItems->count()}/{$order->items->count()}\n";
        
        foreach ($returnableItems as $item) {
            $productName = $item->product ? $item->product->name : "Produit supprimé";
            echo "    * {$productName} - {$item->quantity} x {$item->unit_price}€\n";
        }
    }
    echo "  ---\n";
}

echo "\nPour tester l'interface web, utilisez une des commandes éligibles ci-dessus.\n";
echo "URL de test: http://127.0.0.1:8000/admin/orders/cancellation\n";
