<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Ajout de produits au panier de test...\n\n";

$user = App\Models\User::where('email', 'test@farmshop.com')->first();
if (!$user) {
    echo "Utilisateur test non trouvé.\n";
    exit;
}

$cart = App\Models\Cart::where('user_id', $user->id)->first();
if (!$cart) {
    $cart = App\Models\Cart::create([
        'user_id' => $user->id,
        'status' => 'active'
    ]);
}

// Vider le panier existant
$cart->items()->delete();

// Ajouter des produits
$products = App\Models\Product::where('is_active', true)->where('quantity', '>', 0)->limit(3)->get();

foreach ($products as $product) {
    $taxRate = ($product->category->food_type ?? 'non_alimentaire') === 'alimentaire' ? 6 : 21;
    $quantity = 2;
    $unitPrice = $product->price;
    $subtotal = $unitPrice * $quantity;
    $taxAmount = $subtotal * ($taxRate / 100);
    $total = $subtotal + $taxAmount;
    
    $cartItem = $cart->items()->create([
        'product_id' => $product->id,
        'product_name' => $product->name,
        'product_category' => $product->category->name ?? 'Non définie',
        'quantity' => $quantity,
        'unit_price' => $unitPrice,
        'subtotal' => $subtotal,
        'tax_rate' => $taxRate,
        'tax_amount' => $taxAmount,
        'total' => $total
    ]);
    
    echo "Produit ajouté: " . $product->name . " (Prix: " . $unitPrice . "€, TVA: " . $taxRate . "%, Total: " . number_format($total, 2) . "€)\n";
}

// Recalculer les totaux manuellement
$totalSubtotal = $cart->items->sum('subtotal');
$totalTaxAmount = $cart->items->sum('tax_amount');
$totalAmount = $cart->items->sum('total');

$cart->update([
    'subtotal' => $totalSubtotal,
    'tax_amount' => $totalTaxAmount,
    'total' => $totalAmount
]);

echo "\nPanier mis à jour:\n";
echo "- Nombre d'articles: " . $cart->items->count() . "\n";
echo "- Sous-total HT: " . number_format($totalSubtotal, 2) . "€\n";
echo "- TVA: " . number_format($totalTaxAmount, 2) . "€\n";
echo "- Total TTC: " . number_format($totalAmount, 2) . "€\n";
