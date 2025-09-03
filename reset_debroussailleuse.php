<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\OrderLocation;
use App\Models\OrderItemLocation;
use Carbon\Carbon;

// Trouver la commande de la débroussailleuse
$order = OrderLocation::where('order_number', 'LOC-20250903-FZUX3N')->first();

if ($order) {
    echo "🔄 Remise à zéro de la commande: " . $order->order_number . "\n";
    
    // Remettre à zéro tous les champs d'inspection
    $order->update([
        'status' => 'completed', // Utiliser un statut valide
        'inspection_status' => 'pending',
        'inspection_started_at' => null,
        'inspection_completed_at' => null,
        'late_fees' => 0,
        'damage_cost' => 0,
        'penalty_amount' => 0,
        'deposit_refund' => $order->deposit_amount, // Remettre le montant du dépôt initial
        'has_damages' => false,
        'damage_notes' => null,
        'damage_photos' => null,
        'auto_calculate_damages' => true,
        'general_inspection_notes' => null
    ]);
    
    // Remettre à zéro les items
    foreach ($order->orderItemLocations as $item) {
        $item->update([
            'condition_at_return' => null,
            'damage_cost' => 0,
            'inspection_notes' => null
        ]);
    }
    
    echo "✅ Commande remise à zéro avec succès!\n";
    echo "📋 Statut: " . $order->status . "\n";
    echo "🔍 Inspection: " . $order->inspection_status . "\n";
    echo "📅 Retour effectif: " . $order->actual_return_date . "\n";
    echo "⏰ Jours de retard: " . $order->late_days . "\n";
    
} else {
    echo "❌ Commande non trouvée\n";
}
