<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\OrderLocation;

try {
    echo "🔍 Recherche des locations test\n";
    echo "=".str_repeat("=", 30)."\n\n";

    $testOrders = [
        'LOC-TEST-001-20250805',
        'LOC-TEST-002-20250805', 
        'LOC-TEST-003-20250805'
    ];

    foreach ($testOrders as $orderNumber) {
        $location = OrderLocation::where('order_number', $orderNumber)->first();
        
        if ($location) {
            echo "✅ {$orderNumber} trouvée:\n";
            echo "   - ID: {$location->id}\n";
            echo "   - User ID: {$location->user_id}\n";
            echo "   - Status: {$location->status}\n";
            echo "   - Caution: {$location->deposit_amount}€\n";
            echo "   - Créée le: {$location->created_at}\n";
            echo "   - Supprimée? " . ($location->deleted_at ? 'OUI ('.$location->deleted_at.')' : 'NON') . "\n";
            echo "   ---\n";
        } else {
            echo "❌ {$orderNumber} non trouvée\n";
        }
    }

    // Vérifier pourquoi elles n'apparaissent pas dans l'historique
    echo "\n🔍 Vérification des critères d'affichage de l'historique...\n";
    
    $user = \App\Models\User::find(1); // Meftah Soufiane
    if ($user) {
        echo "📧 Utilisateur: {$user->name} ({$user->email})\n";
        
        // Récupérer TOUTES les locations de cet utilisateur
        $allLocations = OrderLocation::where('user_id', 1)
                                   ->withTrashed() // Inclure les supprimées
                                   ->orderBy('created_at', 'desc')
                                   ->get();
        
        echo "📦 Total locations pour cet utilisateur: " . $allLocations->count() . "\n\n";
        
        foreach ($allLocations as $loc) {
            if (in_array($loc->order_number, $testOrders)) {
                echo "🎯 {$loc->order_number}:\n";
                echo "   - Status: {$loc->status}\n";
                echo "   - Inspection: " . ($loc->inspection_status ?? 'null') . "\n";
                echo "   - Supprimée: " . ($loc->deleted_at ? 'OUI' : 'NON') . "\n";
                echo "   - Payment status: " . ($loc->payment_status ?? 'null') . "\n";
                echo "   ---\n";
            }
        }
    }

} catch (Exception $e) {
    echo "❌ Exception: " . $e->getMessage() . "\n";
    exit(1);
}
