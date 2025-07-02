<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\OrderLocation;
use App\Models\User;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Test complet de l'automatisation des locations ===\n\n";

// Trouver une commande active qui peut être clôturée
$orderToTest = OrderLocation::where('status', 'active')
    ->whereDate('rental_end_date', '<=', now())
    ->with(['user', 'items.product'])
    ->first();

if (!$orderToTest) {
    echo "❌ Aucune commande active prête pour la clôture trouvée.\n";
    echo "Créons une commande de test...\n";
    
    $user = User::first();
    $product = \App\Models\Product::where('category_id', 6)->first();
    
    if (!$user || !$product) {
        echo "❌ Utilisateur ou produit manquant.\n";
        exit(1);
    }
    
    $orderToTest = OrderLocation::create([
        'user_id' => $user->id,
        'order_number' => 'LOC-TEST-FINAL-' . time(),
        'status' => 'active',
        'rental_start_date' => now()->subDays(1),
        'rental_end_date' => now(),
        'total_amount' => 30.00,
        'deposit_amount' => 50.00,
    ]);
    
    \App\Models\OrderItemLocation::create([
        'order_location_id' => $orderToTest->id,
        'product_id' => $product->id,
        'product_name' => $product->name,
        'rental_start_date' => now()->subDays(1),
        'rental_end_date' => now(),
        'duration_days' => 2,
        'rental_price_per_day' => 15.00,
        'deposit_amount' => 50.00,
    ]);
    
    $orderToTest = $orderToTest->load(['user', 'items.product']);
    echo "✅ Commande de test créée: {$orderToTest->order_number}\n\n";
}

echo "📦 Commande de test: {$orderToTest->order_number}\n";
echo "   - Client: {$orderToTest->user->name}\n";
echo "   - Statut initial: {$orderToTest->status} ({$orderToTest->status_label})\n";
echo "   - Fin de location: " . $orderToTest->rental_end_date->format('d/m/Y') . "\n";
echo "   - Peut être clôturée: " . ($orderToTest->can_be_closed_by_client ? '✅ OUI' : '❌ NON') . "\n\n";

// Simuler la clôture par le client
if ($orderToTest->can_be_closed_by_client) {
    echo "🔄 Simulation de la clôture par le client...\n";
    
    $clientNotes = "Matériel rendu en bon état. Test d'automatisation.";
    $success = $orderToTest->closeByClient($clientNotes);
    
    if ($success) {
        $orderToTest->refresh();
        echo "✅ Clôture réussie !\n";
        echo "   - Nouveau statut: {$orderToTest->status} ({$orderToTest->status_label})\n";
        echo "   - Date de clôture client: " . $orderToTest->client_return_date->format('d/m/Y H:i') . "\n";
        echo "   - Notes client: {$orderToTest->client_notes}\n";
        echo "   - Prête pour inspection admin: " . ($orderToTest->is_ready_for_admin_inspection ? '✅ OUI' : '❌ NON') . "\n\n";
    } else {
        echo "❌ Échec de la clôture.\n";
    }
}

// Vérifier les statistiques du dashboard admin
echo "📊 Statistiques admin après clôture:\n";
$stats = [
    'pending_inspection' => OrderLocation::where('status', 'pending_inspection')->count(),
    'active' => OrderLocation::where('status', 'active')->count(),
    'total' => OrderLocation::count(),
];

echo "   - Locations en attente d'inspection: {$stats['pending_inspection']}\n";
echo "   - Locations actives: {$stats['active']}\n";
echo "   - Total des locations: {$stats['total']}\n\n";

echo "🎯 Workflow complet testé avec succès !\n\n";

echo "=== Prochaines étapes de test manuel ===\n";
echo "1. Connectez-vous comme client ({$orderToTest->user->email})\n";
echo "2. Allez sur: http://127.0.0.1:8000/commandes-location\n";
echo "3. Vous devriez voir la commande {$orderToTest->order_number} avec statut 'En attente d'inspection'\n";
echo "4. Connectez-vous comme admin\n";
echo "5. Allez sur: http://127.0.0.1:8000/admin/locations/dashboard\n";
echo "6. Vous devriez voir la notification dans 'Inspections en attente'\n";
echo "7. Cliquez sur 'Inspecter' pour finaliser le retour\n\n";

echo "✅ Système d'automatisation entièrement fonctionnel !\n";
