<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\Order;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Test du système d'automatisation des statuts ===\n\n";

// Afficher l'état actuel des commandes
echo "📊 État actuel des commandes:\n";
$statuses = [
    'pending' => 'En attente',
    'confirmed' => 'Confirmée', 
    'preparation' => 'En préparation',
    'shipped' => 'Expédiée',
    'delivered' => 'Livrée',
    'cancelled' => 'Annulée'
];

foreach ($statuses as $status => $label) {
    $count = Order::where('status', $status)->count();
    echo "   - {$label}: {$count} commande(s)\n";
}

echo "\n🔧 Test de la commande d'automatisation...\n";

// Exécuter la commande d'automatisation
try {
    $exitCode = \Artisan::call('orders:update-status');
    $output = \Artisan::output();
    
    echo "✅ Commande exécutée avec succès (Code: {$exitCode})\n";
    echo "📄 Sortie de la commande:\n";
    echo $output;
    
} catch (\Exception $e) {
    echo "❌ Erreur lors de l'exécution: " . $e->getMessage() . "\n";
}

echo "\n📊 État après automatisation:\n";
foreach ($statuses as $status => $label) {
    $count = Order::where('status', $status)->count();
    echo "   - {$label}: {$count} commande(s)\n";
}

echo "\n🔗 Routes d'automatisation disponibles:\n";
try {
    echo "   - Dashboard: " . route('orders.automation') . "\n";
    echo "   - Exécution: " . route('orders.automation.run') . "\n";
    echo "   - Test: " . route('orders.automation.dry-run') . "\n";
    echo "   - Stats: " . route('orders.automation.stats') . "\n";
} catch (\Exception $e) {
    echo "❌ Erreur routes: " . $e->getMessage() . "\n";
}

echo "\n⏰ L'automatisation JavaScript s'exécutera toutes les minutes sur la page des commandes.\n";
echo "🎯 Système d'automatisation configuré !\n";
