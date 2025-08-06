<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Models\OrderLocation;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 Diagnostic des calculs de pénalités\n";
echo "======================================\n\n";

// Récupérer la dernière commande terminée
$orderLocation = OrderLocation::with(['orderItemLocations'])
    ->where('status', 'finished')
    ->latest()
    ->first();

if (!$orderLocation) {
    echo "❌ Aucune commande terminée trouvée\n";
    exit;
}

echo "📦 Commande: {$orderLocation->order_number}\n";
echo "👤 Client: " . ($orderLocation->user->name ?? 'N/A') . "\n";
echo "📊 Statut: {$orderLocation->status}\n";
echo "🔍 Inspection: {$orderLocation->inspection_status}\n\n";

echo "💰 Valeurs stockées en base:\n";
echo "-----------------------------\n";
echo "• Frais de retard (late_fees): " . number_format($orderLocation->late_fees ?? 0, 2) . "€\n";
echo "• Frais de dégâts (damage_cost): " . number_format($orderLocation->damage_cost ?? 0, 2) . "€\n";
echo "• Total pénalités (penalty_amount): " . number_format($orderLocation->penalty_amount ?? 0, 2) . "€\n";
echo "• Dépôt initial (deposit_amount): " . number_format($orderLocation->deposit_amount ?? 0, 2) . "€\n";
echo "• Remboursement (deposit_refund): " . number_format($orderLocation->deposit_refund ?? 0, 2) . "€\n\n";

echo "🧮 Calculs manuels de vérification:\n";
echo "-----------------------------------\n";

// Calculer les dégâts par produit
$itemDamages = 0;
foreach ($orderLocation->orderItemLocations as $item) {
    $damage = $item->item_damage_cost ?? 0;
    if ($damage > 0) {
        echo "• {$item->product_name}: " . number_format($damage, 2) . "€\n";
        $itemDamages += $damage;
    }
}

$lateFees = $orderLocation->late_fees ?? 0;
$totalCalculated = $lateFees + $itemDamages;
$expectedRefund = max(0, ($orderLocation->deposit_amount ?? 0) - $totalCalculated);

echo "\n📊 Résumé calculé:\n";
echo "• Frais de retard: " . number_format($lateFees, 2) . "€\n";
echo "• Total dégâts (par produit): " . number_format($itemDamages, 2) . "€\n";
echo "• TOTAL PÉNALITÉS CALCULÉ: " . number_format($totalCalculated, 2) . "€\n";
echo "• Remboursement attendu: " . number_format($expectedRefund, 2) . "€\n\n";

echo "⚠️  Comparaison:\n";
echo "• penalty_amount en base: " . number_format($orderLocation->penalty_amount ?? 0, 2) . "€\n";
echo "• Calculé manuellement: " . number_format($totalCalculated, 2) . "€\n";
echo "• Différence: " . number_format(($orderLocation->penalty_amount ?? 0) - $totalCalculated, 2) . "€\n\n";

if (($orderLocation->penalty_amount ?? 0) != $totalCalculated) {
    echo "❌ PROBLÈME: Les valeurs en base ne correspondent pas aux calculs!\n";
} else {
    echo "✅ Les calculs correspondent aux valeurs en base\n";
}
?>
