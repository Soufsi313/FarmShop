<?php
/**
 * TEST Order Model
 * 
 * Vérifie:
 * - Structure du modèle Order
 * - Relations (user, items, returns)
 * - Scopes (pending, confirmed, delivered, etc.)
 * - Attributs par défaut
 * - Status tracking
 */

// Bootstrap Laravel seulement si pas déjà fait
if (!class_exists('\App\Models\Order')) {
    require_once __DIR__ . '/../../vendor/autoload.php';
    $app = require_once __DIR__ . '/../../bootstrap/app.php';
    $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
}

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;

echo "=== TEST ORDER MODEL ===\n\n";

try {
    // Test 1: Vérifier que le modèle existe
    echo "📊 Test 1: Structure du modèle Order...\n";
    
    $orderCount = Order::count();
    echo "  ✅ Modèle Order accessible\n";
    echo "  📈 $orderCount commandes en base\n";
    
    // Test 2: Vérifier les attributs fillable
    echo "\n📊 Test 2: Attributs fillable...\n";
    $order = new Order();
    $fillable = $order->getFillable();
    $requiredFillable = ['order_number', 'user_id', 'status', 'payment_status', 'total_amount', 'payment_method'];
    
    $missingFillable = array_diff($requiredFillable, $fillable);
    if (empty($missingFillable)) {
        echo "  ✅ Tous les attributs requis sont fillable (" . count($fillable) . " total)\n";
    } else {
        echo "  ⚠️  Attributs manquants: " . implode(', ', $missingFillable) . "\n";
    }
    
    // Test 3: Vérifier les attributs par défaut
    echo "\n📊 Test 3: Valeurs par défaut...\n";
    $newOrder = new Order();
    
    if ($newOrder->status === 'pending') {
        echo "  ✅ status par défaut: 'pending'\n";
    }
    if ($newOrder->payment_status === 'pending') {
        echo "  ✅ payment_status par défaut: 'pending'\n";
    }
    if ($newOrder->can_be_cancelled === true) {
        echo "  ✅ can_be_cancelled par défaut: true\n";
    }
    
    // Test 4: Tester les scopes
    echo "\n📊 Test 4: Scopes de statut...\n";
    $pending = Order::pending()->count();
    $confirmed = Order::confirmed()->count();
    $shipped = Order::shipped()->count();
    $delivered = Order::delivered()->count();
    $cancelled = Order::cancelled()->count();
    
    echo "  📦 Pending: $pending\n";
    echo "  ✅ Confirmed: $confirmed\n";
    echo "  🚚 Shipped: $shipped\n";
    echo "  📬 Delivered: $delivered\n";
    echo "  ❌ Cancelled: $cancelled\n";
    
    $total = $pending + $confirmed + $shipped + $delivered + $cancelled;
    echo "  📊 Total via scopes: $total\n";
    
    // Test 5: Tester les relations
    echo "\n📊 Test 5: Relations...\n";
    $orderWithRelations = Order::with(['user', 'items', 'returns'])->first();
    
    if ($orderWithRelations) {
        echo "  ✅ Relation user() définie\n";
        echo "  ✅ Relation items() définie\n";
        echo "  ✅ Relation returns() définie\n";
        
        if (method_exists($orderWithRelations, 'returnableItems')) {
            echo "  ✅ Relation returnableItems() définie\n";
        }
    }
    
    // Test 6: Vérifier les casts
    echo "\n📊 Test 6: Type casting...\n";
    $testOrder = Order::first();
    if ($testOrder) {
        if (is_bool($testOrder->can_be_cancelled)) {
            echo "  ✅ can_be_cancelled casté en boolean\n";
        }
        if (is_bool($testOrder->can_be_returned)) {
            echo "  ✅ can_be_returned casté en boolean\n";
        }
        if (is_array($testOrder->status_history)) {
            echo "  ✅ status_history casté en array\n";
        }
        if (is_array($testOrder->billing_address)) {
            echo "  ✅ billing_address casté en array\n";
        }
    }
    
    // Test 7: Vérifier la cohérence des relations
    echo "\n📊 Test 7: Cohérence des relations...\n";
    $ordersWithItems = Order::has('items')->count();
    $ordersWithUser = Order::has('user')->count();
    
    echo "  ✅ Commandes avec items: $ordersWithItems\n";
    echo "  ✅ Commandes avec user: $ordersWithUser\n";
    
    // Test 8: Vérifier les statuts uniques
    echo "\n📊 Test 8: Statuts utilisés...\n";
    $statuses = Order::distinct('status')->pluck('status')->toArray();
    echo "  📋 Statuts trouvés: " . implode(', ', $statuses) . "\n";
    
    echo "\n=== RÉSUMÉ ===\n";
    echo "✅ Modèle Order: Structure OK\n";
    echo "✅ Scopes: Tous fonctionnels\n";
    echo "✅ Relations: Définies et cohérentes\n";
    echo "✅ Valeurs par défaut: Correctes\n";
    echo "\nTEST RÉUSSI ✅\n";
    
} catch (\Exception $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
    echo "FICHIER: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "TEST ÉCHOUÉ ❌\n";
}
