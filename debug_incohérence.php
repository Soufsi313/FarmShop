<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Models\OrderLocation;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 Diagnostic des données incohérentes\n";
echo "======================================\n\n";

$orderLocation = OrderLocation::where('order_number', 'LOC-INSPECT-1754514886')->first();

if (!$orderLocation) {
    echo "❌ Commande non trouvée\n";
    exit;
}

echo "📦 Commande: {$orderLocation->order_number}\n";
echo "📊 Status: {$orderLocation->status}\n\n";

echo "🔍 Données brutes:\n";
echo "-----------------\n";
echo "late_days: {$orderLocation->late_days}\n";
echo "late_fees: " . ($orderLocation->late_fees ?? 'NULL') . "\n";
echo "damage_cost: " . ($orderLocation->damage_cost ?? 'NULL') . "\n";
echo "penalty_amount: " . ($orderLocation->penalty_amount ?? 'NULL') . "\n\n";

echo "🧮 Analyse des conditions:\n";
echo "--------------------------\n";
echo "Status === 'finished': " . ($orderLocation->status === 'finished' ? 'TRUE' : 'FALSE') . "\n";
echo "late_fees > 0: " . (($orderLocation->late_fees ?? 0) > 0 ? 'TRUE' : 'FALSE') . "\n";
echo "damage_cost > 0: " . (($orderLocation->damage_cost ?? 0) > 0 ? 'TRUE' : 'FALSE') . "\n\n";

echo "🎯 Ce qui devrait s'afficher:\n";
echo "----------------------------\n";

// Test condition section principale
$showMainSection = (($orderLocation->status === 'finished' && (($orderLocation->late_fees ?? 0) > 0 || ($orderLocation->damage_cost ?? 0) > 0)) || 
                   ($orderLocation->status === 'inspecting' && ($orderLocation->late_days > 0 || ($orderLocation->damage_cost ?? 0) > 0)));

echo "Section principale: " . ($showMainSection ? 'VISIBLE' : 'MASQUÉE') . "\n";

// Test condition frais de retard
$showLateFees = (($orderLocation->status === 'finished' && ($orderLocation->late_fees ?? 0) > 0) || 
                ($orderLocation->status === 'inspecting' && $orderLocation->late_days > 0));

echo "Bloc frais de retard: " . ($showLateFees ? 'VISIBLE' : 'MASQUÉ') . "\n";

if ($showLateFees) {
    echo "  → Jours: " . abs($orderLocation->late_days) . "\n";
    if ($orderLocation->status === 'finished') {
        echo "  → Montant: " . number_format($orderLocation->late_fees ?? 0, 2) . "€\n";
    } else {
        echo "  → Montant calculé: " . number_format(abs($orderLocation->late_days) * 10, 2) . "€\n";
    }
}

// Test condition frais de dégâts
$showDamageCosts = ($orderLocation->damage_cost ?? 0) > 0;
echo "Bloc frais de dégâts: " . ($showDamageCosts ? 'VISIBLE' : 'MASQUÉ') . "\n";

// Total
echo "Total pénalités: ";
if ($orderLocation->status === 'finished') {
    echo number_format($orderLocation->penalty_amount ?? 0, 2) . "€\n";
} else {
    $calc = (($orderLocation->late_fees ?? 0) ?: (abs($orderLocation->late_days) * 10)) + ($orderLocation->damage_cost ?? 0);
    echo number_format($calc, 2) . "€\n";
}

echo "\n❌ PROBLÈME DÉTECTÉ:\n";
echo "La condition utilise late_fees > 0 mais late_fees = " . ($orderLocation->late_fees ?? 'NULL') . "\n";
echo "Alors que penalty_amount = " . ($orderLocation->penalty_amount ?? 'NULL') . "\n";
echo "Il y a une incohérence dans les données sauvegardées!\n";
?>
