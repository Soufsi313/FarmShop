<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\OrderLocation;

try {
    echo "🔧 Correction du statut des locations test pour permettre la clôture\n";
    echo "=".str_repeat("=", 65)."\n\n";

    $testLocations = OrderLocation::where('order_number', 'LIKE', 'LOC-MANUAL-%-1754417155')->get();
    
    foreach ($testLocations as $location) {
        echo "📦 {$location->order_number}:\n";
        echo "   - Statut actuel: {$location->status}\n";
        echo "   - Inspection: " . ($location->inspection_status ?? 'null') . "\n";
        echo "   - Can be closed avant: " . ($location->can_be_closed ? 'OUI' : 'NON') . "\n";
        
        // Changer le statut pour permettre la clôture
        $location->update([
            'status' => 'completed', // Au lieu de 'finished'
            'completed_at' => now(),
            'inspection_status' => null, // Pas encore inspecté
        ]);
        
        // Refresh pour voir les nouveaux attributs
        $location->refresh();
        echo "   - Can be closed après: " . ($location->can_be_closed ? 'OUI' : 'NON') . "\n";
        echo "   ✅ Statut mis à jour\n---\n";
    }
    
    echo "\n🎯 **Résultat:**\n";
    echo "✅ Les 3 locations sont maintenant au statut 'completed'\n";
    echo "🔒 Le bouton 'Clôturer la location' devrait maintenant apparaître\n";
    echo "👤 L'utilisateur peut clôturer manuellement les locations\n";
    echo "🔍 La clôture déclenchera l'inspection automatique par l'admin\n\n";
    
    echo "📋 **Workflow attendu:**\n";
    echo "1. Utilisateur clique sur 'Clôturer la location'\n";
    echo "2. Location passe en statut 'inspecting'\n";
    echo "3. Admin reçoit notification pour inspection\n";
    echo "4. Admin fait l'inspection → statut 'finished'\n";
    echo "5. Mr Clank envoie message final avec remboursement caution\n";

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    exit(1);
}
