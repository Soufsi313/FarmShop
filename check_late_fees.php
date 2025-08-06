<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Vérification des frais de retard ===\n\n";

$order = App\Models\OrderLocation::where('order_number', 'LOC-TEST-INSPECTION-1754427887')->first();

if ($order) {
    echo "Commande trouvée: " . $order->order_number . "\n";
    echo "Statut: " . $order->status . "\n";
    echo "Date de fin prévue: " . $order->end_date . "\n";
    echo "Date de retour réelle: " . ($order->actual_return_date ?? 'Non définie') . "\n";
    echo "Jours de retard: " . ($order->late_days ?? 0) . "\n";
    echo "Frais de retard: " . ($order->late_fees ?? 0) . "€\n";
    echo "Pénalités: " . ($order->penalty_amount ?? 0) . "€\n\n";
    
    // Ajouter quelques jours de retard pour le test
    echo "=== Simulation de frais de retard ===\n";
    $order->update([
        'late_days' => 3,
        'late_fees' => 30.00, // 10€ par jour
        'actual_return_date' => now()
    ]);
    
    echo "Frais de retard ajoutés: 3 jours = 30€\n";
    echo "Commande mise à jour avec succès!\n";
    
} else {
    echo "Commande non trouvée.\n";
}
