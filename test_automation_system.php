<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\OrderLocation;
use App\Models\User;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Test du système d'automatisation des locations ===\n\n";

// Vérifier qu'il y a des commandes actives
$activeOrders = OrderLocation::where('status', 'active')->with(['user', 'items.product'])->get();

if ($activeOrders->isEmpty()) {
    echo "❌ Aucune commande active trouvée. Créons des données de test...\n";
    
    // Créer une commande active de test
    $user = User::first();
    if (!$user) {
        echo "❌ Aucun utilisateur trouvé.\n";
        exit(1);
    }
    
    $product = \App\Models\Product::where('category_id', 6)->first(); // Outillage
    if (!$product) {
        echo "❌ Aucun produit d'outillage trouvé.\n";
        exit(1);
    }
    
    $orderLocation = OrderLocation::create([
        'user_id' => $user->id,
        'order_number' => 'LOC-AUTO-TEST-' . time(),
        'status' => 'active',
        'rental_start_date' => now()->subDays(2),
        'rental_end_date' => now(), // Fin aujourd'hui pour tester la clôture
        'total_amount' => 45.00,
        'deposit_amount' => 100.00,
    ]);
    
    \App\Models\OrderItemLocation::create([
        'order_location_id' => $orderLocation->id,
        'product_id' => $product->id,
        'product_name' => $product->name,
        'rental_start_date' => now()->subDays(2),
        'rental_end_date' => now(),
        'duration_days' => 3,
        'rental_price_per_day' => 15.00,
        'deposit_amount' => 100.00,
    ]);
    
    $activeOrders = collect([$orderLocation->load(['user', 'items'])]);
    echo "✅ Commande de test créée: {$orderLocation->order_number}\n";
}

echo "✅ " . $activeOrders->count() . " commande(s) active(s) trouvée(s).\n\n";

// Tester les nouvelles propriétés
foreach ($activeOrders->take(3) as $order) {
    echo "📦 Commande #{$order->order_number}\n";
    echo "   - Client: {$order->user->name}\n";
    echo "   - Statut: {$order->status} ({$order->status_label})\n";
    echo "   - Période: du " . $order->rental_start_date->format('d/m/Y') . " au " . $order->rental_end_date->format('d/m/Y') . "\n";
    
    // Tester les nouvelles propriétés
    echo "   - Peut être clôturée par le client: " . ($order->can_be_closed_by_client ? '✅ OUI' : '❌ NON') . "\n";
    echo "   - Peut être annulée par le client: " . ($order->can_be_cancelled_by_client ? '✅ OUI' : '❌ NON') . "\n";
    echo "   - Nécessite une action client: " . ($order->needs_client_action ? '⚠️ OUI' : '✅ NON') . "\n";
    echo "   - Prête pour inspection admin: " . ($order->is_ready_for_admin_inspection ? '🔍 OUI' : '❌ NON') . "\n";
    echo "\n";
}

// Vérifier les commandes en attente d'inspection
$pendingInspections = OrderLocation::where('status', 'pending_inspection')->with(['user', 'items'])->get();
echo "🔍 Commandes en attente d'inspection: " . $pendingInspections->count() . "\n";

foreach ($pendingInspections as $order) {
    echo "   - {$order->order_number} (Client: {$order->user->name})\n";
    if ($order->client_return_date) {
        echo "     Clôturée le: " . $order->client_return_date->format('d/m/Y H:i') . "\n";
    }
    if ($order->client_notes) {
        echo "     Notes client: " . Str::limit($order->client_notes, 50) . "\n";
    }
}

echo "\n=== URLs de test ===\n";
echo "1. Interface client 'Mes locations' : http://127.0.0.1:8000/commandes-location\n";
echo "2. Dashboard admin locations : http://127.0.0.1:8000/admin/locations/dashboard\n";
echo "3. Liste admin locations : http://127.0.0.1:8000/admin/locations\n\n";

echo "=== Workflow d'automatisation ===\n";
echo "1. Le client voit ses locations actives dans 'Mes locations'\n";
echo "2. Le jour de fin de location, un bouton 'Clôturer la location' apparaît\n";
echo "3. Le client clique et confirme la clôture (avec notes optionnelles)\n";
echo "4. Le statut passe à 'pending_inspection'\n";
echo "5. L'admin voit la notification dans son dashboard\n";
echo "6. L'admin clique sur 'Inspecter' pour faire l'inspection de retour\n";
echo "7. Après validation, le statut passe à 'returned'\n\n";

echo "✅ Système d'automatisation prêt !\n";
