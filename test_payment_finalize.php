<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\User;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Order;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Test de finalisation de paiement ===\n\n";

// Récupérer un utilisateur test
$user = User::first();
if (!$user) {
    echo "❌ Aucun utilisateur trouvé\n";
    exit;
}

echo "👤 Utilisateur: {$user->name} (ID: {$user->id})\n";

// Vérifier le panier de l'utilisateur
$cartItems = CartItem::where('user_id', $user->id)->with('product')->get();
echo "🛒 Articles dans le panier: {$cartItems->count()}\n";

if ($cartItems->isEmpty()) {
    echo "   ➕ Ajout d'un article de test au panier...\n";
    
    // Récupérer un produit test
    $product = Product::where('quantity', '>', 0)->first();
    if (!$product) {
        echo "❌ Aucun produit disponible pour le test\n";
        exit;
    }
    
    // Ajouter au panier
    CartItem::create([
        'user_id' => $user->id,
        'product_id' => $product->id,
        'quantity' => 1,
        'unit_price' => $product->price,
    ]);
    
    $cartItems = CartItem::where('user_id', $user->id)->with('product')->get();
    echo "   ✅ Article ajouté: {$product->name}\n";
}

// Vérifier les données de session
$sessionData = [
    'subtotal' => 0,
    'tax_amount' => 0,
    'shipping_cost' => 2.50,
    'total_amount' => 0,
    'shipping_address' => $user->address ?? [],
    'notes' => null,
    'order_type' => 'purchase',
];

$subtotal = 0;
foreach ($cartItems as $item) {
    echo "   📦 {$item->product->name} x{$item->quantity} = {$item->total_price}€\n";
    $subtotal += $item->total_price;
}

$sessionData['subtotal'] = $subtotal;
$sessionData['total_amount'] = $subtotal + $sessionData['shipping_cost'];

echo "\n💰 Calculs:\n";
echo "   Sous-total: {$subtotal}€\n";
echo "   Frais de livraison: {$sessionData['shipping_cost']}€\n";
echo "   Total: {$sessionData['total_amount']}€\n";

// Simuler les données de session
session()->put('order_data', $sessionData);

echo "\n💾 Données de session stockées\n";

// Tester la logique de finalisation
echo "\n🧪 Test de la logique de finalisation...\n";

try {
    // Vérifier le stock avant
    $stockAvant = [];
    foreach ($cartItems as $item) {
        $stockAvant[$item->product->id] = $item->product->quantity;
        echo "   📦 Stock avant - {$item->product->name}: {$item->product->quantity}\n";
    }
    
    // Simuler la création de commande manuelle (logique de finalizePurchase)
    DB::beginTransaction();
    
    $order = Order::create([
        'user_id' => $user->id,
        'order_number' => Order::generateOrderNumber(),
        'status' => Order::STATUS_CONFIRMED,
        'subtotal' => $sessionData['subtotal'],
        'tax_amount' => $sessionData['tax_amount'],
        'shipping_cost' => $sessionData['shipping_cost'],
        'total_amount' => $sessionData['total_amount'],
        'shipping_address' => json_encode($sessionData['shipping_address']),
        'billing_address' => json_encode($sessionData['shipping_address']),
        'payment_method' => 'stripe',
        'payment_status' => Order::PAYMENT_PAID,
        'notes' => $sessionData['notes'],
        'stripe_payment_intent_id' => 'test_payment_intent',
        'paid_at' => now(),
    ]);
    
    echo "   ✅ Commande créée: {$order->order_number}\n";
    
    // Créer les articles de commande et décrémenter le stock
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
        
        echo "   ✅ Article de commande créé: {$product->name}\n";
        
        // Décrémenter le stock
        if ($product->type !== 'rental') {
            $product->decrement('quantity', $cartItem->quantity);
            echo "   📉 Stock décrémenté: {$product->name} ({$cartItem->quantity})\n";
        }
    }
    
    // Vider le panier
    $cartItemsCount = $user->cartItems()->count();
    $user->cartItems()->delete();
    echo "   🗑️ Panier vidé ({$cartItemsCount} articles supprimés)\n";
    
    // Nettoyer la session
    session()->forget('order_data');
    echo "   💾 Session nettoyée\n";
    
    DB::commit();
    
    echo "\n✅ Finalisation réussie !\n";
    
    // Vérifier les résultats
    echo "\n📊 Vérifications post-finalisation:\n";
    
    // Vérifier que la commande existe
    $orderCheck = Order::find($order->id);
    echo "   📋 Commande existe: " . ($orderCheck ? "✅" : "❌") . "\n";
    
    // Vérifier que le panier est vide
    $cartCheck = CartItem::where('user_id', $user->id)->count();
    echo "   🛒 Panier vide: " . ($cartCheck == 0 ? "✅" : "❌ ({$cartCheck} articles restants)") . "\n";
    
    // Vérifier le stock
    foreach ($cartItems as $item) {
        $product = Product::find($item->product->id);
        $stockActuel = $product->quantity;
        $stockAttendu = $stockAvant[$item->product->id] - $item->quantity;
        echo "   📦 Stock {$product->name}: {$stockActuel} (attendu: {$stockAttendu}) " . 
             ($stockActuel == $stockAttendu ? "✅" : "❌") . "\n";
    }
    
} catch (\Exception $e) {
    DB::rollBack();
    echo "❌ Erreur lors de la finalisation: " . $e->getMessage() . "\n";
    echo "   Fichier: " . $e->getFile() . "\n";
    echo "   Ligne: " . $e->getLine() . "\n";
}

echo "\n=== Fin du test ===\n";
