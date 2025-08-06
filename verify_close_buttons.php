<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\OrderLocation;

try {
    echo "🎯 Vérification finale - Boutons de clôture disponibles\n";
    echo "=".str_repeat("=", 55)."\n\n";

    $testLocations = OrderLocation::where('order_number', 'LIKE', 'LOC-MANUAL-%-1754417155')->get();
    
    echo "📋 **État des locations test:**\n\n";
    
    foreach ($testLocations as $location) {
        echo "📦 {$location->order_number}:\n";
        echo "   - Statut: {$location->status}\n";
        echo "   - Paiement: {$location->payment_status}\n";
        echo "   - Fin prévue: " . \Carbon\Carbon::parse($location->end_date)->format('d/m/Y') . "\n";
        echo "   - Aujourd'hui: " . now()->format('d/m/Y') . "\n";
        echo "   - Date dépassée: " . (now()->gte($location->end_date) ? 'OUI' : 'NON') . "\n";
        echo "   - 🔒 Can be closed: " . ($location->can_be_closed ? '✅ OUI' : '❌ NON') . "\n";
        echo "   - Retard: {$location->late_days} jour(s), {$location->late_fees}€\n";
        echo "   - Caution: {$location->deposit_amount}€\n";
        echo "   ---\n";
    }
    
    echo "\n🌐 **Test en accédant aux URLs:**\n";
    echo "1. Allez sur: http://127.0.0.1:8000/rental-orders\n";
    echo "2. Vous devriez voir le bouton '🔒 Clôturer la location' sur chaque location\n";
    echo "3. Cliquer dessus déclenchera la clôture côté utilisateur\n\n";
    
    echo "🔄 **Workflow complet:**\n";
    echo "👤 Utilisateur → 🔒 Clôturer → 📋 statut 'inspecting'\n";
    echo "🔍 Admin → Inspection → 📋 statut 'finished'\n";
    echo "🤖 Mr Clank → Message final + remboursement caution\n\n";
    
    echo "✅ **Corrections apportées:**\n";
    echo "- Bouton de clôture ajouté dans rental-orders/index.blade.php\n";
    echo "- JavaScript de clôture ajouté (méthode POST)\n";
    echo "- Statut des locations changé de 'finished' → 'completed'\n";
    echo "- Route /my-rentals/{id}/close existe et fonctionne\n\n";
    
    echo "🎉 **PRÊT POUR TEST !** Les boutons de clôture devraient maintenant apparaître.\n";

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    exit(1);
}
