<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\OrderLocation;
use App\Models\User;

try {
    echo "🔍 Test de la requête d'historique des locations\n";
    echo "=".str_repeat("=", 45)."\n\n";

    $user = User::find(1); // Meftah Soufiane
    
    // Simuler la requête du contrôleur
    $query = OrderLocation::with(['user', 'items.product'])
                          ->where('user_id', 1)
                          ->orderBy('created_at', 'desc');
    
    $locations = $query->get();
    
    echo "📦 Total locations trouvées: " . $locations->count() . "\n\n";
    
    $testOrders = [
        'LOC-TEST-001-20250805',
        'LOC-TEST-002-20250805', 
        'LOC-TEST-003-20250805'
    ];
    
    echo "🎯 Recherche des commandes test dans les résultats:\n";
    
    foreach ($locations as $location) {
        if (in_array($location->order_number, $testOrders)) {
            echo "✅ {$location->order_number} présente dans les résultats\n";
            echo "   - Index: " . $locations->search($location) . "\n";
            echo "   - Créée: {$location->created_at}\n";
            echo "   - Status: {$location->status}\n";
        }
    }
    
    echo "\n📄 Top 10 locations par date:\n";
    $top10 = $locations->take(10);
    foreach ($top10 as $index => $location) {
        $isTest = in_array($location->order_number, $testOrders) ? '🎯' : '  ';
        echo "{$isTest} " . ($index + 1) . ". {$location->order_number} - {$location->created_at->format('d/m/Y H:i')} - {$location->status}\n";
    }

} catch (Exception $e) {
    echo "❌ Exception: " . $e->getMessage() . "\n";
    exit(1);
}
