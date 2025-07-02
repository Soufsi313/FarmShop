<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\OrderLocation;
use App\Models\OrderItemLocation;
use App\Models\User;
use App\Models\Product;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Test du workflow d'automatisation des locations ===\n\n";

// Chercher une commande active ou en créer une
$activeOrder = OrderLocation::where('status', 'active')
    ->where('rental_end_date', '<=', now()->endOfDay())
    ->with(['items'])->first();

if (!$activeOrder) {
    echo "❌ Aucune commande active qui se termine aujourd'hui trouvée.\n";
    echo "Créons une commande de test...\n";
    
    // Créer une commande de test
    $user = User::first();
    if (!$user) {
        echo "❌ Aucun utilisateur trouvé. Créez d'abord un utilisateur.\n";
        exit(1);
    }
    
    $product = Product::where('category_id', 6)->first(); // Outillage
    if (!$product) {
        echo "❌ Aucun produit d'outillage trouvé.\n";
        exit(1);
    }
    
    $orderLocation = OrderLocation::create([
        'user_id' => $user->id,
        'order_number' => 'LOC-AUTO-' . time(),
        'status' => 'active',
        'rental_start_date' => now()->subDays(2),
        'rental_end_date' => now(), // Se termine aujourd'hui
        'total_amount' => 50.00,
        'deposit_amount' => 100.00,
        'picked_up_at' => now()->subDays(2),
    ]);
    
    OrderItemLocation::create([
        'order_location_id' => $orderLocation->id,
        'product_id' => $product->id,
        'product_name' => $product->name,
        'rental_start_date' => now()->subDays(2),
        'rental_end_date' => now(),
        'duration_days' => 3,
        'rental_price_per_day' => 15.00,
        'deposit_amount' => 100.00,
    ]);
    
    $activeOrder = $orderLocation->load(['items']);
    echo "✅ Commande de test créée: {$activeOrder->order_number}\n";
}

echo "📦 Commande active trouvée: {$activeOrder->order_number}\n";
echo "   - Statut: {$activeOrder->status}\n";
echo "   - Fin prévue: " . $activeOrder->rental_end_date->format('d/m/Y H:i') . "\n";
echo "   - Peut être clôturée par le client: " . ($activeOrder->can_be_closed_by_client ? 'Oui' : 'Non') . "\n";
echo "   - Nécessite une action client: " . ($activeOrder->needs_client_action ? 'Oui' : 'Non') . "\n";
echo "   - Articles: " . $activeOrder->items->count() . "\n";

echo "\n=== URLs de test ===\n";
echo "Liste des locations client: http://127.0.0.1:8000/commandes-location\n";
echo "Détail de cette location: http://127.0.0.1:8000/commandes-location/{$activeOrder->id}\n";
echo "Dashboard admin: http://127.0.0.1:8000/admin/locations/dashboard\n";

echo "\n=== Test de clôture par le client ===\n";
if ($activeOrder->can_be_closed_by_client) {
    echo "✅ Cette location peut être clôturée par le client.\n";
    echo "URL de clôture: POST http://127.0.0.1:8000/commandes-location/{$activeOrder->id}/cloturer\n";
} else {
    echo "❌ Cette location ne peut pas être clôturée maintenant.\n";
    echo "Raisons possibles:\n";
    echo "- Statut actuel: {$activeOrder->status}\n";
    echo "- Date de fin: " . $activeOrder->rental_end_date->format('d/m/Y') . " (aujourd'hui: " . now()->format('d/m/Y') . ")\n";
}

echo "\n✅ Test terminé !\n";
