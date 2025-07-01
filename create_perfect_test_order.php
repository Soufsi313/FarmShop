<?php

require_once 'vendor/autoload.php';

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;

echo "🏗️ Création d'une commande complète pour test de retour...\n\n";

// Utiliser un utilisateur existant
$user = User::first();
if (!$user) {
    echo "❌ Aucun utilisateur trouvé\n";
    exit;
}

// Récupérer des produits avec des prix > 0 et vérifier qu'ils ne sont pas périssables
$products = Product::where('price', '>', 0)
    ->where(function($query) {
        $query->where('is_perishable', false)
              ->orWhereNull('is_perishable');
    })
    ->take(3)
    ->get();

if ($products->count() < 2) {
    echo "❌ Pas assez de produits non périssables avec prix > 0\n";
    exit;
}

echo "📦 Produits sélectionnés:\n";
foreach ($products as $product) {
    echo "  - {$product->name}: {$product->price}€ (Périssable: " . ($product->isPerishable() ? 'Oui' : 'Non') . ")\n";
}
echo "\n";

// Calculer les montants
$subtotal = 0;
$orderItems = [];

// Article 1 : 2 unités du premier produit
$qty1 = 2;
$price1 = $products[0]->price;
$total1 = $qty1 * $price1;
$subtotal += $total1;

$orderItems[] = [
    'product' => $products[0],
    'quantity' => $qty1,
    'unit_price' => $price1,
    'total_price' => $total1
];

// Article 2 : 1 unité du deuxième produit
$qty2 = 1;
$price2 = $products[1]->price;
$total2 = $qty2 * $price2;
$subtotal += $total2;

$orderItems[] = [
    'product' => $products[1],
    'quantity' => $qty2,
    'unit_price' => $price2,
    'total_price' => $total2
];

$taxAmount = round($subtotal * 0.20, 2); // 20% TVA
$shippingCost = 4.99;
$totalAmount = $subtotal + $taxAmount + $shippingCost;

// Créer la commande
$order = Order::create([
    'user_id' => $user->id,
    'order_number' => 'FS' . now()->format('Ymd') . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
    'status' => Order::STATUS_DELIVERED,
    'confirmed_at' => now()->subDays(5),
    'preparation_at' => now()->subDays(5)->addMinutes(90),
    'shipped_at' => now()->subDays(4),
    'delivered_at' => now()->subDays(3),
    'subtotal' => $subtotal,
    'tax_amount' => $taxAmount,
    'shipping_cost' => $shippingCost,
    'total_amount' => $totalAmount,
    'shipping_method' => 'standard',
    'shipping_address' => json_encode([
        'name' => $user->name,
        'address' => '123 Rue de Test',
        'city' => 'Ville Test',
        'postal_code' => '12345',
        'country' => 'France'
    ]),
    'billing_address' => json_encode([
        'name' => $user->name,
        'address' => '123 Rue de Test',
        'city' => 'Ville Test',
        'postal_code' => '12345',
        'country' => 'France'
    ]),
    'payment_method' => 'card',
    'payment_status' => Order::PAYMENT_PAID,
    'paid_at' => now()->subDays(5)->subMinutes(5),
    'return_deadline' => now()->addDays(11), // Encore retournable
]);

// Créer les OrderItems
foreach ($orderItems as $itemData) {
    OrderItem::create([
        'order_id' => $order->id,
        'product_id' => $itemData['product']->id,
        'product_name' => $itemData['product']->name,
        'product_description' => $itemData['product']->description ?? '',
        'product_sku' => $itemData['product']->sku ?? '',
        'quantity' => $itemData['quantity'],
        'unit_price' => $itemData['unit_price'],
        'total_price' => $itemData['total_price'],
        'is_perishable' => $itemData['product']->isPerishable(),
        'is_returnable' => !$itemData['product']->isPerishable(),
    ]);
}

echo "✅ Commande créée: #{$order->order_number}\n";
echo "💰 Montant total: {$order->total_amount}€\n";
echo "📅 Livrée le: {$order->delivered_at}\n";
echo "🔄 Retournable jusqu'au: {$order->return_deadline}\n";
echo "📦 {$order->items->count()} article(s) ajoutés\n\n";

echo "📋 Détail des articles:\n";
foreach ($order->items as $item) {
    echo "  - {$item->product_name}: {$item->quantity} × {$item->unit_price}€ = {$item->total_price}€\n";
    echo "    Périssable: " . ($item->is_perishable ? 'Oui' : 'Non') . " | Retournable: " . ($item->is_returnable ? 'Oui' : 'Non') . "\n";
}

echo "\n🎯 Commande complète créée ! Testez maintenant le retour.\n";
