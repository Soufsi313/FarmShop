<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\OrderLocation;

try {
    echo "🔍 Vérification des boutons d'action sur les locations test\n";
    echo "=".str_repeat("=", 58)."\n\n";

    $testLocations = OrderLocation::where('order_number', 'LIKE', 'LOC-MANUAL-%-1754417155')->get();
    
    foreach ($testLocations as $location) {
        echo "📦 {$location->order_number}:\n";
        echo "   - Statut: {$location->status}\n";
        echo "   - Paiement: {$location->payment_status}\n";
        echo "   - 👁️  Voir détails: ✅ TOUJOURS DISPONIBLE\n";
        echo "   - 📄 Télécharger facture: " . ($location->canGenerateInvoice() ? '✅ DISPONIBLE' : '❌ NON DISPONIBLE') . "\n";
        echo "   - 🔒 Clôturer location: " . ($location->can_be_closed ? '✅ DISPONIBLE' : '❌ NON DISPONIBLE') . "\n";
        echo "   ---\n";
    }
    
    echo "\n🎯 **Boutons attendus sur chaque location:**\n";
    echo "✅ 👁️ Voir les détails (toujours présent)\n";
    echo "✅ 📄 Télécharger facture (maintenant disponible pour statut 'completed')\n";
    echo "✅ 🔒 Clôturer la location (avec message amélioré)\n\n";
    
    echo "💬 **Message de confirmation amélioré:**\n";
    echo "🔒 CLÔTURE DE LOCATION\n";
    echo "\n";
    echo "Êtes-vous sûr de vouloir clôturer cette location ?\n";
    echo "\n";
    echo "✅ Cette action confirme que :\n";
    echo "• Vous avez rendu tout le matériel\n";
    echo "• Le matériel est en bon état\n";
    echo "• Vous acceptez l'inspection admin\n";
    echo "\n";
    echo "⚠️ Cette action ne peut pas être annulée.\n";
    echo "\n";
    echo "Confirmer la clôture ?\n\n";
    
    echo "🌐 **Testez maintenant sur:** http://127.0.0.1:8000/rental-orders\n";
    echo "🎉 **Tous les boutons devraient être visibles !**\n";

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    exit(1);
}
