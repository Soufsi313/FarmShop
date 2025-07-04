<?php
// Créer une commande parfaite pour tester les retours
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CRÉATION D'UNE COMMANDE PARFAITE POUR RETOUR ===\n\n";

// Récupérer un utilisateur et des produits non périssables avec prix > 0
$user = \App\Models\User::first();
$products = \App\Models\Product::where('is_perishable', false)
    ->where('quantity', '>', 0)
    ->where('price', '>', 0)
    ->limit(2)
    ->get();

if (!$user) {
    echo "Erreur: Aucun utilisateur trouvé.\n";
    exit;
}

if ($products->count() < 1) {
    echo "Pas assez de produits non périssables avec prix > 0. Mise à jour des prix...\n";
    
    // Mettre à jour quelques produits pour avoir des prix
    $productsToUpdate = \App\Models\Product::where('is_perishable', false)->limit(3)->get();
    foreach ($productsToUpdate as $i => $product) {
        $product->update(['price' => 15.99 + ($i * 5)]);
        echo "  - {$product->name}: prix mis à jour à {$product->price}€\n";
    }
    
    $products = \App\Models\Product::where('is_perishable', false)
        ->where('quantity', '>', 0)
        ->where('price', '>', 0)
        ->limit(2)
        ->get();
}

echo "Utilisateur: {$user->name} ({$user->email})\n";
echo "Produits sélectionnés:\n";
foreach ($products as $product) {
    echo "  - {$product->name} (Stock: {$product->quantity}, Prix: {$product->price}€)\n";
}

// Créer la commande
$orderNumber = 'FS' . now()->format('Ymd') . str_pad(\App\Models\Order::count() + 1, 6, '0', STR_PAD_LEFT);

// Calculer les montants (s'assurer qu'on a au moins 1 produit)
if ($products->count() < 1) {
    echo "Erreur: Aucun produit non périssable avec prix trouvé.\n";
    exit;
}

$subtotal = 0;
$quantities = [2, 1]; // Quantités pour les produits

foreach ($products as $i => $product) {
    $qty = $quantities[$i] ?? 1;
    $subtotal += $product->price * $qty;
}
$taxAmount = round($subtotal * 0.21, 2); // 21% TVA
$shippingCost = 4.99;
$totalAmount = $subtotal + $taxAmount + $shippingCost;

$order = \App\Models\Order::create([
    'user_id' => $user->id,
    'order_number' => $orderNumber,
    'status' => 'delivered',
    'payment_status' => 'paid',
    'subtotal' => $subtotal,
    'tax_amount' => $taxAmount,
    'shipping_cost' => $shippingCost,
    'total_amount' => $totalAmount,
    'payment_method' => 'card',
    'billing_address' => json_encode([
        'name' => $user->name,
        'address' => '123 Rue de Test',
        'city' => 'Bruxelles',
        'postal_code' => '1000',
        'country' => 'Belgique'
    ]),
    'shipping_address' => json_encode([
        'name' => $user->name,
        'address' => '123 Rue de Test',
        'city' => 'Bruxelles',
        'postal_code' => '1000',
        'country' => 'Belgique'
    ]),
    'delivered_at' => now()->subDays(1), // Livrée hier
    'created_at' => now()->subDays(2),
    'updated_at' => now()->subDays(1),
]);

// Ajouter les items
foreach ($products as $i => $product) {
    $qty = $quantities[$i] ?? 1;
    \App\Models\OrderItem::create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'product_name' => $product->name,
        'quantity' => $qty,
        'unit_price' => $product->price,
        'total_price' => $product->price * $qty,
        'is_perishable' => false,
        'is_returnable' => true,
    ]);
}

echo "\n=== COMMANDE CRÉÉE AVEC SUCCÈS ===\n";
echo "Numéro de commande: {$order->order_number} (ID: {$order->id})\n";
echo "Statut: {$order->status}\n";
echo "Montant total: {$order->total_amount}€\n";
echo "Livrée le: " . $order->delivered_at->format('d/m/Y H:i') . "\n";

$returnDeadline = \Carbon\Carbon::parse($order->delivered_at)->addDays(14);
echo "Deadline de retour: " . $returnDeadline->format('d/m/Y H:i') . "\n";
echo "Jours restants pour retour: " . now()->diffInDays($returnDeadline, false) . "\n";

echo "\nProduits de la commande:\n";
foreach ($order->items as $item) {
    echo "  - {$item->product->name}\n";
    echo "    Quantité: {$item->quantity}\n";
    echo "    Prix unitaire: {$item->unit_price}€\n";
    echo "    Total: {$item->total_price}€\n";
    echo "    Périssable: NON\n";
    echo "    Retournable: OUI\n";
    echo "  ---\n";
}

echo "\n✅ Cette commande est parfaite pour tester les retours !\n";
echo "URL de test: http://127.0.0.1:8000/admin/orders/cancellation\n";
echo "Cherchez la commande: {$order->order_number}\n";
