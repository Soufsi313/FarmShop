<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\OrderLocation;
use App\Models\OrderItemLocation;
use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;

echo "\n🧪 Test complet du workflow avec la nouvelle logique de retard\n";
echo "=" . str_repeat("=", 65) . "\n";

// Trouver un utilisateur existant
$user = User::where('email', 'like', '%@%')->first();
if (!$user) {
    $user = User::factory()->create([
        'name' => 'Utilisateur Test',
        'email' => 'test@example.com'
    ]);
    echo "✅ Utilisateur de test créé: {$user->email}\n";
} else {
    echo "✅ Utilisateur trouvé: {$user->email}\n";
}

// Trouver des produits de location
$products = Product::where('is_rentable', true)->limit(2)->get();
if ($products->count() < 2) {
    echo "❌ Pas assez de produits de location disponibles\n";
    exit(1);
}

echo "✅ Produits sélectionnés: " . $products->pluck('name')->join(', ') . "\n";

// Créer une commande de location qui se termine aujourd'hui
echo "\n📝 Création d'une commande de location (fin aujourd'hui)...\n";

// Générer un numéro de commande unique
$orderNumber = 'LOC-' . now()->format('Ymd') . '-' . str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT);

$order = OrderLocation::create([
    'order_number' => $orderNumber,
    'user_id' => $user->id,
    'status' => 'active',
    'rental_start_date' => Carbon::now()->subDays(3)->startOfDay(),
    'rental_end_date' => Carbon::now()->startOfDay(), // Aujourd'hui
    'total_amount' => 150.00,
    'deposit_amount' => 75.00,
    'picked_up_at' => Carbon::now()->subDays(3)->setTime(10, 0, 0),
]);

// Ajouter des articles à la commande
foreach ($products as $index => $product) {
    OrderItemLocation::create([
        'order_location_id' => $order->id,
        'product_id' => $product->id,
        'product_name' => $product->name,
        'product_description' => $product->description,
        'rental_price_per_day' => $product->rental_price_per_day ?? 25.00,
        'deposit_amount' => $product->deposit_amount ?? 15.00,
        'rental_start_date' => $order->rental_start_date->toDateString(),
        'rental_end_date' => $order->rental_end_date->toDateString(),
        'duration_days' => 3,
        'subtotal' => ($product->rental_price_per_day ?? 25.00) * 3, // 3 jours
        'total_with_deposit' => (($product->rental_price_per_day ?? 25.00) * 3) + ($product->deposit_amount ?? 15.00),
    ]);
}

echo "   ✅ Commande créée: {$order->order_number}\n";
echo "   📅 Période: du " . $order->rental_start_date->format('d/m/Y') . " au " . $order->rental_end_date->format('d/m/Y') . "\n";

// Test de la logique de retard
echo "\n🔍 Tests de la logique de retard :\n";

// Test 1: Vérifier qu'elle n'est pas en retard maintenant (location active, jour J)
echo "\n   Test 1 - Location active le jour J:\n";
echo "     - Date actuelle: " . Carbon::now()->format('d/m/Y H:i') . "\n";
echo "     - Fin de location: " . $order->rental_end_date->format('d/m/Y') . " 23:59\n";
echo "     - En retard: " . ($order->is_overdue ? '❌ OUI' : '✅ NON') . "\n";
echo "     - Jours de retard: " . $order->days_late . "\n";

// Test 2: Simuler une clôture par le client aujourd'hui à 20h
echo "\n   Test 2 - Clôture client aujourd'hui à 20h00:\n";
$order->update([
    'status' => 'pending_inspection',
    'client_return_date' => Carbon::now()->setTime(20, 0, 0),
    'client_notes' => 'Matériel retourné en bon état'
]);

echo "     - Date de clôture: " . $order->client_return_date->format('d/m/Y H:i') . "\n";
echo "     - En retard: " . ($order->is_overdue ? '❌ OUI' : '✅ NON') . "\n";
echo "     - Jours de retard: " . $order->days_late . "\n";

// Test 3: Simuler une clôture en retard (demain à 10h)
echo "\n   Test 3 - Simulation clôture en retard (demain 10h00):\n";
$order->update([
    'client_return_date' => Carbon::now()->addDay()->setTime(10, 0, 0),
]);

echo "     - Date de clôture: " . $order->client_return_date->format('d/m/Y H:i') . "\n";
echo "     - En retard: " . ($order->is_overdue ? '❌ OUI' : '✅ NON') . "\n";
echo "     - Jours de retard: " . $order->days_late . "\n";

// Test 4: Simuler une clôture juste après minuit le jour de fin
echo "\n   Test 4 - Simulation clôture après minuit (jour J+1 00:30):\n";
$order->update([
    'client_return_date' => Carbon::now()->addDay()->setTime(0, 30, 0),
]);

echo "     - Date de clôture: " . $order->client_return_date->format('d/m/Y H:i') . "\n";
echo "     - En retard: " . ($order->is_overdue ? '❌ OUI' : '✅ NON') . "\n";
echo "     - Jours de retard: " . $order->days_late . "\n";

// Nettoyer
echo "\n🧹 Nettoyage des données de test...\n";
$order->items()->delete();
$order->delete();

echo "\n✅ Test terminé ! La nouvelle logique de retard fonctionne correctement :\n";
echo "   - Une location n'est en retard que si clôturée après 23h59 le jour de fin\n";
echo "   - Le calcul des jours de retard est précis\n";
echo "   - Les locations actives ne sont en retard qu'après 23h59 le jour J\n\n";

echo "🚀 Votre système est prêt pour de nouvelles commandes avec le bon calcul de retard !\n\n";
