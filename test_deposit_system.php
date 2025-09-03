<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\OrderLocation;

echo "=== Test du nouveau système de caution/dommages ===\n\n";

// Chercher une location pour tester
$orderLocation = OrderLocation::where('status', 'finished')
    ->orWhere('status', 'inspecting')
    ->first();

if (!$orderLocation) {
    echo "❌ Aucune location trouvée pour le test\n";
    exit(1);
}

echo "📋 Test avec la location '{$orderLocation->order_number}':\n";
echo "   - ID: {$orderLocation->id}\n";
echo "   - Statut: {$orderLocation->status}\n";
echo "   - Montant caution: " . number_format($orderLocation->deposit_amount, 2) . "€\n";
echo "   - Frais de retard: " . number_format($orderLocation->late_fees ?? 0, 2) . "€\n";

// Test du calcul automatique des dommages
echo "\n🧪 Tests de calcul de dommages:\n";

echo "\n1. Test sans dommages:\n";
$orderLocation->has_damages = false;
$orderLocation->auto_calculate_damages = true;
$damageAmount = $orderLocation->calculateDamageAmount();
echo "   - Dommages calculés: " . number_format($damageAmount, 2) . "€\n";
echo "   - Caution sera capturée: " . ($orderLocation->deposit_will_be_captured ? 'Oui' : 'Non') . "\n";
echo "   - Montant libéré: " . number_format($orderLocation->deposit_release_amount, 2) . "€\n";

echo "\n2. Test avec dommages:\n";
$orderLocation->has_damages = true;
$orderLocation->auto_calculate_damages = true;
$damageAmount = $orderLocation->calculateDamageAmount();
echo "   - Dommages calculés: " . number_format($damageAmount, 2) . "€\n";
echo "   - Caution sera capturée: " . ($orderLocation->deposit_will_be_captured ? 'Oui' : 'Non') . "\n";
echo "   - Montant libéré: " . number_format($orderLocation->deposit_release_amount, 2) . "€\n";

echo "\n✅ Nouvelles méthodes de calcul:\n";
echo "   1. ✅ calculateDamageAmount() - Calcul automatique\n";
echo "   2. ✅ getDepositWillBeCapturedAttribute() - Détection capture\n";
echo "   3. ✅ getDepositReleaseAmountAttribute() - Montant libération\n";

echo "\n💡 Changements de terminologie:\n";
echo "   - 'Remboursement' → 'Libération de caution'\n";
echo "   - 'Caution versée' → 'Caution pré-autorisée'\n";
echo "   - Calcul automatique: Caution + Frais de retard si dommages\n";

echo "\n🎯 Points clés du nouveau système:\n";
echo "   - Caution pré-autorisée (bloquée) pas débitée\n";
echo "   - Si aucun dommage: caution libérée intégralement\n";
echo "   - Si dommages: caution capturée + frais = montant débité\n";
echo "   - Plus de saisie manuelle des montants de dommages\n";

echo "\n=== Test terminé ===\n";
