<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\OrderLocation;

try {
    echo "🔍 Analyse des locations existantes\n";
    echo "=".str_repeat("=", 35)."\n\n";

    $locations = OrderLocation::take(10)->get();
    
    if ($locations->isEmpty()) {
        echo "❌ Aucune location trouvée\n";
        exit(1);
    }

    echo "📦 Locations disponibles:\n\n";
    
    foreach ($locations as $location) {
        echo "ID: {$location->id} | Numéro: {$location->order_number}\n";
        echo "   Status: {$location->status}\n";
        echo "   Caution: " . ($location->deposit_amount ?? '0') . "€\n";
        echo "   Total location: " . ($location->total_rental_cost ?? '0') . "€\n";
        echo "   Dates: " . ($location->start_date ?? 'N/A') . " → " . ($location->end_date ?? 'N/A') . "\n";
        echo "   ---\n";
    }
    
    // Chercher une location avec caution > 0
    $locationWithDeposit = OrderLocation::where('deposit_amount', '>', 0)->first();
    
    if ($locationWithDeposit) {
        echo "\n✅ Location avec caution trouvée: ID {$locationWithDeposit->id}\n";
        echo "   Caution: {$locationWithDeposit->deposit_amount}€\n";
    } else {
        echo "\n❌ Aucune location avec caution > 0 trouvée\n";
        echo "💡 Créons une location de test avec caution...\n";
    }

} catch (Exception $e) {
    echo "❌ Exception: " . $e->getMessage() . "\n";
    exit(1);
}
