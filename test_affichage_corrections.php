<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Models\OrderLocation;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔧 Test des corrections d'affichage\n";
echo "===================================\n\n";

// Récupérer la commande qui posait problème
$orderLocation = OrderLocation::where('order_number', 'LOC-INSPECT-1754514886')->first();

if (!$orderLocation) {
    echo "❌ Commande de test non trouvée\n";
    exit;
}

echo "📦 Commande testée: {$orderLocation->order_number}\n";
echo "📊 Statut: {$orderLocation->status}\n";
echo "📊 Inspection: {$orderLocation->inspection_status}\n\n";

echo "💰 Données actuelles:\n";
echo "   - Jours de retard: {$orderLocation->late_days}\n";
echo "   - Frais de retard: " . number_format($orderLocation->late_fees ?? 0, 2) . "€\n";
echo "   - Frais de dégâts: " . number_format($orderLocation->damage_cost ?? 0, 2) . "€\n";
echo "   - Total pénalités: " . number_format($orderLocation->penalty_amount ?? 0, 2) . "€\n\n";

echo "🎯 Tests de la logique d'affichage:\n";
echo "-----------------------------------\n";

$showSection = false;

// Test condition principale
if (($orderLocation->status === 'finished' && ($orderLocation->late_fees > 0 || $orderLocation->damage_cost > 0)) || 
    ($orderLocation->status === 'inspecting' && ($orderLocation->late_days > 0 || $orderLocation->damage_cost > 0))) {
    $showSection = true;
    echo "✅ Section '⚠️ Frais et Pénalités' doit s'afficher\n";
} else {
    echo "❌ Section '⚠️ Frais et Pénalités' ne doit PAS s'afficher\n";
}

// Test affichage frais de retard
if (($orderLocation->status === 'finished' && $orderLocation->late_fees > 0) || 
    ($orderLocation->status === 'inspecting' && $orderLocation->late_days > 0)) {
    echo "✅ Bloc 'Frais de retard' doit s'afficher : " . abs($orderLocation->late_days) . " jour(s)\n";
    
    if ($orderLocation->status === 'finished') {
        echo "   → Montant affiché: " . number_format($orderLocation->late_fees, 2) . "€\n";
    } else {
        echo "   → Montant calculé: " . number_format(abs($orderLocation->late_days) * 10, 2) . "€\n";
    }
} else {
    echo "❌ Bloc 'Frais de retard' ne doit PAS s'afficher\n";
}

// Test affichage frais de dégâts
if ($orderLocation->damage_cost > 0) {
    echo "✅ Bloc 'Frais de dégâts' doit s'afficher : " . number_format($orderLocation->damage_cost, 2) . "€\n";
} else {
    echo "✅ Bloc 'Frais de dégâts' ne doit PAS s'afficher (damage_cost = 0)\n";
}

// Test total
echo "✅ Total des pénalités affiché : ";
if ($orderLocation->status === 'finished') {
    echo number_format($orderLocation->penalty_amount ?? 0, 2) . "€\n";
} else {
    $calculated = ($orderLocation->late_fees ?? (abs($orderLocation->late_days) * 10)) + ($orderLocation->damage_cost ?? 0);
    echo number_format($calculated, 2) . "€\n";
}

echo "\n🎯 Résultat attendu après corrections:\n";
echo "--------------------------------------\n";
if ($showSection && $orderLocation->status === 'finished') {
    echo "📋 Section '⚠️ Frais et Pénalités' visible avec :\n";
    if ($orderLocation->late_fees > 0) {
        echo "   ✅ Frais de retard: " . abs($orderLocation->late_days) . " jour(s) = " . number_format($orderLocation->late_fees, 2) . "€\n";
    }
    if ($orderLocation->damage_cost > 0) {
        echo "   ✅ Frais de dégâts: " . number_format($orderLocation->damage_cost, 2) . "€\n";
    } else {
        echo "   ❌ Frais de dégâts: MASQUÉ (car 0€)\n";
    }
    echo "   ✅ Total pénalités: " . number_format($orderLocation->penalty_amount, 2) . "€\n";
} else {
    echo "❌ Section '⚠️ Frais et Pénalités' masquée\n";
}

echo "\n🔗 Testez maintenant sur: http://127.0.0.1:8000/admin/rental-returns/{$orderLocation->id}\n";
?>
