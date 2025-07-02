<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\OrderLocation;
use App\Models\OrderItemLocation;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Test du workflow de retour ===\n\n";

// Chercher une commande active ou en créer une
$activeOrder = OrderLocation::where('status', 'active')->with(['items'])->first();

if (!$activeOrder) {
    echo "❌ Aucune commande active trouvée.\n";
    echo "Créons une commande de test...\n";
    
    // Créer une commande de test
    $user = \App\Models\User::first();
    if (!$user) {
        echo "❌ Aucun utilisateur trouvé. Créez d'abord un utilisateur.\n";
        exit(1);
    }
    
    $product = \App\Models\Product::where('category_id', 6)->first(); // Outillage
    if (!$product) {
        echo "❌ Aucun produit d'outillage trouvé.\n";
        exit(1);
    }
    
    $orderLocation = OrderLocation::create([
        'user_id' => $user->id,
        'order_number' => 'LOC-TEST-' . time(),
        'status' => 'active',
        'rental_start_date' => now(),
        'rental_end_date' => now()->addDays(3),
        'total_amount' => 50.00,
        'deposit_amount' => 100.00,
    ]);
    
    OrderItemLocation::create([
        'order_location_id' => $orderLocation->id,
        'product_id' => $product->id,
        'product_name' => $product->name,
        'duration_days' => 3,
        'rental_price_per_day' => 15.00,
        'deposit_amount' => 100.00,
    ]);
    
    $activeOrder = $orderLocation->load(['items']);
    echo "✅ Commande de test créée: {$activeOrder->order_number}\n";
}

echo "📦 Commande active trouvée: {$activeOrder->order_number}\n";
echo "   - Statut: {$activeOrder->status}\n";
echo "   - Articles: " . $activeOrder->items->count() . "\n";
echo "   - URL de retour: /admin/locations/{$activeOrder->id}/return\n\n";

// Préparer les données de test pour le retour
$returnData = [
    'return_notes' => 'Matériel retourné en bon état lors du test automatique',
    'items' => [],
    'late_fee' => 0
];

foreach ($activeOrder->items as $item) {
    $returnData['items'][] = [
        'id' => $item->id,
        'condition' => 'excellent',
        'notes' => 'Bon état général',
        'damage_fee' => 0
    ];
}

echo "=== Données de test pour le retour ===\n";
echo "Notes générales: " . $returnData['return_notes'] . "\n";
echo "Frais de retard: " . $returnData['late_fee'] . "€\n";
echo "Articles:\n";
foreach ($returnData['items'] as $index => $item) {
    echo "  - Article #{$item['id']}: {$item['condition']} - {$item['notes']}\n";
}

echo "\n=== Instructions pour le test manuel ===\n";
echo "1. Connectez-vous comme admin sur : http://127.0.0.1:8000/admin\n";
echo "2. Allez sur : http://127.0.0.1:8000/admin/locations/{$activeOrder->id}\n";
echo "3. Cliquez sur 'Inspecter et marquer comme terminé'\n";
echo "4. Remplissez le formulaire de retour\n";
echo "5. Cliquez sur 'Valider le retour'\n";
echo "6. Vous devriez être redirigé vers la page de détail avec un message de succès\n";
echo "7. Le statut de la commande devrait passer à 'returned'\n\n";

echo "✅ Prêt pour le test !\n";
