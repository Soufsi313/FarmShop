<?php
// Créer une nouvelle commande de test pour tester l'interface de retour
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CRÉATION D'UNE NOUVELLE COMMANDE DE TEST ===\n\n";

// Trouver un utilisateur
$user = \App\Models\User::first();
if (!$user) {
    echo "Aucun utilisateur trouvé.\n";
    exit;
}

// Trouver des produits non périssables
$products = \App\Models\Product::where('is_perishable', false)->where('quantity', '>', 0)->limit(2)->get();
if ($products->count() < 2) {
    echo "Pas assez de produits non périssables disponibles.\n";
    exit;
}

// Créer la commande
$order = \App\Models\Order::create([
    'user_id' => $user->id,
    'order_number' => 'FS' . now()->format('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
    'status' => \App\Models\Order::STATUS_DELIVERED,
    'total_amount' => 0,
    'subtotal' => 0,
    'delivery_address' => '123 Test Street, Test City',
    'delivery_phone' => '+32123456789',
    'payment_method' => 'card',
    'payment_status' => \App\Models\Order::PAYMENT_PAID,
    'delivered_at' => now()->subDays(3), // Livrée il y a 3 jours
    'created_at' => now()->subDays(5),
    'updated_at' => now()->subDays(3)
]);

$totalAmount = 0;

// Ajouter des articles
foreach ($products as $index => $product) {
    $quantity = $index === 0 ? 2 : 1;
    $unitPrice = round(rand(500, 2000) / 100, 2); // Prix entre 5€ et 20€
    $totalPrice = $quantity * $unitPrice;
    $totalAmount += $totalPrice;
    
    \App\Models\OrderItem::create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'quantity' => $quantity,
        'unit_price' => $unitPrice,
        'total_price' => $totalPrice,
        'is_perishable' => false,
        'is_returnable' => true
    ]);
    
    echo "Article ajouté: {$product->name} x{$quantity} à {$unitPrice}€ = {$totalPrice}€\n";
}

// Mettre à jour le montant total
$order->update([
    'total_amount' => $totalAmount,
    'subtotal' => $totalAmount
]);

echo "\n✅ COMMANDE CRÉÉE AVEC SUCCÈS !\n";
echo "Numéro: {$order->order_number}\n";
echo "ID: {$order->id}\n";
echo "Statut: {$order->status}\n";
echo "Montant total: {$totalAmount}€\n";
echo "Livrée le: " . $order->delivered_at->format('d/m/Y H:i') . "\n";
echo "Deadline retour: " . \Carbon\Carbon::parse($order->delivered_at)->addDays(14)->format('d/m/Y H:i') . "\n";

echo "\n🔗 Testez l'interface de retour avec cette commande !\n";
echo "URL: http://127.0.0.1:8000/admin/orders/{$order->id}/return\n";

echo "\nCommande créée !\n";
