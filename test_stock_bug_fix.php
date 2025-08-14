<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;

echo "🧪 TEST DU CORRECTIF DU BUG DE STOCK\n";
echo "====================================\n\n";

// Trouver un produit de test
$product = Product::where('type', 'sale')
    ->where('is_active', true)
    ->where('quantity', '>', 50)
    ->first();

if (!$product) {
    echo "❌ Aucun produit d'achat trouvé pour le test\n";
    exit;
}

$initialStock = $product->quantity;
echo "📦 Produit de test: {$product->name}\n";
echo "📊 Stock initial: {$initialStock}\n\n";

// Utiliser un utilisateur existant
$user = User::first();
if (!$user) {
    echo "❌ Aucun utilisateur trouvé pour le test\n";
    exit;
}

echo "👤 Utilisateur de test: {$user->name} ({$user->email})\n";

try {
    DB::beginTransaction();
    
    // Créer un panier et une commande de test
    $cart = $user->getOrCreateActiveCart();
    $cart->items()->delete(); // Vider le panier
    
    $cart->addProduct($product, 10);
    
    echo "🛒 Panier créé avec 10 unités\n";
    echo "📊 Stock après ajout au panier: {$product->fresh()->quantity} (doit rester {$initialStock})\n\n";
    
    // Créer la commande (sans paiement)
    $order = Order::createFromCart(
        $cart,
        [
            'name' => 'Test User',
            'address' => '123 Test Street',
            'city' => 'Test City',
            'postal_code' => '12345',
            'country' => 'France'
        ],
        [
            'name' => 'Test User',
            'address' => '123 Test Street', 
            'city' => 'Test City',
            'postal_code' => '12345',
            'country' => 'France'
        ],
        'card'
    );
    
    echo "📝 Commande créée: {$order->order_number}\n";
    echo "💳 Statut paiement: {$order->payment_status}\n";
    echo "📊 Stock après création commande: {$product->fresh()->quantity} (doit rester {$initialStock})\n\n";
    
    // Test 1: Annuler la commande non payée
    echo "🧪 TEST 1: Annulation commande NON PAYÉE\n";
    echo "----------------------------------------\n";
    $order->cancel('Test annulation commande non payée');
    $stockAfterCancel = $product->fresh()->quantity;
    echo "📊 Stock après annulation: {$stockAfterCancel}\n";
    
    if ($stockAfterCancel == $initialStock) {
        echo "✅ CORRECT: Stock inchangé pour commande non payée\n\n";
    } else {
        echo "❌ ERREUR: Stock modifié pour commande non payée!\n\n";
    }
    
    // Test 2: Simuler une commande payée puis annulée
    echo "🧪 TEST 2: Annulation commande PAYÉE\n";
    echo "------------------------------------\n";
    
    // Créer une nouvelle commande
    $cart2 = $user->getOrCreateActiveCart();
    $cart2->items()->delete();
    $cart2->addProduct($product, 5);
    
    $order2 = Order::createFromCart(
        $cart2,
        [
            'name' => 'Test User',
            'address' => '123 Test Street',
            'city' => 'Test City',
            'postal_code' => '12345',
            'country' => 'France'
        ],
        [
            'name' => 'Test User',
            'address' => '123 Test Street',
            'city' => 'Test City',
            'postal_code' => '12345',
            'country' => 'France'
        ],
        'card'
    );
    
    // Simuler le paiement confirmé (décrémenter le stock manuellement)
    $product->decrement('quantity', 5);
    $order2->update([
        'payment_status' => 'paid',
        'status' => 'confirmed'
    ]);
    
    $stockAfterPayment = $product->fresh()->quantity;
    echo "📊 Stock après paiement simulé: {$stockAfterPayment} (doit être {$initialStock} - 5 = " . ($initialStock - 5) . ")\n";
    
    // Annuler la commande payée
    $order2->cancel('Test annulation commande payée');
    $stockAfterCancelPaid = $product->fresh()->quantity;
    echo "📊 Stock après annulation commande payée: {$stockAfterCancelPaid} (doit revenir à {$initialStock})\n";
    
    if ($stockAfterCancelPaid == $initialStock) {
        echo "✅ CORRECT: Stock restauré pour commande payée\n\n";
    } else {
        echo "❌ ERREUR: Stock mal restauré pour commande payée!\n\n";
    }
    
    // Test 3: Suppression de commande non payée
    echo "🧪 TEST 3: Suppression commande NON PAYÉE\n";
    echo "-----------------------------------------\n";
    
    $cart3 = $user->getOrCreateActiveCart();
    $cart3->items()->delete();
    $cart3->addProduct($product, 3);
    
    $order3 = Order::createFromCart(
        $cart3,
        [
            'name' => 'Test User',
            'address' => '123 Test Street',
            'city' => 'Test City',
            'postal_code' => '12345',
            'country' => 'France'
        ],
        [
            'name' => 'Test User',
            'address' => '123 Test Street',
            'city' => 'Test City',
            'postal_code' => '12345',
            'country' => 'France'
        ],
        'card'
    );
    
    $stockBeforeDelete = $product->fresh()->quantity;
    echo "📊 Stock avant suppression: {$stockBeforeDelete}\n";
    
    // Supprimer la commande via l'Observer
    $order3->delete();
    $stockAfterDelete = $product->fresh()->quantity;
    echo "📊 Stock après suppression: {$stockAfterDelete}\n";
    
    if ($stockAfterDelete == $stockBeforeDelete) {
        echo "✅ CORRECT: Stock inchangé pour suppression commande non payée\n\n";
    } else {
        echo "❌ ERREUR: Stock modifié pour suppression commande non payée!\n\n";
    }
    
    DB::rollback();
    
    echo "🎉 TESTS TERMINÉS\n";
    echo "=================\n";
    echo "📊 Stock final: {$product->fresh()->quantity} (doit être égal au stock initial: {$initialStock})\n";
    
    if ($product->fresh()->quantity == $initialStock) {
        echo "✅ TOUS LES TESTS RÉUSSIS: Le bug de stock est corrigé!\n";
    } else {
        echo "❌ ÉCHEC: Le bug de stock persiste\n";
    }
    
} catch (\Exception $e) {
    DB::rollback();
    echo "❌ Erreur lors du test: " . $e->getMessage() . "\n";
    echo "📊 Stock final: {$product->fresh()->quantity}\n";
}
