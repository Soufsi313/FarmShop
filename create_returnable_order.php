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

echo "🛠️ Création de commandes avec vrais produits NON périssables...\n\n";

// Récupérer l'utilisateur test
$user = User::where('email', 's.mef2703@gmail.com')->first();
if (!$user) {
    echo "❌ Utilisateur test non trouvé\n";
    exit;
}

// Récupérer des produits NON périssables
$nonPerishableProducts = Product::where('is_perishable', false)
    ->orWhereNull('is_perishable')
    ->where('quantity', '>', 0)
    ->limit(3)
    ->get();

if ($nonPerishableProducts->count() < 2) {
    echo "❌ Pas assez de produits non périssables disponibles\n";
    echo "Produits trouvés: {$nonPerishableProducts->count()}\n";
    foreach ($nonPerishableProducts as $product) {
        echo "- {$product->name} (périssable: " . ($product->is_perishable ? 'OUI' : 'NON') . ")\n";
    }
    exit;
}

echo "✅ Produits non périssables trouvés:\n";
foreach ($nonPerishableProducts as $product) {
    echo "- {$product->name} (périssable: " . ($product->is_perishable ? 'OUI' : 'NON') . ", retournable: " . ($product->is_returnable ? 'OUI' : 'NON') . ")\n";
}
echo "\n";

// Créer une commande livrée avec produits NON périssables
$order = Order::create([
    'user_id' => $user->id,
    'order_number' => 'FS' . now()->format('Ymd') . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
    'status' => Order::STATUS_DELIVERED,
    'confirmed_at' => now()->subDays(5),
    'preparation_at' => now()->subDays(5)->addMinutes(90),
    'shipped_at' => now()->subDays(4),
    'delivered_at' => now()->subDays(3),
    'subtotal' => 45.00,
    'tax_amount' => 9.00,
    'shipping_cost' => 4.99,
    'total_amount' => 58.99,
    'shipping_method' => 'standard',
    'tracking_number' => 'TRK' . rand(100000, 999999),
    'payment_status' => Order::PAYMENT_PAID,
    'paid_at' => now()->subDays(5)->subMinutes(5),
    'return_deadline' => now()->addDays(11), // Encore 11 jours pour retourner
    'shipping_address' => json_encode([
        'name' => 'Soufiane MEFTAH',
        'address' => '123 Rue de Test',
        'city' => 'Ville Test',
        'postal_code' => '12345',
        'country' => 'France'
    ]),
    'billing_address' => json_encode([
        'name' => 'Soufiane MEFTAH',
        'address' => '123 Rue de Test',
        'city' => 'Ville Test',
        'postal_code' => '12345',
        'country' => 'France'
    ]),
    'payment_method' => 'card',
]);

// Ajouter des articles NON périssables
$totalCalculated = 0;
foreach ($nonPerishableProducts->take(2) as $index => $product) {
    $quantity = $index === 0 ? 2 : 1;
    $unitPrice = $product->price;
    $totalPrice = $quantity * $unitPrice;
    $totalCalculated += $totalPrice;
    
    OrderItem::create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'product_name' => $product->name,
        'product_description' => $product->description ?? '',
        'product_sku' => $product->sku ?? 'SKU' . $product->id,
        'quantity' => $quantity,
        'unit_price' => $unitPrice,
        'total_price' => $totalPrice,
        'is_perishable' => $product->is_perishable ?? false,
        'is_returnable' => $product->is_returnable ?? true,
        'status' => 'delivered',
    ]);
    
    echo "✅ Article ajouté: {$product->name} x{$quantity} = {$totalPrice}€\n";
}

// Mettre à jour le montant de la commande
$order->update([
    'subtotal' => $totalCalculated,
    'total_amount' => $totalCalculated + $order->tax_amount + $order->shipping_cost
]);

echo "\n🎉 Commande créée: #{$order->order_number}\n";
echo "💰 Total: {$order->total_amount}€\n";
echo "📦 {$order->items->count()} articles NON périssables\n";
echo "🔄 Retournable jusqu'au: {$order->return_deadline->format('d/m/Y')}\n";
echo "\n🚀 Testez maintenant le retour sur /admin/orders/cancellation\n";
