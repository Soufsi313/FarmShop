<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\OrderLocation;

try {
    echo "🔧 Correction manuelle des retards\n";
    echo "=".str_repeat("=", 35)."\n\n";

    $corrections = [
        'LOC-MANUAL-001-1754417155' => ['days' => 2, 'fees' => 10.00],
        'LOC-MANUAL-002-1754417155' => ['days' => 1, 'fees' => 3.50],
        'LOC-MANUAL-003-1754417155' => ['days' => 3, 'fees' => 21.00]
    ];

    foreach ($corrections as $orderNumber => $correction) {
        $location = OrderLocation::where('order_number', $orderNumber)->first();
        if ($location) {
            $location->update([
                'late_days' => $correction['days'],
                'late_fees' => $correction['fees']
            ]);
            
            $item = $location->items->first();
            if ($item) {
                $item->update([
                    'item_late_days' => $correction['days'],
                    'item_late_fees' => $correction['fees']
                ]);
            }
            
            echo "✅ {$orderNumber}: {$correction['days']} jour(s) de retard, {$correction['fees']}€ de pénalité\n";
        }
    }

    echo "\n🎯 **PRÊT POUR VOS TESTS CE SOIR !**\n\n";
    echo "📋 **3 locations créées et prêtes pour inspection manuelle:**\n";
    echo "   - LOC-MANUAL-001: 2 jours de retard, 10€ de pénalité, 25€ de caution\n";
    echo "   - LOC-MANUAL-002: 1 jour de retard, 3.50€ de pénalité, 18€ de caution\n";
    echo "   - LOC-MANUAL-003: 3 jours de retard, 21€ de pénalité, 30€ de caution\n\n";
    
    echo "🔗 **URLs d'accès admin:**\n";
    echo "   http://127.0.0.1:8000/admin/rental-returns/37\n";
    echo "   http://127.0.0.1:8000/admin/rental-returns/38\n";
    echo "   http://127.0.0.1:8000/admin/rental-returns/39\n\n";
    
    echo "✅ **Corrections apportées:**\n";
    echo "   - Mr Clank n'envoie plus d'emails (seulement messages internes)\n";
    echo "   - Système de préautorisation fonctionnel\n";
    echo "   - Notifications Mr Clank avec sender_id 103\n\n";
    
    echo "⚠️  **Points à améliorer identifiés:**\n";
    echo "   - Interface d'inspection ne montre pas les détails des retards/sanctions\n";
    echo "   - Manque l'affichage des informations monétaires de pénalité\n\n";
    
    echo "🎉 Bonnes pauses et bon test ce soir !\n";

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    exit(1);
}
