<?php
// Diagnostic spécifique pour la commande FS202507000013
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DIAGNOSTIC COMMANDE FS202507000013 ===\n\n";

$order = \App\Models\Order::where('order_number', 'FS202507000013')->first();

if (!$order) {
    echo "Commande non trouvée.\n";
    exit;
}

echo "Commande trouvée:\n";
echo "  - ID: {$order->id}\n";
echo "  - Numéro: {$order->order_number}\n";
echo "  - Statut: {$order->status}\n";
echo "  - Créée le: " . $order->created_at . "\n";
echo "  - Mise à jour le: " . $order->updated_at . "\n";

// Vérifier les champs de timestamp spécifiques
$timestamps = ['confirmed_at', 'preparation_at', 'shipped_at', 'delivered_at'];
foreach ($timestamps as $field) {
    $value = $order->$field;
    echo "  - {$field}: " . ($value ? $value : 'NULL') . "\n";
}

// Calculer manuellement si la commande devrait être mise à jour
$now = \Carbon\Carbon::now();
echo "\nAnalyse pour transition confirmed -> preparation:\n";
echo "  - Statut actuel: {$order->status}\n";
echo "  - Temps actuel: {$now}\n";

if ($order->status === 'confirmed') {
    if ($order->confirmed_at) {
        $delayPassed = $now->diffInSeconds($order->confirmed_at);
        echo "  - confirmed_at: {$order->confirmed_at}\n";
        echo "  - Délai écoulé: {$delayPassed} secondes\n";
        echo "  - Devrait être mis à jour: " . ($delayPassed >= 60 ? 'OUI' : 'NON') . "\n";
    } else {
        echo "  - confirmed_at est NULL - utilisons updated_at\n";
        $delayPassed = $now->diffInSeconds($order->updated_at);
        echo "  - updated_at: {$order->updated_at}\n";
        echo "  - Délai écoulé: {$delayPassed} secondes\n";
        echo "  - Devrait être mis à jour: " . ($delayPassed >= 60 ? 'OUI' : 'NON') . "\n";
    }
}

// Test de la requête que fait la commande
echo "\nTest de la requête d'automatisation:\n";
$testQuery = \App\Models\Order::where('status', 'confirmed');
if ($order->confirmed_at) {
    $testQuery->where('confirmed_at', '<=', $now->copy()->subSeconds(60));
} else {
    echo "  - confirmed_at est NULL, la requête ne trouvera pas cette commande\n";
}

$foundOrders = $testQuery->get();
echo "  - Commandes trouvées par la requête: {$foundOrders->count()}\n";
foreach ($foundOrders as $foundOrder) {
    echo "    * {$foundOrder->order_number} (confirmed_at: {$foundOrder->confirmed_at})\n";
}

echo "\nDiagnostic terminé.\n";
