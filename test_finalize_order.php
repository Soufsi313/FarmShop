<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Test de finalisation de commande ===\n\n";

use App\Models\User;
use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

// 1. Simuler une connexion utilisateur
$user = User::find(31); // L'utilisateur qui a le panier actif
Auth::login($user);

echo "👤 Connecté en tant que: {$user->name}\n";

// 2. Vérifier le panier avant
$cartItemsBefore = $user->cartItems()->with('product')->get();
echo "🛒 Articles dans le panier AVANT: {$cartItemsBefore->count()}\n";
foreach ($cartItemsBefore as $item) {
    echo "  - {$item->product->name} x{$item->quantity} = {$item->total_price}€\n";
}

// 3. Simuler les données de session (comme si on venait de la page de paiement)
$orderData = [
    'subtotal' => $cartItemsBefore->sum('total_price'),
    'tax_amount' => 0,
    'shipping_cost' => 0,
    'total_amount' => $cartItemsBefore->sum('total_price'),
    'shipping_address' => [
        'name' => $user->name,
        'address' => '123 Test Street',
        'city' => 'Test City',
        'postal_code' => '12345',
        'country' => 'France'
    ],
    'notes' => 'Test de finalisation'
];

Session::put('order_data', $orderData);
echo "💾 Données de session stockées\n";

// 4. Créer un faux PaymentIntent (simuler Stripe)
$fakePaymentIntent = (object) [
    'id' => 'pi_test_' . uniqid(),
    'status' => 'succeeded',
    'metadata' => (object) [
        'user_id' => $user->id,
        'order_type' => 'purchase'
    ]
];

echo "💳 PaymentIntent simulé: {$fakePaymentIntent->id}\n";

// 5. Simuler l'appel à finalizePurchase
echo "\n🔄 Début de la finalisation...\n";

try {
    // Reproduire la logique de finalizePurchase
    $cartItems = $user->cartItems()->with(['product'])->get();
    
    if ($cartItems->isEmpty()) {
        echo "❌ Erreur: Panier vide\n";
        exit;
    }
    
    echo "✅ Panier trouvé avec {$cartItems->count()} article(s)\n";
    
    // Vérifier le stock
    foreach ($cartItems as $cartItem) {
        $product = $cartItem->product;
        echo "📦 Produit: {$product->name} | Stock: {$product->quantity} | Demandé: {$cartItem->quantity}\n";
        
        if ($product->type !== 'rental' && $product->quantity < $cartItem->quantity) {
            echo "❌ Stock insuffisant pour {$product->name}\n";
            exit;
        }
    }
    
    // Créer la commande (sans transaction pour ce test)
    $order = Order::create([
        'user_id' => $user->id,
        'order_number' => Order::generateOrderNumber(),
        'status' => Order::STATUS_CONFIRMED,
        'subtotal' => $orderData['subtotal'],
        'tax_amount' => $orderData['tax_amount'],
        'shipping_cost' => $orderData['shipping_cost'],
        'total_amount' => $orderData['total_amount'],
        'shipping_address' => json_encode($orderData['shipping_address']),
        'billing_address' => json_encode($orderData['shipping_address']),
        'payment_method' => 'stripe',
        'payment_status' => Order::PAYMENT_PAID,
        'notes' => $orderData['notes'],
        'stripe_payment_intent_id' => $fakePaymentIntent->id,
        'paid_at' => now(),
    ]);
    
    echo "✅ Commande créée: {$order->order_number}\n";
    
    // Créer les articles de commande
    foreach ($cartItems as $cartItem) {
        $product = $cartItem->product;
        
        \App\Models\OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_description' => $product->description,
            'unit_price' => $cartItem->unit_price,
            'quantity' => $cartItem->quantity,
            'total_price' => $cartItem->total_price,
            'status' => \App\Models\OrderItem::STATUS_PENDING,
        ]);
        
        echo "✅ Article ajouté: {$product->name}\n";
        
        // Décrémenter le stock
        if ($product->type !== 'rental') {
            $oldQuantity = $product->quantity;
            $product->decrement('quantity', $cartItem->quantity);
            $product->refresh();
            echo "📦 Stock mis à jour: {$oldQuantity} -> {$product->quantity}\n";
        }
    }
    
    // Vider le panier
    $deletedCount = $user->cartItems()->delete();
    echo "🗑️ Panier vidé: {$deletedCount} article(s) supprimé(s)\n";
    
    // Nettoyer la session
    Session::forget('order_data');
    echo "🧹 Session nettoyée\n";
    
    echo "\n✅ Finalisation réussie ! Commande: {$order->order_number}\n";
    
} catch (\Exception $e) {
    echo "❌ Erreur lors de la finalisation: {$e->getMessage()}\n";
    echo "📍 Trace: {$e->getFile()}:{$e->getLine()}\n";
}

// 6. Vérifier le panier après
$cartItemsAfter = $user->cartItems()->with('product')->get();
echo "\n🛒 Articles dans le panier APRÈS: {$cartItemsAfter->count()}\n";

echo "\n=== Fin du test ===\n";
