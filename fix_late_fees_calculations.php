<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\OrderLocation;

try {
    echo "🔧 Correction des calculs de retard pour les locations test\n";
    echo "=".str_repeat("=", 55)."\n\n";

    $testLocations = OrderLocation::where('order_number', 'LIKE', 'LOC-MANUAL-%-1754417155')->get();
    
    foreach ($testLocations as $location) {
        $endDate = \Carbon\Carbon::parse($location->end_date);
        $returnDate = \Carbon\Carbon::parse($location->actual_return_date);
        
        // Calculer correctement les jours de retard
        $lateDays = 0;
        if ($returnDate->gt($endDate)) {
            $lateDays = $returnDate->diffInDays($endDate);
        }
        
        $lateFees = $lateDays * $location->late_fee_per_day;
        
        echo "📦 {$location->order_number}:\n";
        echo "   - Fin prévue: {$endDate->format('d/m/Y')}\n";
        echo "   - Retour effectif: {$returnDate->format('d/m/Y')}\n";
        echo "   - Jours de retard: {$lateDays}\n";
        echo "   - Frais de retard: {$lateFees}€\n";
        
        // Mettre à jour
        $location->update([
            'late_days' => $lateDays,
            'late_fees' => $lateFees
        ]);
        
        // Mettre à jour l'item aussi
        $item = $location->items->first();
        if ($item) {
            $item->update([
                'item_late_days' => $lateDays,
                'item_late_fees' => $lateFees
            ]);
        }
        
        echo "   ✅ Corrigé\n---\n";
    }
    
    echo "\n🎯 **Résumé:**\n";
    echo "✅ Les calculs de retard ont été corrigés\n";
    echo "🔍 Les locations sont prêtes pour inspection manuelle\n";
    echo "📧 Mr Clank enverra seulement des messages internes (pas d'emails)\n\n";
    
    echo "💡 **Points à noter pour votre test ce soir:**\n";
    echo "1. Les détails des retards ne sont pas affichés dans l'interface d'inspection\n";
    echo "2. Il faut ajouter l'affichage des sanctions monétaires\n";
    echo "3. Le système de préautorisation est opérationnel\n";
    echo "4. Mr Clank n'envoie plus d'emails, seulement messages internes\n";

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    exit(1);
}
