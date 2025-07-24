<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Test du processus de checkout...\n\n";

// Créer un utilisateur test s'il n'existe pas
$user = App\Models\User::firstOrCreate(
    ['email' => 'test@farmshop.com'],
    [
        'name' => 'Test User',
        'username' => 'testuser',
        'password' => bcrypt('password'),
        'email_verified_at' => now()
    ]
);

echo "Utilisateur test: " . $user->email . " (ID: " . $user->id . ")\n";

// Vérifier s'il y a un panier existant
$cart = App\Models\Cart::where('user_id', $user->id)->first();

if (!$cart) {
    echo "Aucun panier trouvé pour cet utilisateur.\n";
    echo "Créons un panier avec des produits pour tester...\n";
    
    // Créer un panier
    $cart = App\Models\Cart::create([
        'user_id' => $user->id,
        'status' => 'active'
    ]);
    
    // Ajouter des produits au panier
    $products = App\Models\Product::where('is_active', true)->limit(2)->get();
    
    foreach ($products as $product) {
        $cart->items()->create([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_category' => $product->category->name ?? 'Non définie',
            'quantity' => 1,
            'unit_price' => $product->price,
            'tax_rate' => ($product->category->food_type ?? 'non_alimentaire') === 'alimentaire' ? 6 : 21
        ]);
    }
    
    // Recalculer les totaux
    $cart->calculateTotals();
    $cart->save();
    
    echo "Panier créé avec " . $cart->items->count() . " produits.\n";
} else {
    echo "Panier existant trouvé avec " . $cart->items->count() . " produits.\n";
}

echo "Détails du panier:\n";
echo "- Sous-total HT: " . number_format($cart->subtotal, 2) . "€\n";
echo "- TVA: " . number_format($cart->tax_amount, 2) . "€\n";
echo "- Total TTC: " . number_format($cart->total, 2) . "€\n\n";

// Tester la création d'une commande
echo "Test de création de commande...\n";

$billingAddress = [
    'firstName' => 'John',
    'lastName' => 'Doe',
    'email' => 'test@farmshop.com',
    'phone' => '+32123456789',
    'company' => '',
    'address' => '123 Rue Test',
    'addressComplement' => '',
    'city' => 'Bruxelles',
    'postalCode' => '1000',
    'country' => 'BE'
];

$shippingAddress = $billingAddress;

try {
    $order = App\Models\Order::createFromCart($cart, $billingAddress, $shippingAddress, 'stripe');
    
    echo "✅ Commande créée avec succès!\n";
    echo "- Numéro: " . $order->order_number . "\n";
    echo "- Total: " . number_format($order->total_amount, 2) . "€\n";
    echo "- Items: " . $order->items->count() . "\n";
    
} catch (Exception $e) {
    echo "❌ Erreur lors de la création de la commande:\n";
    echo $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
